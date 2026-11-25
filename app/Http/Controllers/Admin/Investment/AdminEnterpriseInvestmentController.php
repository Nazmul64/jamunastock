<?php

namespace App\Http\Controllers\Admin\Investment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestmentSector;
use App\Models\Investment\InvestmentPlan;
use App\Models\Investment\InvestmentCustomer;
use App\Models\Investment\EnterpriseInvestment;
use App\Models\Investment\InvestmentProfitSchedule;
use App\Models\Investment\InvestmentReceipt;
use App\Models\Investment\InvestmentReceiptLog;
use App\Models\Investment\InvestmentSettlement;
use App\Models\Investment\InvestmentLedger;
use App\Models\Investment\InvestmentInquiry;
use App\Services\InvestmentCalculationService;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminEnterpriseInvestmentController extends Controller
{
    // ==========================================
    // 1. CENTRAL MULTI-SECTOR DASHBOARD (SECTION 10)
    // ==========================================
    public function centralDashboard()
    {
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();

        $totalSectors = $sectors->count();
        $totalInvestors = InvestmentCustomer::count();
        $totalInvestment = EnterpriseInvestment::where('status', '!=', 'cancelled')->sum('amount');
        $activeInvestment = EnterpriseInvestment::where('status', 'active')->sum('amount');
        $totalProfitPaid = EnterpriseInvestment::sum('paid_profit_total');
        $totalProfitExpected = EnterpriseInvestment::where('status', 'active')->sum('expected_profit_amount');
        $totalProfitDue = max(0, $totalProfitExpected - $totalProfitPaid);
        $totalPrincipalRefunded = EnterpriseInvestment::sum('principal_refunded_total');
        $totalPrincipalDue = max(0, $totalInvestment - $totalPrincipalRefunded);

        // Sector-wise summary metrics
        $sectorSummaries = [];
        foreach ($sectors as $sec) {
            $invCount = EnterpriseInvestment::where('sector_id', $sec->id)->where('status', 'active')->count();
            $invTotal = EnterpriseInvestment::where('sector_id', $sec->id)->where('status', 'active')->sum('amount');
            $secProfitPaid = EnterpriseInvestment::where('sector_id', $sec->id)->sum('paid_profit_total');
            $secProfitExpected = EnterpriseInvestment::where('sector_id', $sec->id)->where('status', 'active')->sum('expected_profit_amount');
            $secProfitDue = max(0, $secProfitExpected - $secProfitPaid);
            $secPrincipalRefunded = EnterpriseInvestment::where('sector_id', $sec->id)->sum('principal_refunded_total');
            $secPrincipalDue = max(0, $invTotal - $secPrincipalRefunded);

            // Latest ledger balance
            $lastLedger = InvestmentLedger::where('sector_id', $sec->id)->orderByDesc('id')->first();
            $balance = $lastLedger ? $lastLedger->balance : $invTotal;

            $sectorSummaries[] = [
                'sector' => $sec,
                'investors_count' => $invCount,
                'total_investment' => $invTotal,
                'profit_paid' => $secProfitPaid,
                'profit_due' => $secProfitDue,
                'principal_due' => $secPrincipalDue,
                'ledger_balance' => $balance,
            ];
        }

        $recentInvestments = EnterpriseInvestment::with(['customer', 'sector'])
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $recentInquiries = InvestmentInquiry::with('sector')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('admin.investments.central_dashboard', compact(
            'totalSectors',
            'totalInvestors',
            'totalInvestment',
            'activeInvestment',
            'totalProfitPaid',
            'totalProfitDue',
            'totalPrincipalDue',
            'sectorSummaries',
            'recentInvestments',
            'recentInquiries'
        ));
    }

    // ==========================================
    // 2. SECTOR-SPECIFIC ISOLATED DASHBOARD (SECTION 2)
    // ==========================================
    public function sectorDashboard($slug)
    {
        $sector = InvestmentSector::where('slug', $slug)->firstOrFail();

        $investmentsQuery = EnterpriseInvestment::where('sector_id', $sector->id);

        $totalInvestors = InvestmentCustomer::whereHas('investments', function ($q) use ($sector) {
            $q->where('sector_id', $sector->id);
        })->count();

        $totalInvestment = (clone $investmentsQuery)->where('status', '!=', 'cancelled')->sum('amount');
        $activeInvestment = (clone $investmentsQuery)->where('status', 'active')->sum('amount');
        $completedInvestment = (clone $investmentsQuery)->whereIn('status', ['matured', 'settled', 'early_settled'])->count();

        $totalProfitPayable = (clone $investmentsQuery)->where('status', 'active')->sum('expected_profit_amount');
        $totalProfitPaid = (clone $investmentsQuery)->sum('paid_profit_total');
        $totalProfitDue = max(0, $totalProfitPayable - $totalProfitPaid);

        $totalPrincipal = $totalInvestment;
        $principalReturned = (clone $investmentsQuery)->sum('principal_refunded_total');
        $totalOutstanding = max(0, $totalPrincipal - $principalReturned);

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthlyInvestment = (clone $investmentsQuery)
            ->whereMonth('start_date', $currentMonth)
            ->whereYear('start_date', $currentYear)
            ->sum('amount');

        $monthlyProfitPayment = InvestmentProfitSchedule::whereHas('investment', function ($q) use ($sector) {
            $q->where('sector_id', $sector->id);
        })
            ->where('status', 'paid')
            ->whereMonth('paid_at', $currentMonth)
            ->whereYear('paid_at', $currentYear)
            ->sum('paid_amount');

        $recentInvestments = (clone $investmentsQuery)->with('customer')->orderByDesc('id')->take(6)->get();

        $pendingInquiriesCount = InvestmentInquiry::where('sector_id', $sector->id)
            ->whereIn('status', ['new', 'pending_contact', 'contacted', 'meeting_scheduled'])
            ->count();

        return view('admin.investments.sector_dashboard', compact(
            'sector',
            'totalInvestors',
            'totalInvestment',
            'activeInvestment',
            'completedInvestment',
            'totalProfitPayable',
            'totalProfitPaid',
            'totalProfitDue',
            'totalPrincipal',
            'principalReturned',
            'totalOutstanding',
            'monthlyInvestment',
            'monthlyProfitPayment',
            'recentInvestments',
            'pendingInquiriesCount'
        ));
    }

    // ==========================================
    // 3. SECTOR INVESTORS / CUSTOMER LIST (SECTION 3)
    // ==========================================
    public function sectorCustomers($slug)
    {
        $sector = InvestmentSector::where('slug', $slug)->firstOrFail();

        $customers = InvestmentCustomer::whereHas('investments', function ($q) use ($sector) {
            $q->where('sector_id', $sector->id);
        })
            ->with(['investments' => function ($q) use ($sector) {
                $q->where('sector_id', $sector->id);
            }])
            ->paginate(15);

        return view('admin.investments.customers_index', compact('sector', 'customers'));
    }

    // Customer Overall Profile
    public function customerProfile($id)
    {
        $customer = InvestmentCustomer::with(['investments.sector', 'investments.profitSchedules', 'receipts', 'ledgers.sector'])
            ->findOrFail($id);

        return view('admin.investments.customer_profile', compact('customer'));
    }

    // ==========================================
    // 4. INVESTMENTS LIST & CREATE (SECTION 4 & 5)
    // ==========================================
    public function investmentsIndex(Request $request, $slug = null)
    {
        $query = EnterpriseInvestment::with(['customer', 'sector', 'plan']);

        $selectedSector = null;
        if ($slug) {
            $selectedSector = InvestmentSector::where('slug', $slug)->firstOrFail();
            $query->where('sector_id', $selectedSector->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('investment_code', 'LIKE', "%{$search}%")
                    ->orWhere('agreement_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($sq) use ($search) {
                        $sq->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%")
                            ->orWhere('customer_code', 'LIKE', "%{$search}%");
                    });
            });
        }

        $investments = $query->orderByDesc('id')->paginate(15);
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();

        return view('admin.investments.investments_index', compact('investments', 'sectors', 'selectedSector'));
    }

    public function createInvestment(Request $request, $slug = null)
    {
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();
        $selectedSector = $slug ? InvestmentSector::where('slug', $slug)->first() : null;

        $customers = InvestmentCustomer::orderBy('name')->get();
        $inquiry = null;

        if ($request->filled('inquiry_id')) {
            $inquiry = InvestmentInquiry::with(['sector', 'plan'])->find($request->inquiry_id);
            if ($inquiry && !$selectedSector) {
                $selectedSector = $inquiry->sector;
            }
        }

        return view('admin.investments.create_investment', compact('sectors', 'selectedSector', 'customers', 'inquiry'));
    }

    public function storeInvestment(Request $request)
    {
        $validated = $request->validate([
            'sector_id' => 'required|exists:investment_sectors,id',
            'customer_type' => 'required|in:existing,new',
            'customer_id' => 'nullable|required_if:customer_type,existing|exists:investment_customers,id',
            // New customer fields
            'customer_name' => 'nullable|required_if:customer_type,new|string|max:255',
            'customer_phone' => 'nullable|required_if:customer_type,new|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_nid' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:100',
            'nominee_name' => 'nullable|string|max:255',
            'nominee_phone' => 'nullable|string|max:20',
            'nominee_relation' => 'nullable|string|max:100',
            'nominee_nid' => 'nullable|string|max:50',
            // Investment fields
            'plan_id' => 'nullable|exists:investment_plans,id',
            'amount' => 'required|numeric|min:1000',
            'duration_years' => 'required|integer|min:1|max:20',
            'start_date' => 'required|date',
            'profit_type' => 'required|in:fixed,percentage,contractual',
            'profit_rate' => 'required|numeric|min:0',
            'profit_frequency' => 'required|in:monthly,quarterly,half_yearly,yearly,at_maturity',
            'payment_method' => 'required|string|max:50',
            'inquiry_id' => 'nullable|exists:investment_inquiries,id',
            'agreement_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'terms_conditions' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $sector = InvestmentSector::findOrFail($validated['sector_id']);

            // 1. Resolve or Create Customer Profile
            if ($validated['customer_type'] === 'new') {
                $customer = InvestmentCustomer::create([
                    'customer_code' => InvestmentCalculationService::generateCustomerCode(),
                    'name' => $validated['customer_name'],
                    'phone' => $validated['customer_phone'],
                    'email' => $validated['customer_email'] ?? null,
                    'nid_number' => $validated['customer_nid'] ?? null,
                    'present_address' => $validated['customer_address'] ?? null,
                    'father_name' => $validated['father_name'] ?? null,
                    'mother_name' => $validated['mother_name'] ?? null,
                    'occupation' => $validated['occupation'] ?? null,
                    'nominee_name' => $validated['nominee_name'] ?? null,
                    'nominee_phone' => $validated['nominee_phone'] ?? null,
                    'nominee_relation' => $validated['nominee_relation'] ?? null,
                    'nominee_nid' => $validated['nominee_nid'] ?? null,
                    'status' => 'active',
                ]);
            } else {
                $customer = InvestmentCustomer::findOrFail($validated['customer_id']);
            }

            // 2. Calculate Dates & Returns
            $startDate = Carbon::parse($validated['start_date']);
            $durationYears = (int) $validated['duration_years'];
            $maturityDate = (clone $startDate)->addYears($durationYears);

            $amount = (float) $validated['amount'];
            $rate = (float) $validated['profit_rate'];

            // Expected profit calculation
            $expectedProfit = ($amount * ($rate / 100)) * $durationYears;
            $totalExpectedReturn = $amount + $expectedProfit;

            // Handle Agreement File Upload
            $agreementPath = null;
            if ($request->hasFile('agreement_file')) {
                $dir = public_path('uploads/agreements');
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $fileName = 'AGR_' . time() . '_' . uniqid() . '.' . $request->file('agreement_file')->getClientOriginalExtension();
                $request->file('agreement_file')->move($dir, $fileName);
                $agreementPath = 'uploads/agreements/' . $fileName;
            }

            // 3. Create Enterprise Investment Record
            $investmentCode = InvestmentCalculationService::generateInvestmentCode($sector);
            $agreementNumber = InvestmentCalculationService::generateAgreementNumber($sector);

            $investment = EnterpriseInvestment::create([
                'investment_code' => $investmentCode,
                'agreement_number' => $agreementNumber,
                'customer_id' => $customer->id,
                'sector_id' => $sector->id,
                'plan_id' => $validated['plan_id'] ?? null,
                'inquiry_id' => $validated['inquiry_id'] ?? null,
                'amount' => $amount,
                'duration_years' => $durationYears,
                'start_date' => $startDate->toDateString(),
                'maturity_date' => $maturityDate->toDateString(),
                'profit_type' => $validated['profit_type'],
                'profit_rate' => $rate,
                'profit_frequency' => $validated['profit_frequency'],
                'expected_profit_amount' => $expectedProfit,
                'total_expected_return' => $totalExpectedReturn,
                'principal_return_type' => 'at_maturity',
                'agreement_file' => $agreementPath,
                'terms_conditions' => $validated['terms_conditions'] ?? $sector->terms,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'received',
                'status' => 'active',
                'created_by' => auth()->id(),
                'approved_by' => auth()->id(),
            ]);

            // 4. Generate Profit Installments Schedule (Section 7)
            InvestmentCalculationService::createProfitSchedules($investment);

            // 5. Generate Official Investment Receipt (Section 8 & 17)
            $receiptNumber = InvestmentCalculationService::generateReceiptNumber($sector);
            $amountInWords = InvestmentCalculationService::amountToWords($amount);

            $receipt = InvestmentReceipt::create([
                'receipt_number' => $receiptNumber,
                'receipt_type' => 'investment_deposit',
                'investment_id' => $investment->id,
                'customer_id' => $customer->id,
                'sector_id' => $sector->id,
                'amount' => $amount,
                'amount_in_words' => $amountInWords,
                'payment_method' => $validated['payment_method'],
                'received_by' => auth()->user() ? auth()->user()->name : 'Accounts Desk',
                'notes' => 'Official initial investment receipt for ' . $sector->name_bn,
            ]);

            // Log receipt creation
            InvestmentReceiptLog::create([
                'receipt_id' => $receipt->id,
                'action' => 'created',
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'details' => "Receipt {$receiptNumber} created for investment {$investmentCode}",
            ]);

            // 6. Record in Double-Entry Sector Financial Ledger (Section 9)
            InvestmentCalculationService::recordLedger(
                $sector->id,
                $customer->id,
                $investment->id,
                $receipt->id,
                'investment_received',
                $amount, // Credit inflow
                0, // Debit
                "Investment Received - {$customer->name} ({$investmentCode})",
                $receiptNumber,
                $validated['payment_method'],
                auth()->id()
            );

            // 7. If linked to an inquiry, mark inquiry as Converted (Section 15 & 16)
            if (!empty($validated['inquiry_id'])) {
                $inquiry = InvestmentInquiry::find($validated['inquiry_id']);
                if ($inquiry) {
                    $inquiry->update([
                        'status' => 'converted',
                        'customer_id' => $customer->id,
                        'converted_investment_id' => $investment->id,
                        'converted_at' => now(),
                        'notes' => ($inquiry->notes ? $inquiry->notes . "\n" : '') . "Converted to Investment: {$investmentCode} on " . now()->toDateTimeString(),
                    ]);
                }
            }

            AuditLog::log('enterprise_investment_created', 'EnterpriseInvestment', $investment->id, "Investment {$investmentCode} of BDT {$amount} created for customer {$customer->name}");

            return redirect()->route('admin.investments.show', $investment->id)
                ->with('success', "বিনিয়োগ {$investmentCode} সফলভাবে নিবন্ধিত হয়েছে এবং অফিশিয়াল মানি রিসিপ্ট {$receiptNumber} প্রস্তুত হয়েছে।");
        });
    }

    public function showInvestment($id)
    {
        $investment = EnterpriseInvestment::with(['customer', 'sector', 'plan', 'profitSchedules', 'receipts', 'settlements', 'ledgers'])
            ->findOrFail($id);

        return view('admin.investments.show_investment', compact('investment'));
    }

    // ==========================================
    // 5. PROFIT MANAGEMENT & PAYOUT (SECTION 7)
    // ==========================================
    public function payProfitInstallment(Request $request, $scheduleId)
    {
        $schedule = InvestmentProfitSchedule::with(['investment.sector', 'investment.customer'])->findOrFail($scheduleId);

        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'transaction_ref' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($schedule, $validated) {
            $paidAmount = (float) $validated['paid_amount'];
            $investment = $schedule->investment;
            $sector = $investment->sector;
            $customer = $investment->customer;

            $schedule->update([
                'paid_amount' => $schedule->paid_amount + $paidAmount,
                'due_amount' => max(0, $schedule->expected_profit - ($schedule->paid_amount + $paidAmount)),
                'status' => 'paid',
                'paid_at' => now()->toDateString(),
                'payment_method' => $validated['payment_method'],
                'transaction_ref' => $validated['transaction_ref'] ?? ('TXN-' . uniqid()),
                'remarks' => $validated['remarks'] ?? '',
                'approved_by' => auth()->id(),
            ]);

            // Update parent investment's total paid profit
            $investment->increment('paid_profit_total', $paidAmount);

            // Generate Profit Payout Receipt
            $receiptNumber = InvestmentCalculationService::generateReceiptNumber($sector);
            $amountInWords = InvestmentCalculationService::amountToWords($paidAmount);

            $receipt = InvestmentReceipt::create([
                'receipt_number' => $receiptNumber,
                'receipt_type' => 'profit_payout',
                'investment_id' => $investment->id,
                'customer_id' => $customer->id,
                'sector_id' => $sector->id,
                'profit_schedule_id' => $schedule->id,
                'amount' => $paidAmount,
                'amount_in_words' => $amountInWords,
                'payment_method' => $validated['payment_method'],
                'transaction_ref' => $validated['transaction_ref'] ?? null,
                'received_by' => auth()->user() ? auth()->user()->name : 'Accounts Officer',
                'notes' => "Profit payout for {$schedule->period_label} ({$investment->investment_code})",
            ]);

            // Log receipt
            InvestmentReceiptLog::create([
                'receipt_id' => $receipt->id,
                'action' => 'created',
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'details' => "Profit payout receipt {$receiptNumber} issued",
            ]);

            // Record in Double-Entry Sector Financial Ledger
            InvestmentCalculationService::recordLedger(
                $sector->id,
                $customer->id,
                $investment->id,
                $receipt->id,
                'profit_paid',
                0, // Credit
                $paidAmount, // Debit outflow
                "Profit Paid - {$schedule->period_label} to {$customer->name} ({$investment->investment_code})",
                $receiptNumber,
                $validated['payment_method'],
                auth()->id()
            );

            return back()->with('success', "মুনাফা ৳" . number_format($paidAmount, 2) . " সফলভাবে পরিশোধ হয়েছে। মানি রসিদ নম্বর: {$receiptNumber}");
        });
    }

    // ==========================================
    // 6. EARLY SETTLEMENT CALCULATOR & EXECUTION (SECTION 6)
    // ==========================================
    public function settlementPreview(Request $request, $id)
    {
        $investment = EnterpriseInvestment::with(['customer', 'sector'])->findOrFail($id);
        $settlementDate = $request->filled('settlement_date') ? Carbon::parse($request->settlement_date) : Carbon::now();
        $policy = $request->input('policy', 'no_profit');

        $calc = InvestmentCalculationService::calculateEarlySettlement($investment, $settlementDate, $policy);

        return response()->json($calc);
    }

    public function processSettlement(Request $request, $id)
    {
        $investment = EnterpriseInvestment::with(['customer', 'sector'])->findOrFail($id);

        $validated = $request->validate([
            'settlement_type' => 'required|in:maturity,early_withdrawal,cancellation',
            'settlement_date' => 'required|date',
            'penalty_or_deduction' => 'required|numeric|min:0',
            'net_refund_amount' => 'required|numeric|min:0',
            'deduction_reason' => 'nullable|string',
            'payment_method' => 'required|string',
        ]);

        return DB::transaction(function () use ($investment, $validated) {
            $sector = $investment->sector;
            $customer = $investment->customer;

            $settlement = InvestmentSettlement::create([
                'investment_id' => $investment->id,
                'customer_id' => $customer->id,
                'sector_id' => $sector->id,
                'settlement_type' => $validated['settlement_type'],
                'settlement_date' => $validated['settlement_date'],
                'original_amount' => $investment->amount,
                'total_profit_paid' => $investment->paid_profit_total,
                'penalty_or_deduction' => $validated['penalty_or_deduction'],
                'net_refund_amount' => $validated['net_refund_amount'],
                'deduction_reason' => $validated['deduction_reason'] ?? '',
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Update investment status
            $newStatus = $validated['settlement_type'] === 'early_withdrawal' ? 'early_settled' : ($validated['settlement_type'] === 'cancellation' ? 'cancelled' : 'settled');
            $investment->update([
                'status' => $newStatus,
                'principal_refunded_total' => $validated['net_refund_amount'],
            ]);

            // Generate Principal Refund / Settlement Receipt
            $receiptNumber = InvestmentCalculationService::generateReceiptNumber($sector);
            $amountInWords = InvestmentCalculationService::amountToWords((float) $validated['net_refund_amount']);

            $receipt = InvestmentReceipt::create([
                'receipt_number' => $receiptNumber,
                'receipt_type' => 'settlement',
                'investment_id' => $investment->id,
                'customer_id' => $customer->id,
                'sector_id' => $sector->id,
                'amount' => $validated['net_refund_amount'],
                'amount_in_words' => $amountInWords,
                'payment_method' => $validated['payment_method'],
                'received_by' => auth()->user() ? auth()->user()->name : 'Accounts Authority',
                'notes' => "Final settlement refund for {$investment->investment_code}",
            ]);

            $settlement->update(['receipt_id' => $receipt->id]);

            // Record in Sector Ledger
            InvestmentCalculationService::recordLedger(
                $sector->id,
                $customer->id,
                $investment->id,
                $receipt->id,
                'principal_refunded',
                0, // Credit
                (float) $validated['net_refund_amount'], // Debit outflow
                "Principal Settlement Refund - {$customer->name} ({$investment->investment_code})",
                $receiptNumber,
                $validated['payment_method'],
                auth()->id()
            );

            return back()->with('success', "বিনিয়োগ সেটেলমেন্ট সম্পন্ন হয়েছে। মূলধন ফেরত রসিদ {$receiptNumber} প্রস্তুত।");
        });
    }

    // ==========================================
    // 7. OFFICIAL INVESTMENT RECEIPTS (SECTION 8 & 17)
    // ==========================================
    public function receiptsIndex(Request $request)
    {
        $query = InvestmentReceipt::with(['customer', 'sector', 'investment']);

        if ($request->filled('sector_id')) {
            $query->where('sector_id', $request->sector_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('receipt_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($sq) use ($search) {
                        $sq->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('investment', function ($sq) use ($search) {
                        $sq->where('investment_code', 'LIKE', "%{$search}%");
                    });
            });
        }

        $receipts = $query->orderByDesc('id')->paginate(20);
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();

        return view('admin.investments.receipts_index', compact('receipts', 'sectors'));
    }

    public function viewReceipt($id)
    {
        $receipt = InvestmentReceipt::with(['customer', 'sector', 'investment', 'logs.user'])->findOrFail($id);

        // Update print count & log
        $receipt->increment('print_count');
        $receipt->update(['last_printed_at' => now()]);

        InvestmentReceiptLog::create([
            'receipt_id' => $receipt->id,
            'action' => $receipt->print_count > 1 ? 'reprinted' : 'printed',
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'details' => "Viewed/Printed official receipt (Copy #" . $receipt->print_count . ")",
        ]);

        return view('admin.investments.receipt_view', compact('receipt'));
    }

    public function voidReceipt(Request $request, $id)
    {
        $receipt = InvestmentReceipt::findOrFail($id);

        $validated = $request->validate([
            'void_reason' => 'required|string|min:5',
        ]);

        $receipt->update([
            'is_void' => true,
            'void_reason' => $validated['void_reason'],
            'voided_by' => auth()->id(),
            'voided_at' => now(),
        ]);

        InvestmentReceiptLog::create([
            'receipt_id' => $receipt->id,
            'action' => 'voided',
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'details' => "Voided receipt: {$validated['void_reason']}",
        ]);

        AuditLog::log('receipt_voided', 'InvestmentReceipt', $receipt->id, "Receipt {$receipt->receipt_number} voided by admin");

        return back()->with('success', "মানি রসিদ {$receipt->receipt_number} সফলভাবে বাতিল (Void) করা হয়েছে। অডিট ট্রেইলে কারণ সংরক্ষিত হয়েছে।");
    }

    // ==========================================
    // 8. FINANCIAL LEDGER (SECTION 9)
    // ==========================================
    public function ledgerIndex(Request $request, $slug = null)
    {
        $query = InvestmentLedger::with(['sector', 'customer', 'investment', 'entryUser']);

        $selectedSector = null;
        if ($slug) {
            $selectedSector = InvestmentSector::where('slug', $slug)->firstOrFail();
            $query->where('sector_id', $selectedSector->id);
        } elseif ($request->filled('sector_id')) {
            $query->where('sector_id', $request->sector_id);
            $selectedSector = InvestmentSector::find($request->sector_id);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        $ledgers = $query->orderByDesc('id')->paginate(25);
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();
        $customers = InvestmentCustomer::orderBy('name')->get();

        $totalCredit = (clone $query)->sum('credit');
        $totalDebit = (clone $query)->sum('debit');
        $netBalance = $totalCredit - $totalDebit;

        return view('admin.investments.ledger_index', compact('ledgers', 'sectors', 'customers', 'selectedSector', 'totalCredit', 'totalDebit', 'netBalance'));
    }
}
