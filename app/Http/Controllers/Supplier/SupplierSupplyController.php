<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\SupplierPayment;
use App\Models\SupplierSupply;
use App\Models\SupplierSupplyAttachment;
use App\Models\SupplierSupplyItem;
use App\Models\SystemNotification;
use App\Models\User;
use App\Services\SupplyWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierSupplyController extends Controller
{
    public function dashboard()
    {
        $supplierId = Auth::id();
        $user = User::with('supplierProfile')->findOrFail($supplierId);

        $supplies = SupplierSupply::with(['assignedAgent', 'assignedBranch', 'assignedAgentPoint'])
            ->where('supplier_id', $supplierId)
            ->orderByDesc('created_at')
            ->get();

        $payments = SupplierPayment::where('supplier_id', $supplierId)->orderByDesc('payment_date')->get();

        $totalSuppliesCount = $supplies->count();
        $pendingCount = $supplies->where('status', 'pending')->count();
        $approvedAssignedCount = $supplies->whereIn('status', ['approved', 'assigned'])->count();
        $inTransitCount = $supplies->where('status', 'in_transit')->count();
        $receivedCount = $supplies->whereIn('status', ['received', 'partially_received'])->count();
        $approvedCount = $supplies->whereIn('status', ['approved', 'assigned', 'in_transit', 'partially_received', 'received'])->count();
        $rejectedCount = $supplies->whereIn('status', ['rejected', 'cancelled'])->count();

        $totalQuantitySupplied = $supplies->whereNotIn('status', ['draft', 'rejected', 'cancelled'])->sum('quantity');
        $totalSubmittedAmount = $supplies->whereNotIn('status', ['draft', 'rejected', 'cancelled'])->sum('total_amount');
        $totalApprovedAmount = $supplies->whereIn('status', ['approved', 'assigned', 'in_transit', 'partially_received', 'received', 'discrepancy'])->sum(fn ($s) => $s->approved_amount ?? $s->total_amount);
        $totalActualReceivedAmount = $supplies->whereIn('status', ['received', 'partially_received', 'discrepancy'])->sum('actual_received_amount');
        $totalDiscrepancyAmount = $supplies->sum('discrepancy_amount');

        $totalSupplyAmount = $supplies->sum(fn ($s) => $s->payable_amount);
        $totalPaidAmount = $payments->sum('amount');

        $openingBalance = $user->supplierProfile ? $user->supplierProfile->opening_balance : 0;
        $totalDue = ($openingBalance + $totalSupplyAmount) - $totalPaidAmount;

        $recentSupplies = $supplies->take(8);
        $recentPayments = $payments->take(5);

        $notifications = SystemNotification::where(function ($q) use ($supplierId) {
                $q->where('user_id', $supplierId)
                  ->orWhere('target_role', 'supplier');
            })
            ->where('is_read', false)
            ->orderByDesc('id')
            ->take(8)
            ->get();

        return view('supplier.dashboard', compact(
            'user',
            'totalSuppliesCount',
            'approvedCount',
            'pendingCount',
            'approvedAssignedCount',
            'inTransitCount',
            'receivedCount',
            'rejectedCount',
            'totalQuantitySupplied',
            'totalSubmittedAmount',
            'totalApprovedAmount',
            'totalActualReceivedAmount',
            'totalDiscrepancyAmount',
            'totalSupplyAmount',
            'totalPaidAmount',
            'totalDue',
            'recentSupplies',
            'recentPayments',
            'notifications'
        ));
    }

    public function index(Request $request)
    {
        $query = SupplierSupply::with(['assignedAgent', 'assignedBranch', 'assignedAgentPoint', 'items'])
            ->where('supplier_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('supply_code', 'like', "%{$s}%")
                  ->orWhere('invoice_no', 'like', "%{$s}%")
                  ->orWhere('product_name', 'like', "%{$s}%");
            });
        }
        if ($request->filled('from_date')) {
            $query->whereDate('supply_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('supply_date', '<=', $request->to_date);
        }

        $supplies = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('supplier.supplies.index', compact('supplies'));
    }

    public function create()
    {
        $units = ProductUnit::active()->get();
        $categories = ProductCategory::active()->get();
        return view('supplier.supplies.create', compact('units', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string|max:255',
            'supply_date' => 'required|date',
            'invoice_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.category' => 'nullable|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string|max:50',
            'items.*.rate' => 'required|numeric|min:0.01',
        ]);

        $actionStatus = $request->input('action_type') === 'draft' ? 'draft' : 'pending';

        $invoiceFilePath = null;
        $originalName = null;
        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('upload/invoices');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $file->move($uploadDir, $fileName);
            $invoiceFilePath = 'upload/invoices/' . $fileName;
        }

        $supply = DB::transaction(function () use ($request, $actionStatus, $invoiceFilePath, $originalName) {
            $items = $request->input('items');
            $totalQty = 0;
            $grandTotal = 0;

            foreach ($items as $item) {
                $qty = (float) $item['quantity'];
                $rate = (float) $item['rate'];
                $totalQty += $qty;
                $grandTotal += ($qty * $rate);
            }

            $firstItem = $items[0];
            $itemCount = count($items);
            $summaryName = $itemCount > 1
                ? $firstItem['product_name'] . ' (+' . ($itemCount - 1) . 'টি)'
                : $firstItem['product_name'];

            $avgRate = $totalQty > 0 ? round($grandTotal / $totalQty, 2) : (float) $firstItem['rate'];
            $supplyCode = SupplierSupply::generateSupplyCode();

            $supply = SupplierSupply::create([
                'supply_code' => $supplyCode,
                'supplier_id' => Auth::id(),
                'invoice_no' => $request->invoice_no,
                'product_name' => $summaryName,
                'category' => $firstItem['category'] ?? null,
                'quantity' => $totalQty,
                'unit' => $firstItem['unit'],
                'rate' => $avgRate,
                'total_amount' => $grandTotal,
                'supply_date' => $request->supply_date,
                'invoice_file' => $invoiceFilePath,
                'note' => $request->note,
                'status' => $actionStatus,
            ]);

            foreach ($items as $item) {
                $qty = (float) $item['quantity'];
                $rate = (float) $item['rate'];
                SupplierSupplyItem::create([
                    'supplier_supply_id' => $supply->id,
                    'product_name' => $item['product_name'],
                    'category' => $item['category'] ?? null,
                    'quantity' => $qty,
                    'unit' => $item['unit'],
                    'rate' => $rate,
                    'total_amount' => $qty * $rate,
                ]);
            }

            if ($invoiceFilePath) {
                SupplierSupplyAttachment::create([
                    'supplier_supply_id' => $supply->id,
                    'file_type' => 'invoice',
                    'file_path' => $invoiceFilePath,
                    'original_name' => $originalName,
                    'uploaded_by' => Auth::id(),
                ]);
            }

            SupplyWorkflowService::recordStatusChange(
                $supply,
                null,
                $actionStatus,
                $actionStatus === 'draft' ? 'সাপ্লায়ার ড্রাফট হিসেবে সংরক্ষণ করেছেন।' : 'সাপ্লায়ার নতুন পণ্য সরবরাহ জমা দিয়েছেন।'
            );

            SupplyWorkflowService::logAudit(
                $supply->id,
                'supply_submitted',
                null,
                [
                    'supply_code' => $supplyCode,
                    'invoice_no' => $supply->invoice_no,
                    'quantity' => $totalQty,
                    'total_amount' => $grandTotal,
                    'status' => $actionStatus,
                ],
                'Supplier submitted supply request'
            );

            if ($actionStatus === 'pending') {
                SupplyWorkflowService::notifyParty(
                    null,
                    'admin',
                    'নতুন সাপ্লায়ার সরবরাহ অনুরোধ (' . $supplyCode . ')',
                    (Auth::user()->supplierProfile->company_name ?? Auth::user()->name) . " থেকে {$summaryName} ({$totalQty} {$firstItem['unit']}) - ৳" . number_format($grandTotal, 2) . ' এর সরবরাহ জমা হয়েছে।',
                    'info',
                    $supply,
                    route('admin.suppliers.supplies.show', $supply->id)
                );
            }

            return $supply;
        });

        $msg = $actionStatus === 'draft'
            ? "সরবরাহ ড্রাফট ({$supply->supply_code}) সংরক্ষণ করা হয়েছে।"
            : "পণ্য সরবরাহ ({$supply->supply_code}) সফলভাবে জমা হয়েছে! অ্যাডমিন অনুমোদনের জন্য পেন্ডিং রয়েছে।";

        return redirect()->route('supplier.supplies.show', $supply->id)->with('success', $msg);
    }

    public function show($id)
    {
        $supply = SupplierSupply::with([
            'supplier.supplierProfile',
            'items',
            'assignedAgent',
            'assignedAgentPoint',
            'assignedBranch',
            'assignments.agent',
            'assignments.branch',
            'receipts.items',
            'statusHistories.user',
            'attachments',
        ])
        ->where('supplier_id', Auth::id())
        ->findOrFail($id);

        return view('supplier.supplies.show', compact('supply'));
    }

    public function submitDraft($id)
    {
        $supply = SupplierSupply::where('supplier_id', Auth::id())->findOrFail($id);
        if ($supply->status !== 'draft') {
            return back()->with('error', 'শুধুমাত্র ড্রাফট সরবরাহ সাবমিট করা যাবে।');
        }

        $supply->update(['status' => 'pending']);
        SupplyWorkflowService::recordStatusChange($supply, 'draft', 'pending', 'সাপ্লায়ার ড্রাফট থেকে সাবমিট করেছেন।');
        SupplyWorkflowService::logAudit($supply->id, 'draft_submitted', ['status' => 'draft'], ['status' => 'pending']);

        SupplyWorkflowService::notifyParty(
            null,
            'admin',
            'নতুন সাপ্লায়ার সরবরাহ অনুরোধ (' . $supply->supply_code . ')',
            Auth::user()->name . " থেকে {$supply->product_name} এর সরবরাহ জমা হয়েছে।",
            'info',
            $supply,
            route('admin.suppliers.supplies.show', $supply->id)
        );

        return back()->with('success', 'সরবরাহ সফলভাবে অ্যাডমিন অনুমোদনের জন্য পাঠানো হয়েছে!');
    }

    public function dispatchSupply(Request $request, $id)
    {
        $supply = SupplierSupply::where('supplier_id', Auth::id())->findOrFail($id);

        if (!in_array($supply->status, ['assigned', 'approved'], true)) {
            return back()->with('error', 'শুধুমাত্র অনুমোদিত / নির্ধারিত সরবরাহ ডিসপ্যাচ করা যাবে।');
        }

        $oldStatus = $supply->status;
        $supply->update([
            'status' => 'in_transit',
            'dispatched_at' => now(),
        ]);

        SupplyWorkflowService::recordStatusChange(
            $supply,
            $oldStatus,
            'in_transit',
            $request->input('dispatch_note') ?: 'সাপ্লায়ার নির্ধারিত গন্তব্যে মালামাল পাঠিয়েছেন।'
        );

        SupplyWorkflowService::logAudit(
            $supply->id,
            'supply_dispatched',
            ['status' => $oldStatus],
            ['status' => 'in_transit', 'dispatched_at' => now()->toDateTimeString()]
        );

        if ($supply->assigned_agent_id) {
            SupplyWorkflowService::notifyParty(
                $supply->assigned_agent_id,
                'agent',
                'পণ্য পথে আছে (' . $supply->supply_code . ')',
                "সাপ্লায়ার {$supply->product_name} নির্ধারিত পয়েন্টে পাঠিয়েছেন। পৌঁছানোর পর যাচাই করে রিসিভ করুন।",
                'warning',
                $supply,
                route('agent.supplies.show', $supply->id),
                $supply->assigned_branch_id
            );
        }

        SupplyWorkflowService::notifyParty(
            null,
            'admin',
            'পণ্য ডিসপ্যাচ সম্পন্ন (' . $supply->supply_code . ')',
            "সাপ্লায়ার {$supply->product_name} গন্তব্যের উদ্দেশ্যে পাঠিয়েছেন।",
            'info',
            $supply,
            route('admin.suppliers.supplies.show', $supply->id)
        );

        return back()->with('success', 'সরবরাহ "In Transit (পথে আছে)" হিসেবে আপডেট করা হয়েছে!');
    }

    protected function buildStatementData(int $supplierId): array
    {
        $supplier = User::with(['supplierProfile'])->findOrFail($supplierId);

        $openingBalance = $supplier->supplierProfile ? $supplier->supplierProfile->opening_balance : 0;
        $allSupplies = SupplierSupply::where('supplier_id', $supplierId)->orderBy('supply_date', 'asc')->get();

        $accountableSupplies = $allSupplies->filter(fn ($s) => $s->payable_amount > 0);
        $payments = SupplierPayment::where('supplier_id', $supplierId)->orderBy('payment_date', 'asc')->get();

        $totalSubmitted = $allSupplies->whereNotIn('status', ['draft', 'rejected', 'cancelled'])->sum('total_amount');
        $totalApproved = $allSupplies->whereIn('status', ['approved', 'assigned', 'in_transit', 'partially_received', 'received', 'discrepancy'])->sum(fn ($s) => $s->approved_amount ?? $s->total_amount);
        $totalSupply = $accountableSupplies->sum(fn ($s) => $s->payable_amount);
        $totalDiscrepancy = $allSupplies->sum('discrepancy_amount');
        $totalPaid = $payments->sum('amount');
        $currentBalance = ($openingBalance + $totalSupply) - $totalPaid;

        $transactions = collect();

        if ($openingBalance > 0) {
            $transactions->push([
                'date' => $supplier->supplierProfile->opening_date ?? $supplier->created_at->toDateString(),
                'type' => 'Opening Balance',
                'description' => 'প্রারম্ভিক জের (Opening Balance)',
                'debit' => $openingBalance,
                'credit' => 0,
                'ref' => '-',
            ]);
        }

        foreach ($accountableSupplies as $sup) {
            $payable = $sup->payable_amount;
            $qtyUsed = in_array($sup->status, ['received', 'partially_received', 'discrepancy'], true)
                ? $sup->actual_received_quantity
                : ($sup->approved_quantity ?? $sup->quantity);
            $rateUsed = $sup->approved_rate ?? $sup->rate;

            $transactions->push([
                'date' => $sup->supply_date,
                'type' => 'Product Supply (' . $sup->supply_code . ')',
                'description' => $sup->product_name . ' (' . floatval($qtyUsed) . ' ' . $sup->unit . ' @ ৳' . number_format($rateUsed, 2) . ')' . ($sup->discrepancy_quantity > 0 ? ' [ঘাটতি: ' . floatval($sup->discrepancy_quantity) . ']' : ''),
                'debit' => $payable,
                'credit' => 0,
                'ref' => 'Challan: ' . $sup->invoice_no,
            ]);
        }

        foreach ($payments as $pay) {
            $methodText = strtoupper($pay->payment_method) . ($pay->bank_name ? ' - ' . $pay->bank_name : '');
            $transactions->push([
                'date' => $pay->payment_date,
                'type' => 'Payment',
                'description' => 'পরিশোধ (' . $methodText . ')' . ($pay->transaction_id ? ' TxID: ' . $pay->transaction_id : ''),
                'debit' => 0,
                'credit' => $pay->amount,
                'ref' => 'Pay ID: #' . $pay->id,
            ]);
        }

        $sortedTransactions = $transactions->sortBy('date')->values();

        $runningBalance = 0;
        $ledger = $sortedTransactions->map(function ($item) use (&$runningBalance) {
            $runningBalance += ($item['debit'] - $item['credit']);
            $item['balance'] = $runningBalance;
            return $item;
        });

        return compact(
            'supplier',
            'openingBalance',
            'totalSubmitted',
            'totalApproved',
            'totalSupply',
            'totalDiscrepancy',
            'totalPaid',
            'currentBalance',
            'ledger'
        );
    }

    public function statement()
    {
        return view('supplier.statement', $this->buildStatementData(Auth::id()));
    }

    public function printStatement()
    {
        return view('admin.suppliers.print_statement', $this->buildStatementData(Auth::id()));
    }

    public function printInvoice($id)
    {
        $supply = SupplierSupply::with(['supplier.supplierProfile', 'items', 'assignedAgent', 'assignedBranch'])
            ->where('supplier_id', Auth::id())
            ->find($id);

        if (!$supply && (int) $id === (int) Auth::id()) {
            return $this->printStatement();
        }

        if (!$supply) {
            abort(404);
        }

        return view('admin.suppliers.print_invoice', compact('supply'));
    }

    public function markNotificationRead($id)
    {
        SystemNotification::where('id', $id)
            ->where(function ($q) {
                $q->where('user_id', Auth::id())->orWhere('target_role', 'supplier');
            })
            ->update(['is_read' => true, 'read_at' => now()]);
        return back();
    }
}
