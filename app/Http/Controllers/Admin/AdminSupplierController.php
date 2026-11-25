<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\HRM\HrmBranch;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\SupplierPayment;
use App\Models\SupplierProfile;
use App\Models\SupplierSupply;
use App\Models\SupplierSupplyAssignment;
use App\Models\SupplierSupplyAuditLog;
use App\Models\SupplierSupplyItem;
use App\Models\SystemNotification;
use App\Models\User;
use App\Services\SupplyWorkflowService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSupplierController extends Controller
{
    public function index()
    {
        $suppliers = User::where('role', 'supplier')->with('supplierProfile', 'supplies', 'supplierPayments')->get();

        $totalSuppliers = $suppliers->count();
        $totalSupplyAmount = 0;
        $totalPaidAmount = 0;
        $totalOpeningBalance = 0;

        foreach ($suppliers as $supplier) {
            $op = $supplier->supplierProfile ? $supplier->supplierProfile->opening_balance : 0;
            $supAmount = $supplier->supplies->sum(fn ($s) => $s->payable_amount);
            $paidAmount = $supplier->supplierPayments->sum('amount');

            $totalOpeningBalance += $op;
            $totalSupplyAmount += $supAmount;
            $totalPaidAmount += $paidAmount;
        }

        $totalDue = ($totalOpeningBalance + $totalSupplyAmount) - $totalPaidAmount;

        return view('admin.suppliers.index', compact(
            'suppliers',
            'totalSuppliers',
            'totalSupplyAmount',
            'totalPaidAmount',
            'totalDue'
        ));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'district_thana' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $userCount = User::where('role', 'supplier')->count();
            $supplierCode = 'SUP-' . str_pad($userCount + 1, 4, '0', STR_PAD_LEFT);
            $username = 'sup_' . time();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email ?? ($username . '@jamunastock.com'),
                'username' => $username,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'supplier',
            ]);

            SupplierProfile::create([
                'user_id' => $user->id,
                'supplier_code' => $supplierCode,
                'company_name' => $request->company_name,
                'district_thana' => $request->district_thana,
                'address' => $request->address,
                'opening_balance' => $request->opening_balance ?? 0,
                'opening_date' => Carbon::now()->toDateString(),
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('admin.suppliers.index')->with('success', 'সাপ্লায়ার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে!');
    }

    public function show($id, Request $request)
    {
        $supplier = User::where('role', 'supplier')->with(['supplierProfile', 'supplies', 'supplierPayments'])->findOrFail($id);

        $openingBalance = $supplier->supplierProfile ? $supplier->supplierProfile->opening_balance : 0;

        $approvedSuppliesQuery = SupplierSupply::where('supplier_id', $id)
            ->whereIn('status', ['approved', 'assigned', 'in_transit', 'partially_received', 'received', 'discrepancy']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $approvedSuppliesQuery->whereBetween('supply_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('product_name')) {
            $approvedSuppliesQuery->where('product_name', 'like', '%' . $request->product_name . '%');
        }
        $approvedSupplies = $approvedSuppliesQuery->get();

        $paymentsQuery = SupplierPayment::where('supplier_id', $id);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $paymentsQuery->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('payment_method')) {
            $paymentsQuery->where('payment_method', $request->payment_method);
        }
        $payments = $paymentsQuery->get();

        $totalSupply = $approvedSupplies->sum(fn ($s) => $s->payable_amount);
        $totalPaid = $payments->sum('amount');
        $cashPaid = $payments->where('payment_method', 'cash')->sum('amount');
        $bankPaid = $payments->where('payment_method', 'bank')->sum('amount');
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

        foreach ($approvedSupplies as $sup) {
            $payable = $sup->payable_amount;
            if ($payable <= 0) {
                continue;
            }
            $qtyUsed = in_array($sup->status, ['received', 'partially_received', 'discrepancy'], true)
                ? $sup->actual_received_quantity
                : ($sup->approved_quantity ?? $sup->quantity);
            $rateUsed = $sup->approved_rate ?? $sup->rate;

            $transactions->push([
                'date' => $sup->supply_date,
                'type' => 'Product Supply (' . ($sup->supply_code ?: '#' . $sup->id) . ')',
                'description' => $sup->product_name . ' (' . floatval($qtyUsed) . ' ' . $sup->unit . ' @ ৳' . number_format($rateUsed, 2) . ')',
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

        $productSummary = $approvedSupplies->groupBy('product_name')->map(function ($items, $name) {
            $totalQty = $items->sum(fn ($i) => $i->actual_received_quantity > 0 ? $i->actual_received_quantity : $i->quantity);
            $totalAmt = $items->sum(fn ($i) => $i->payable_amount);
            $unit = $items->first()->unit ?? '';
            $avgRate = $totalQty > 0 ? ($totalAmt / $totalQty) : 0;

            return [
                'product_name' => $name,
                'total_quantity' => $totalQty,
                'unit' => $unit,
                'avg_rate' => $avgRate,
                'total_amount' => $totalAmt,
            ];
        });

        return view('admin.suppliers.show', compact(
            'supplier',
            'openingBalance',
            'totalSupply',
            'totalPaid',
            'cashPaid',
            'bankPaid',
            'currentBalance',
            'ledger',
            'productSummary',
            'approvedSupplies'
        ));
    }

    /**
     * Supply Requests List & Dashboard Stats (Section 4 & 14)
     */
    public function pendingSupplies(Request $request)
    {
        $query = SupplierSupply::with([
            'supplier.supplierProfile',
            'assignedAgent',
            'assignedAgentPoint',
            'assignedBranch',
            'items',
        ]);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('invoice_no')) {
            $query->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
        }
        if ($request->filled('supply_code')) {
            $query->where('supply_code', 'like', '%' . $request->supply_code . '%');
        }
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('agent_id')) {
            $query->where('assigned_agent_id', $request->agent_id);
        }
        if ($request->filled('branch_id')) {
            $query->where('assigned_branch_id', $request->branch_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('supply_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('supply_date', '<=', $request->to_date);
        }

        $supplies = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        // Dashboard Statistics (Section 14)
        $allStats = SupplierSupply::selectRaw("
            COUNT(*) as total_count,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status IN ('approved', 'assigned', 'in_transit') THEN 1 ELSE 0 END) as assigned_count,
            SUM(CASE WHEN status IN ('received', 'partially_received') THEN 1 ELSE 0 END) as received_count,
            SUM(CASE WHEN status = 'discrepancy' THEN 1 ELSE 0 END) as discrepancy_count,
            SUM(total_amount) as total_submitted_amount,
            SUM(actual_received_amount) as total_received_amount,
            SUM(discrepancy_amount) as total_discrepancy_amount
        ")->first();

        $suppliers = User::where('role', 'supplier')->with('supplierProfile')->orderBy('name')->get();
        $agents = User::where('role', 'agent')->with('agentPoint')->orderBy('name')->get();
        $branches = HrmBranch::orderBy('name')->get();
        $categories = ProductCategory::active()->get();

        $notifications = SystemNotification::where('target_role', 'admin')
            ->where('is_read', false)
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('admin.suppliers.pending_supplies', compact(
            'supplies',
            'allStats',
            'suppliers',
            'agents',
            'branches',
            'categories',
            'notifications'
        ));
    }

    /**
     * Supply Details, Approval & Agent/Branch Assignment Page (Section 4.2 & 5)
     */
    public function showSupply($id)
    {
        $supply = SupplierSupply::with([
            'supplier.supplierProfile',
            'items',
            'assignedAgent',
            'assignedAgentPoint',
            'assignedBranch',
            'assignments.agent',
            'assignments.branch',
            'assignments.assignedByUser',
            'receipts.items',
            'receipts.agent',
            'statusHistories.user',
            'auditLogs.user',
            'attachments',
        ])->findOrFail($id);

        $agents = User::where('role', 'agent')->with('agentPoint')->orderBy('name')->get();
        $branches = HrmBranch::orderBy('name')->get();
        $agentPoints = AgentPoint::where('status', 'active')->orderBy('name')->get();

        return view('admin.suppliers.supplies.show', compact('supply', 'agents', 'branches', 'agentPoints'));
    }

    /**
     * Approve Supply & Assign Agent + Branch + Destination (Section 5.1 & 5.2)
     * NOTE: Does NOT add to stock directly! Stock updates only when Agent confirms receiving.
     */
    public function approveAndAssign(Request $request, $id)
    {
        $supply = SupplierSupply::with('items')->findOrFail($id);

        if (in_array($supply->status, ['received', 'rejected', 'cancelled'], true)) {
            return back()->with('error', 'এই সরবরাহটি ইতিমধ্যে সম্পন্ন বা বাতিল হয়েছে, তাই অনুমোদন/অ্যাসাইন করা যাবে না।');
        }

        $request->validate([
            'assigned_agent_id' => 'required|exists:users,id',
            'assigned_branch_id' => 'required|exists:hrm_branches,id',
            'destination_type' => 'required|in:branch,warehouse,distribution_center,agent_location,specific_address',
            'destination_location' => 'nullable|string|max:255',
            'delivery_instructions' => 'nullable|string',
            'expected_delivery_date' => 'nullable|date',
            'admin_note' => 'nullable|string',
            'approved_quantity' => 'nullable|numeric|min:0.01',
            'approved_rate' => 'nullable|numeric|min:0.01',
            'items' => 'nullable|array',
            'items.*.approved_quantity' => 'required_with:items|numeric|min:0',
            'items.*.approved_rate' => 'required_with:items|numeric|min:0',
        ]);

        $agent = User::findOrFail($request->assigned_agent_id);
        $branch = HrmBranch::findOrFail($request->assigned_branch_id);
        $agentPointId = $request->input('assigned_agent_point_id') ?: $agent->getAssignedAgentPointId();

        DB::transaction(function () use ($request, $supply, $agent, $branch, $agentPointId) {
            $oldStatus = $supply->status;
            $oldData = [
                'status' => $supply->status,
                'approved_quantity' => $supply->approved_quantity,
                'approved_rate' => $supply->approved_rate,
                'assigned_agent_id' => $supply->assigned_agent_id,
                'assigned_branch_id' => $supply->assigned_branch_id,
            ];

            $approvedQtyTotal = 0;
            $approvedAmountTotal = 0;

            if ($request->has('items') && $supply->items->count() > 0) {
                foreach ($request->input('items') as $itemId => $itemData) {
                    $item = SupplierSupplyItem::where('supplier_supply_id', $supply->id)->find($itemId);
                    if ($item) {
                        $aq = (float) $itemData['approved_quantity'];
                        $ar = (float) $itemData['approved_rate'];
                        $aa = $aq * $ar;
                        $item->update([
                            'approved_quantity' => $aq,
                            'approved_rate' => $ar,
                            'approved_amount' => $aa,
                        ]);
                        $approvedQtyTotal += $aq;
                        $approvedAmountTotal += $aa;
                    }
                }
                $approvedRate = $approvedQtyTotal > 0 ? round($approvedAmountTotal / $approvedQtyTotal, 2) : (float) $supply->rate;
            } else {
                $approvedQtyTotal = (float) ($request->input('approved_quantity') ?: $supply->quantity);
                $approvedRate = (float) ($request->input('approved_rate') ?: $supply->rate);
                $approvedAmountTotal = $approvedQtyTotal * $approvedRate;

                if ($supply->items->count() === 1) {
                    $onlyItem = $supply->items->first();
                    $onlyItem->update([
                        'approved_quantity' => $approvedQtyTotal,
                        'approved_rate' => $approvedRate,
                        'approved_amount' => $approvedAmountTotal,
                    ]);
                }
            }

            // Deactivate any previous active assignment
            SupplierSupplyAssignment::where('supplier_supply_id', $supply->id)
                ->where('status', 'active')
                ->update(['status' => 'changed']);

            $destLocation = $request->destination_location ?: $branch->name . ($branch->address ? ' (' . $branch->address . ')' : '');

            SupplierSupplyAssignment::create([
                'supplier_supply_id' => $supply->id,
                'agent_id' => $agent->id,
                'agent_point_id' => $agentPointId,
                'branch_id' => $branch->id,
                'destination_type' => $request->destination_type,
                'destination_location' => $destLocation,
                'delivery_instructions' => $request->delivery_instructions,
                'expected_delivery_date' => $request->expected_delivery_date,
                'admin_note' => $request->admin_note,
                'status' => 'active',
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
            ]);

            $supply->update([
                'approved_quantity' => $approvedQtyTotal,
                'approved_rate' => $approvedRate,
                'approved_amount' => $approvedAmountTotal,
                'assigned_agent_id' => $agent->id,
                'assigned_agent_point_id' => $agentPointId,
                'assigned_branch_id' => $branch->id,
                'destination_type' => $request->destination_type,
                'destination_location' => $destLocation,
                'delivery_instructions' => $request->delivery_instructions,
                'expected_delivery_date' => $request->expected_delivery_date,
                'admin_note' => $request->admin_note,
                'status' => 'assigned',
                'approved_by' => Auth::id(),
                'approved_at' => $supply->approved_at ?: now(),
            ]);

            SupplyWorkflowService::recordStatusChange(
                $supply,
                $oldStatus,
                'assigned',
                "অ্যাডমিন অনুমোদন করেছেন এবং এজেন্ট ({$agent->name}) ও ব্রাঞ্চ ({$branch->name}) নির্ধারণ করেছেন।"
            );

            SupplyWorkflowService::logAudit(
                $supply->id,
                'supply_approved_and_assigned',
                $oldData,
                [
                    'status' => 'assigned',
                    'approved_quantity' => $approvedQtyTotal,
                    'approved_rate' => $approvedRate,
                    'approved_amount' => $approvedAmountTotal,
                    'assigned_agent_id' => $agent->id,
                    'assigned_branch_id' => $branch->id,
                ],
                $request->admin_note
            );

            // Notify Supplier with direct delivery destination
            SupplyWorkflowService::notifyParty(
                $supply->supplier_id,
                'supplier',
                'সরবরাহ অনুমোদিত ও গন্তব্য নির্ধারিত (' . $supply->supply_code . ')',
                "আপনার {$supply->product_name} ({$approvedQtyTotal} {$supply->unit}) অনুমোদিত হয়েছে। সরাসরি {$branch->name} (এজেন্ট: {$agent->name}) এ পণ্য পাঠান।",
                'success',
                $supply,
                route('supplier.supplies.show', $supply->id)
            );

            // Notify Assigned Agent
            SupplyWorkflowService::notifyParty(
                $agent->id,
                'agent',
                'নতুন ইনকামিং সাপ্লাই অ্যাসাইন (' . $supply->supply_code . ')',
                "অ্যাডমিন আপনার পয়েন্টে ({$branch->name}) {$supply->product_name} ({$approvedQtyTotal} {$supply->unit}) অ্যাসাইন করেছেন।",
                'info',
                $supply,
                route('agent.supplies.show', $supply->id),
                $branch->id
            );
        });

        return back()->with('success', 'সরবরাহ অনুমোদন করা হয়েছে এবং নির্দিষ্ট এজেন্ট ও ব্রাঞ্চে অ্যাসাইন করা হয়েছে!');
    }

    /**
     * Cancel Assignment (Before Receiving)
     */
    public function cancelAssignment(Request $request, $id)
    {
        $request->validate(['cancel_reason' => 'required|string|max:500']);
        $supply = SupplierSupply::findOrFail($id);

        if (in_array($supply->status, ['in_transit', 'received', 'partially_received'], true)) {
            return back()->with('error', 'পণ্য ডিসপ্যাচ বা রিসিভ হয়ে যাওয়ার পর অ্যাসাইনমেন্ট বাতিল করা যাবে না।');
        }

        $oldAgentId = $supply->assigned_agent_id;
        $oldStatus = $supply->status;

        DB::transaction(function () use ($request, $supply, $oldAgentId, $oldStatus) {
            SupplierSupplyAssignment::where('supplier_supply_id', $supply->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'cancel_reason' => $request->cancel_reason,
                    'cancelled_by' => Auth::id(),
                    'cancelled_at' => now(),
                ]);

            $supply->update([
                'assigned_agent_id' => null,
                'assigned_agent_point_id' => null,
                'assigned_branch_id' => null,
                'status' => 'approved',
            ]);

            SupplyWorkflowService::recordStatusChange($supply, $oldStatus, 'approved', 'অ্যাসাইনমেন্ট বাতিল: ' . $request->cancel_reason);
            SupplyWorkflowService::logAudit($supply->id, 'assignment_cancelled', ['assigned_agent_id' => $oldAgentId], ['status' => 'approved'], $request->cancel_reason);

            if ($oldAgentId) {
                SupplyWorkflowService::notifyParty(
                    $oldAgentId,
                    'agent',
                    'অ্যাসাইনমেন্ট বাতিল (' . $supply->supply_code . ')',
                    'কারণ: ' . $request->cancel_reason,
                    'warning',
                    $supply
                );
            }
        });

        return back()->with('success', 'এজেন্ট ও ব্রাঞ্চ অ্যাসাইনমেন্ট বাতিল করা হয়েছে।');
    }

    /**
     * Legacy quick approve fallback (redirects to details page so Admin can assign Agent & Branch)
     */
    public function approveSupply($id)
    {
        return redirect()->route('admin.suppliers.supplies.show', $id)
            ->with('info', 'অনুগ্রহ করে অনুমোদিত পরিমাণ যাচাই করে এজেন্ট ও ব্রাঞ্চ নির্বাচন করুন।');
    }

    /**
     * Reject Supply (GET fallback or POST with reason)
     */
    public function rejectSupply(Request $request, $id)
    {
        $supply = SupplierSupply::findOrFail($id);
        $oldStatus = $supply->status;
        $reason = $request->input('rejection_reason') ?: 'অ্যাডমিন কর্তৃক প্রত্যাখ্যাত';

        $supply->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => Carbon::now(),
            'rejection_reason' => $reason,
        ]);

        SupplyWorkflowService::recordStatusChange($supply, $oldStatus, 'rejected', $reason);
        SupplyWorkflowService::logAudit($supply->id, 'supply_rejected', ['status' => $oldStatus], ['status' => 'rejected', 'reason' => $reason]);

        SupplyWorkflowService::notifyParty(
            $supply->supplier_id,
            'supplier',
            'সরবরাহ প্রত্যাখ্যাত (' . ($supply->supply_code ?: '#' . $supply->id) . ')',
            'কারণ: ' . $reason,
            'danger',
            $supply,
            route('supplier.supplies.show', $supply->id)
        );

        return redirect()->back()->with('error', 'পণ্য সরবরাহ প্রত্যাখ্যান করা হয়েছে!');
    }

    /**
     * Dynamic Units & Categories Management (Section 3.2)
     */
    public function unitsIndex()
    {
        $units = ProductUnit::orderBy('sort_order')->orderBy('name')->get();
        $categories = ProductCategory::orderBy('name')->get();
        return view('admin.suppliers.units', compact('units', 'categories'));
    }

    public function unitsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:80',
            'bn_name' => 'nullable|string|max:100',
            'symbol' => 'nullable|string|max:30',
        ]);

        ProductUnit::create([
            'name' => $request->name,
            'bn_name' => $request->bn_name,
            'symbol' => $request->symbol,
            'sort_order' => (ProductUnit::max('sort_order') ?? 0) + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'নতুন ইউনিট সফলভাবে যোগ করা হয়েছে!');
    }

    public function unitsToggle($id)
    {
        $unit = ProductUnit::findOrFail($id);
        $unit->update(['is_active' => !$unit->is_active]);
        return back()->with('success', 'ইউনিট স্ট্যাটাস আপডেট হয়েছে।');
    }

    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'bn_name' => 'nullable|string|max:150',
        ]);

        ProductCategory::create([
            'name' => $request->name,
            'bn_name' => $request->bn_name,
            'is_active' => true,
        ]);

        return back()->with('success', 'নতুন পণ্য ক্যাটাগরি যোগ করা হয়েছে!');
    }

    public function categoriesToggle($id)
    {
        $cat = ProductCategory::findOrFail($id);
        $cat->update(['is_active' => !$cat->is_active]);
        return back()->with('success', 'ক্যাটাগরি স্ট্যাটাস আপডেট হয়েছে।');
    }

    /**
     * Supply Audit Logs (Section 17)
     */
    public function auditLogs(Request $request)
    {
        $logs = SupplierSupplyAuditLog::with(['supply', 'user'])
            ->orderByDesc('id')
            ->paginate(30);

        return view('admin.suppliers.audit_logs', compact('logs'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank',
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'nullable|required_if:payment_method,bank|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        SupplierPayment::create([
            'supplier_id' => $request->supplier_id,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'transaction_id' => $request->transaction_id,
            'note' => $request->note,
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'পেমেন্ট সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    public function reports(Request $request)
    {
        $suppliers = User::where('role', 'supplier')->with('supplierProfile')->get();
        $agents = User::where('role', 'agent')->get();
        $branches = HrmBranch::all();

        $query = SupplierSupply::with(['supplier.supplierProfile', 'assignedAgent', 'assignedBranch']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('agent_id')) {
            $query->where('assigned_agent_id', $request->agent_id);
        }
        if ($request->filled('branch_id')) {
            $query->where('assigned_branch_id', $request->branch_id);
        }
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('supply_date', [$request->start_date, $request->end_date]);
        }

        $supplies = $query->orderBy('supply_date', 'desc')->get();

        // Branch-wise, Agent-wise, Product-wise summaries (Section 14)
        $branchSummary = $supplies->whereNotNull('assigned_branch_id')->groupBy('assigned_branch_id')->map(function ($group) {
            return [
                'branch_name' => $group->first()->assignedBranch->name ?? 'N/A',
                'count' => $group->count(),
                'received_qty' => $group->sum('actual_received_quantity'),
                'received_amount' => $group->sum('actual_received_amount'),
            ];
        });

        $agentSummary = $supplies->whereNotNull('assigned_agent_id')->groupBy('assigned_agent_id')->map(function ($group) {
            return [
                'agent_name' => $group->first()->assignedAgent->name ?? 'N/A',
                'count' => $group->count(),
                'received_qty' => $group->sum('actual_received_quantity'),
                'received_amount' => $group->sum('actual_received_amount'),
            ];
        });

        return view('admin.suppliers.reports', compact(
            'supplies',
            'suppliers',
            'agents',
            'branches',
            'branchSummary',
            'agentSummary'
        ));
    }

    public function printStatement($id)
    {
        $supplier = User::where('role', 'supplier')->with(['supplierProfile', 'supplies', 'supplierPayments'])->findOrFail($id);

        $openingBalance = $supplier->supplierProfile ? $supplier->supplierProfile->opening_balance : 0;
        $approvedSupplies = SupplierSupply::where('supplier_id', $id)
            ->whereIn('status', ['approved', 'assigned', 'in_transit', 'partially_received', 'received', 'discrepancy'])
            ->orderBy('supply_date', 'asc')
            ->get();
        $payments = SupplierPayment::where('supplier_id', $id)->orderBy('payment_date', 'asc')->get();

        $totalSupply = $approvedSupplies->sum(fn ($s) => $s->payable_amount);
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

        foreach ($approvedSupplies as $sup) {
            $payable = $sup->payable_amount;
            if ($payable <= 0) {
                continue;
            }
            $qtyUsed = in_array($sup->status, ['received', 'partially_received', 'discrepancy'], true)
                ? $sup->actual_received_quantity
                : ($sup->approved_quantity ?? $sup->quantity);
            $rateUsed = $sup->approved_rate ?? $sup->rate;

            $transactions->push([
                'date' => $sup->supply_date,
                'type' => 'Product Supply',
                'description' => $sup->product_name . ' (' . floatval($qtyUsed) . ' ' . $sup->unit . ' @ ৳' . number_format($rateUsed, 2) . ')',
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

        return view('admin.suppliers.print_statement', compact('supplier', 'openingBalance', 'totalSupply', 'totalPaid', 'currentBalance', 'ledger'));
    }

    public function printInvoice($id)
    {
        $supply = SupplierSupply::with(['supplier.supplierProfile', 'items', 'assignedAgent', 'assignedBranch'])->findOrFail($id);
        return view('admin.suppliers.print_invoice', compact('supply'));
    }

    public function notificationsIndex()
    {
        $notifications = SystemNotification::where('target_role', 'admin')
            ->orderByDesc('id')
            ->paginate(25);

        return view('admin.suppliers.notifications', compact('notifications'));
    }

    public function markNotificationRead($id)
    {
        $notif = SystemNotification::where('target_role', 'admin')->findOrFail($id);
        if (!$notif->is_read) {
            $notif->update(['is_read' => true, 'read_at' => now()]);
        }

        if ($notif->action_url) {
            return redirect($notif->action_url);
        }
        return back()->with('success', 'নোটিফিকেশন পঠিত হিসেবে চিহ্নিত করা হয়েছে।');
    }

    public function markAllNotificationsRead()
    {
        SystemNotification::where('target_role', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'সকল নোটিফিকেশন পঠিত হিসেবে চিহ্নিত করা হয়েছে।');
    }

    /**
     * SRD Section 6 & 13: Admin Customer Product Distribution Monitoring
     */
    public function customerDistributions(Request $request)
    {
        $query = \App\Models\CustomerDistribution::with(['items', 'agent', 'agentPoint', 'branch']);

        if ($request->filled('distribution_code')) {
            $query->where('distribution_code', 'like', '%' . $request->distribution_code . '%');
        }
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('customer')) {
            $c = $request->customer;
            $query->where(function ($q) use ($c) {
                $q->where('customer_name', 'like', "%{$c}%")
                  ->orWhere('customer_code', 'like', "%{$c}%")
                  ->orWhere('customer_phone', 'like', "%{$c}%");
            });
        }
        if ($request->filled('product')) {
            $p = $request->product;
            $query->whereHas('items', fn ($q) => $q->where('product_name', 'like', "%{$p}%"));
        }
        if ($request->filled('distribution_type')) {
            $query->where('distribution_type', $request->distribution_type);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('distribution_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('distribution_date', '<=', $request->to_date);
        }

        $distributions = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $totalQty = \App\Models\CustomerDistribution::where('status', 'completed')->sum('total_quantity');
        $freeCount = \App\Models\CustomerDistribution::where('distribution_type', 'free')->count();
        $paidCount = \App\Models\CustomerDistribution::where('distribution_type', '!=', 'free')->count();
        $totalAmount = \App\Models\CustomerDistribution::sum('total_amount');

        $agents = User::where('role', 'agent')->orderBy('name')->get();
        $branches = HrmBranch::orderBy('name')->get();

        return view('admin.suppliers.customer_distributions', compact(
            'distributions',
            'totalQty',
            'freeCount',
            'paidCount',
            'totalAmount',
            'agents',
            'branches'
        ));
    }

    /**
     * SRD Section 9, 10, 11: Accounts -> Supplier Payments & Invoice Allocation
     */
    public function supplierPaymentsIndex(Request $request)
    {
        $suppliers = User::where('role', 'supplier')->with('supplierProfile')->orderBy('name')->get();

        // Sync any received supplies that don't have a payable row yet
        $missingPayables = SupplierSupply::whereIn('status', ['received', 'partially_received', 'discrepancy'])
            ->where('actual_received_amount', '>', 0)
            ->whereDoesntHave('payable')
            ->get();
        foreach ($missingPayables as $ms) {
            \App\Models\SupplierPayable::create([
                'supplier_id' => $ms->supplier_id,
                'supplier_supply_id' => $ms->id,
                'supply_code' => $ms->supply_code,
                'invoice_no' => $ms->invoice_no,
                'payable_amount' => $ms->actual_received_amount,
                'paid_amount' => 0,
                'due_amount' => $ms->actual_received_amount,
                'payment_status' => 'unpaid',
                'payable_date' => $ms->received_at?->toDateString() ?? $ms->supply_date,
            ]);
        }

        $payablesQuery = \App\Models\SupplierPayable::with(['supplier.supplierProfile', 'supply']);
        if ($request->filled('supplier_id')) {
            $payablesQuery->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('payment_status')) {
            $payablesQuery->where('payment_status', $request->payment_status);
        }
        $payables = $payablesQuery->orderByDesc('id')->get();

        $payments = SupplierPayment::with(['supplier.supplierProfile', 'creator', 'allocations.payable'])
            ->when($request->filled('supplier_id'), fn ($q) => $q->where('supplier_id', $request->supplier_id))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $totalPayable = $payables->sum('payable_amount');
        $totalPaid = $payables->sum('paid_amount');
        $totalDue = $payables->sum('due_amount');

        return view('admin.suppliers.payments', compact(
            'suppliers',
            'payables',
            'payments',
            'totalPayable',
            'totalPaid',
            'totalDue'
        ));
    }

    public function storeSupplierPaymentAllocated(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'supplier_payable_id' => 'nullable|exists:supplier_payables,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,bkash,nagad,rocket,other',
            'amount' => 'required|numeric|min:0.01',
            'bank_name' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'reference_no' => 'nullable|string|max:120',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'note' => 'nullable|string',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = 'pay_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $dir = public_path('upload/payment_receipts');
            if (!file_exists($dir)) {
                mkdir($dir, 0775, true);
            }
            $file->move($dir, $fileName);
            $attachmentPath = 'upload/payment_receipts/' . $fileName;
        }

        DB::transaction(function () use ($request, $attachmentPath) {
            $paymentNo = SupplierPayment::generatePaymentNo();

            $payment = SupplierPayment::create([
                'payment_no' => $paymentNo,
                'supplier_id' => $request->supplier_id,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'transaction_id' => $request->transaction_id,
                'reference_no' => $request->reference_no,
                'attachment_path' => $attachmentPath,
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);

            $remainingToAllocate = (float) $request->amount;

            // If specific invoice/payable selected, allocate to it first
            if ($request->filled('supplier_payable_id')) {
                $target = \App\Models\SupplierPayable::where('supplier_id', $request->supplier_id)
                    ->find($request->supplier_payable_id);
                if ($target && $target->due_amount > 0) {
                    $alloc = min($remainingToAllocate, (float) $target->due_amount);
                    if ($alloc > 0) {
                        \App\Models\SupplierPaymentAllocation::create([
                            'supplier_payment_id' => $payment->id,
                            'supplier_payable_id' => $target->id,
                            'supplier_supply_id' => $target->supplier_supply_id,
                            'allocated_amount' => $alloc,
                        ]);
                        $target->recalculateStatus();
                        $remainingToAllocate -= $alloc;
                    }
                }
            }

            // FIFO allocate any remaining payment amount across unpaid/partial invoices
            if ($remainingToAllocate > 0.01) {
                $openPayables = \App\Models\SupplierPayable::where('supplier_id', $request->supplier_id)
                    ->where('due_amount', '>', 0)
                    ->orderBy('id', 'asc')
                    ->get();
                foreach ($openPayables as $op) {
                    if ($remainingToAllocate <= 0.01) break;
                    $alloc = min($remainingToAllocate, (float) $op->due_amount);
                    if ($alloc > 0) {
                        \App\Models\SupplierPaymentAllocation::create([
                            'supplier_payment_id' => $payment->id,
                            'supplier_payable_id' => $op->id,
                            'supplier_supply_id' => $op->supplier_supply_id,
                            'allocated_amount' => $alloc,
                        ]);
                        $op->recalculateStatus();
                        $remainingToAllocate -= $alloc;
                    }
                }
            }

            SystemNotification::notifyUser(
                (int) $request->supplier_id,
                'supplier',
                'পেমেন্ট প্রাপ্তি (' . $paymentNo . ')',
                'অ্যাডমিন আপনার অ্যাকাউন্টে ৳' . number_format($request->amount, 2) . ' (' . strtoupper($request->payment_method) . ') পরিশোধ করেছেন।',
                'success',
                'SupplierPayment',
                $payment->id,
                route('supplier.statement')
            );
        });

        return back()->with('success', 'সাপ্লায়ার পেমেন্ট সফলভাবে রেকর্ড ও ইনভয়েসে সমন্বয় করা হয়েছে!');
    }

    /**
     * SRD Section 13 & 15: Master Inventory, Stock Movements & Accounting Dashboard
     */
    public function masterAccountingDashboard(Request $request)
    {
        $totalSupplierPayable = \App\Models\SupplierPayable::sum('payable_amount');
        $totalSupplierPaid = \App\Models\SupplierPayable::sum('paid_amount');
        $totalSupplierDue = \App\Models\SupplierPayable::sum('due_amount');
        $totalDistributionQty = \App\Models\CustomerDistribution::where('status', 'completed')->sum('total_quantity');
        $totalDistributionAmount = \App\Models\CustomerDistribution::where('status', 'completed')->sum('total_amount');

        $movements = \App\Models\StockMovement::with(['agent', 'agentPoint'])
            ->when($request->filled('movement_type'), fn ($q) => $q->where('movement_type', $request->movement_type))
            ->when($request->filled('product'), fn ($q) => $q->where('product_name', 'like', '%' . $request->product . '%'))
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $agentStocks = \App\Models\AgentStock::with('agent')->get();

        return view('admin.suppliers.master_dashboard', compact(
            'totalSupplierPayable',
            'totalSupplierPaid',
            'totalSupplierDue',
            'totalDistributionQty',
            'totalDistributionAmount',
            'movements',
            'agentStocks'
        ));
    }
}
