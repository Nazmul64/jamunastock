<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountingVoucher;
use App\Models\BillPaymentAllocation;
use App\Models\ChartOfAccount;
use App\Models\FinancialBill;
use App\Models\FinancialBillItem;
use App\Models\GeneralLedgerEntry;
use App\Models\Admin\SiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminBillsVouchersController extends Controller
{
    /**
     * Dashboard: Financial Bills & Vouchers Overview
     */
    public function dashboard()
    {
        // Bills Statistics
        $totalBillsCount = FinancialBill::count();
        $totalBillsAmount = FinancialBill::sum('grand_total');
        $totalPaidBillsAmount = FinancialBill::sum('paid_amount');
        $totalOutstandingBillsAmount = FinancialBill::sum('outstanding_amount');
        $unpaidBillsCount = FinancialBill::whereIn('payment_status', ['unpaid', 'partially_paid', 'overdue'])->count();
        $overdueBillsCount = FinancialBill::where('payment_status', 'overdue')
            ->orWhere(function ($q) {
                $q->whereIn('payment_status', ['unpaid', 'partially_paid'])
                    ->whereNotNull('due_date')
                    ->where('due_date', '<', Carbon::today());
            })->count();

        // Vouchers Statistics
        $totalVouchersCount = AccountingVoucher::count();
        $postedVouchersCount = AccountingVoucher::where('status', 'posted')->count();
        $pendingVouchersCount = AccountingVoucher::whereIn('status', ['draft', 'submitted', 'approved'])->count();

        // Payment vs Receipt Totals (Posted Vouchers)
        $totalPaymentsPosted = AccountingVoucher::where('status', 'posted')
            ->whereIn('voucher_type', ['payment', 'cash_payment', 'bank_payment', 'expense'])
            ->sum('total_debit');

        $totalReceiptsPosted = AccountingVoucher::where('status', 'posted')
            ->whereIn('voucher_type', ['receipt', 'cash_receipt', 'bank_receipt', 'income'])
            ->sum('total_credit');

        // Cash & Bank Live Balances from Chart of Accounts
        $cashAccounts = ChartOfAccount::where('type', 'asset')
            ->where('category', 'cash_in_hand')
            ->get();
        $cashBalance = $cashAccounts->sum('current_balance');

        $bankAccounts = ChartOfAccount::where('type', 'asset')
            ->where('category', 'cash_at_bank')
            ->get();
        $bankBalance = $bankAccounts->sum('current_balance');

        // Account Types Summary
        $assetBalance = ChartOfAccount::where('type', 'asset')->sum('current_balance');
        $liabilityBalance = ChartOfAccount::where('type', 'liability')->sum('current_balance');
        $equityBalance = ChartOfAccount::where('type', 'equity')->sum('current_balance');
        $incomeBalance = ChartOfAccount::where('type', 'income')->sum('current_balance');
        $expenseBalance = ChartOfAccount::where('type', 'expense')->sum('current_balance');

        // Recent 6 Bills & Vouchers
        $recentBills = FinancialBill::latest('bill_date')->take(6)->get();
        $recentVouchers = AccountingVoucher::with('preparedByUser')->latest('voucher_date')->take(6)->get();

        return view('admin.bills_vouchers.dashboard', compact(
            'totalBillsCount',
            'totalBillsAmount',
            'totalPaidBillsAmount',
            'totalOutstandingBillsAmount',
            'unpaidBillsCount',
            'overdueBillsCount',
            'totalVouchersCount',
            'postedVouchersCount',
            'pendingVouchersCount',
            'totalPaymentsPosted',
            'totalReceiptsPosted',
            'cashBalance',
            'bankBalance',
            'cashAccounts',
            'bankAccounts',
            'assetBalance',
            'liabilityBalance',
            'equityBalance',
            'incomeBalance',
            'expenseBalance',
            'recentBills',
            'recentVouchers'
        ));
    }

    /**
     * Vouchers List & Filter
     */
    public function vouchersIndex(Request $request)
    {
        $query = AccountingVoucher::with(['preparedByUser', 'approvedByUser', 'postedByUser', 'entries']);

        // Type Filter
        if ($request->filled('type') && $request->type !== 'all') {
            if ($request->type === 'payment_all') {
                $query->whereIn('voucher_type', ['payment', 'cash_payment', 'bank_payment', 'expense']);
            } elseif ($request->type === 'receipt_all') {
                $query->whereIn('voucher_type', ['receipt', 'cash_receipt', 'bank_receipt', 'income']);
            } else {
                $query->where('voucher_type', $request->type);
            }
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('voucher_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('voucher_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('voucher_no', 'like', "%{$search}%")
                    ->orWhere('payee_payer_name', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%")
                    ->orWhere('cheque_no', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderBy('voucher_date', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // Metrics for filter tabs
        $allCount = AccountingVoucher::count();
        $paymentCount = AccountingVoucher::whereIn('voucher_type', ['payment', 'cash_payment', 'bank_payment', 'expense'])->count();
        $receiptCount = AccountingVoucher::whereIn('voucher_type', ['receipt', 'cash_receipt', 'bank_receipt', 'income'])->count();
        $journalCount = AccountingVoucher::where('voucher_type', 'journal')->count();
        $contraCount = AccountingVoucher::where('voucher_type', 'contra')->count();
        $postedCount = AccountingVoucher::where('status', 'posted')->count();
        $pendingCount = AccountingVoucher::whereIn('status', ['draft', 'submitted', 'approved'])->count();

        return view('admin.bills_vouchers.vouchers.index', compact(
            'vouchers',
            'allCount',
            'paymentCount',
            'receiptCount',
            'journalCount',
            'contraCount',
            'postedCount',
            'pendingCount'
        ));
    }

    /**
     * Create Voucher Form
     */
    public function voucherCreate(Request $request)
    {
        $selectedType = $request->query('type', 'payment');
        $billId = $request->query('bill_id');
        $linkedBill = null;

        if ($billId) {
            $linkedBill = FinancialBill::find($billId);
        }

        // Generate next suggested voucher number
        $prefix = match ($selectedType) {
            'receipt', 'cash_receipt', 'bank_receipt' => 'RV',
            'journal' => 'JV',
            'contra' => 'CV',
            'debit_note' => 'DN',
            'credit_note' => 'CN',
            default => 'PV',
        };

        $year = date('Y');
        $count = AccountingVoucher::where('voucher_no', 'like', "{$prefix}-{$year}-%")->count() + 1;
        $suggestedVoucherNo = sprintf("%s-%s-%05d", $prefix, $year, $count);

        // Active accounts grouped by type
        $accounts = ChartOfAccount::where('status', true)->orderBy('code')->get();

        // Unpaid bills for allocation dropdown if payment voucher
        $unpaidBills = FinancialBill::whereIn('payment_status', ['unpaid', 'partially_paid'])
            ->orderBy('bill_date', 'desc')
            ->get();

        return view('admin.bills_vouchers.vouchers.create', compact(
            'selectedType',
            'suggestedVoucherNo',
            'accounts',
            'unpaidBills',
            'linkedBill'
        ));
    }

    /**
     * Store Voucher (Double-Entry Validation)
     */
    public function voucherStore(Request $request)
    {
        $request->validate([
            'voucher_type' => 'required|string',
            'voucher_date' => 'required|date',
            'payment_mode' => 'required|string',
            'entries' => 'required|array|min:2',
            'entries.*.account_id' => 'required|exists:chart_of_accounts,id',
            'entries.*.entry_type' => 'required|in:debit,credit',
            'entries.*.amount' => 'required|numeric|min:0.01',
        ], [
            'entries.min' => 'একটি ভাউচারে অন্তত দুটি এন্ট্রি (কমপক্ষে একটি ডেবিট ও একটি ক্রেডিট) থাকতে হবে।',
            'entries.*.account_id.required' => 'প্রতিটি লাইনের হিসাবের খাত (Account Head) নির্বাচন করুন।',
            'entries.*.amount.min' => 'টাকার পরিমাণ শূন্যের চেয়ে বেশি হতে হবে।',
        ]);

        $entries = $request->input('entries', []);
        $totalDebit = 0.00;
        $totalCredit = 0.00;

        foreach ($entries as $item) {
            $amt = (float) ($item['amount'] ?? 0);
            if ($item['entry_type'] === 'debit') {
                $totalDebit += $amt;
            } elseif ($item['entry_type'] === 'credit') {
                $totalCredit += $amt;
            }
        }

        // Strict Double-Entry Equality Validation
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()->with('error', sprintf(
                'ভাউচার অসম্পূর্ণ! ডাবল এন্ট্রি নীতি অনুযায়ী মোট ডেবিট ও মোট ক্রেডিট সমান হতে হবে। (মোট ডেবিট: ৳%s | মোট ক্রেডিট: ৳%s | পার্থক্য: ৳%s)',
                number_format($totalDebit, 2),
                number_format($totalCredit, 2),
                number_format(abs($totalDebit - $totalCredit), 2)
            ));
        }

        // Generate voucher number if not supplied
        $voucherNo = $request->voucher_no;
        if (empty($voucherNo) || AccountingVoucher::where('voucher_no', $voucherNo)->exists()) {
            $prefix = match ($request->voucher_type) {
                'receipt', 'cash_receipt', 'bank_receipt' => 'RV',
                'journal' => 'JV',
                'contra' => 'CV',
                'debit_note' => 'DN',
                'credit_note' => 'CN',
                default => 'PV',
            };
            $year = date('Y', strtotime($request->voucher_date));
            $count = AccountingVoucher::where('voucher_no', 'like', "{$prefix}-{$year}-%")->count() + 1;
            $voucherNo = sprintf("%s-%s-%05d", $prefix, $year, $count);
        }

        // Handle attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/vouchers'), $fileName);
            $attachmentPath = 'uploads/vouchers/' . $fileName;
        }

        DB::beginTransaction();
        try {
            $amountInWords = AccountingVoucher::numberToWords($totalDebit);

            $voucher = AccountingVoucher::create([
                'voucher_no' => $voucherNo,
                'voucher_type' => $request->voucher_type,
                'voucher_date' => $request->voucher_date,
                'financial_year' => $request->financial_year ?? (date('Y') . '-' . (date('Y') + 1)),
                'reference_no' => $request->reference_no,
                'payment_mode' => $request->payment_mode,
                'payee_payer_name' => $request->payee_payer_name,
                'bank_account_name' => $request->bank_account_name,
                'cheque_no' => $request->cheque_no,
                'cheque_date' => $request->cheque_date,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'amount_in_words' => $amountInWords,
                'general_narration' => $request->general_narration,
                'status' => 'submitted', // draft / submitted
                'attachment' => $attachmentPath,
                'prepared_by' => Auth::id(),
            ]);

            // Save line entries
            foreach ($entries as $item) {
                $account = ChartOfAccount::find($item['account_id']);
                $voucher->entries()->create([
                    'account_id' => $account->id,
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'entry_type' => $item['entry_type'],
                    'amount' => (float) $item['amount'],
                    'line_narration' => $item['line_narration'] ?? null,
                    'cost_center' => $item['cost_center'] ?? null,
                    'reference_no' => $item['reference_no'] ?? null,
                ]);
            }

            // Save Bill Allocation if linked
            if ($request->filled('bill_id') && $request->filled('allocated_amount')) {
                $bill = FinancialBill::find($request->bill_id);
                if ($bill) {
                    $allocAmt = min((float) $request->allocated_amount, $totalDebit);
                    BillPaymentAllocation::create([
                        'voucher_id' => $voucher->id,
                        'bill_id' => $bill->id,
                        'allocated_amount' => $allocAmt,
                        'notes' => 'Allocated from voucher ' . $voucher->voucher_no,
                    ]);
                }
            }

            // Auto-post if requested by admin
            if ($request->input('action') === 'save_and_post') {
                $this->executePosting($voucher);
            }

            DB::commit();

            if ($request->input('action') === 'save_and_post') {
                return redirect()->route('admin.bills_vouchers.vouchers.show', $voucher->id)
                    ->with('success', 'ভাউচার সফলভাবে তৈরি এবং লেজারে পোস্ট (Posted) সম্পন্ন হয়েছে।');
            }

            return redirect()->route('admin.bills_vouchers.vouchers.show', $voucher->id)
                ->with('success', 'ভাউচার সফলভাবে সংরক্ষণ করা হয়েছে (পর্যালোচনাধীন)।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'ভাউচার সংরক্ষণ করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Show Voucher Details
     */
    public function voucherShow($id)
    {
        $voucher = AccountingVoucher::with([
            'entries.account',
            'billAllocations.bill',
            'preparedByUser',
            'checkedByUser',
            'approvedByUser',
            'postedByUser',
            'reversalVoucher',
            'ledgerEntries'
        ])->findOrFail($id);

        return view('admin.bills_vouchers.vouchers.show', compact('voucher'));
    }

    /**
     * Formal Printable Voucher Slip
     * Matches the paper forms provided in the user reference images:
     * 1. Payment Voucher (Pay To, Bank A/C, Cheque No, Debit/Credit lines, Signatures)
     * 2. Cash Receipt Voucher (CRV) (Received From, Ref No, Description, Amount, Words, Signatures)
     * 3. Journal Voucher (Debit Accounts table, Credit Accounts table, Signatures)
     */
    public function voucherPrint($id)
    {
        $voucher = AccountingVoucher::with([
            'entries.account',
            'billAllocations.bill',
            'preparedByUser',
            'approvedByUser',
            'postedByUser'
        ])->findOrFail($id);

        $siteSetting = SiteSetting::first();

        return view('admin.bills_vouchers.vouchers.print', compact('voucher', 'siteSetting'));
    }

    /**
     * Approve Voucher
     */
    public function voucherApprove($id)
    {
        $voucher = AccountingVoucher::findOrFail($id);

        if ($voucher->status === 'posted') {
            return back()->with('error', 'এই ভাউচারটি ইতোমধ্যে পোস্ট করা হয়েছে।');
        }

        $voucher->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'ভাউচারটি সফলভাবে অনুমোদিত (Approved) হয়েছে। এখন এটি লেজারে পোস্ট করতে পারেন।');
    }

    /**
     * Post Voucher to General Ledger & Update Account Balances
     */
    public function voucherPost($id)
    {
        $voucher = AccountingVoucher::with('entries')->findOrFail($id);

        if ($voucher->status === 'posted') {
            return back()->with('error', 'এই ভাউচারটি ইতোমধ্যে পোস্ট করা হয়েছে।');
        }

        if (abs($voucher->total_debit - $voucher->total_credit) > 0.01) {
            return back()->with('error', 'ডেবিট ও ক্রেডিট সমান না হওয়ায় এই ভাউচার পোস্ট করা সম্ভব নয়।');
        }

        DB::beginTransaction();
        try {
            $this->executePosting($voucher);
            DB::commit();

            return back()->with('success', 'ভাউচারটি সফলভাবে জেনারেল লেজারে পোস্ট (Posted) করা হয়েছে এবং হিসাবের ব্যালেন্স আপডেট হয়েছে।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'পোস্ট করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Internal Core Engine: Execute Posting & Balance Updates
     */
    private function executePosting(AccountingVoucher $voucher)
    {
        // 1. Insert into General Ledger Entries & Update Chart of Accounts Current Balances
        foreach ($voucher->entries as $entry) {
            $account = ChartOfAccount::find($entry->account_id);
            if (!$account) continue;

            $debit = ($entry->entry_type === 'debit') ? (float) $entry->amount : 0.00;
            $credit = ($entry->entry_type === 'credit') ? (float) $entry->amount : 0.00;

            // Balance calculation rules based on Account Type
            // Assets & Expenses: Debit increases balance, Credit decreases balance
            // Liabilities, Equity & Incomes: Credit increases balance, Debit decreases balance
            if (in_array($account->type, ['asset', 'expense'])) {
                $newBalance = $account->current_balance + $debit - $credit;
            } else {
                $newBalance = $account->current_balance + $credit - $debit;
            }

            $account->update(['current_balance' => $newBalance]);

            GeneralLedgerEntry::create([
                'voucher_id' => $voucher->id,
                'voucher_no' => $voucher->voucher_no,
                'voucher_type' => $voucher->voucher_type,
                'transaction_date' => $voucher->voucher_date,
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'debit' => $debit,
                'credit' => $credit,
                'running_balance' => $newBalance,
                'narration' => $entry->line_narration ?: $voucher->general_narration,
                'reference_no' => $entry->reference_no ?: $voucher->reference_no,
            ]);
        }

        // 2. Settle Allocated Bills
        $allocations = BillPaymentAllocation::where('voucher_id', $voucher->id)->get();
        foreach ($allocations as $alloc) {
            $bill = FinancialBill::find($alloc->bill_id);
            if ($bill) {
                $newPaid = $bill->paid_amount + $alloc->allocated_amount;
                $newOutstanding = max(0, $bill->grand_total - $newPaid);
                $status = ($newOutstanding <= 0.01) ? 'paid' : 'partially_paid';

                $bill->update([
                    'paid_amount' => $newPaid,
                    'outstanding_amount' => $newOutstanding,
                    'payment_status' => $status,
                ]);
            }
        }

        // 3. Mark Voucher as Posted
        $voucher->update([
            'status' => 'posted',
            'posted_by' => Auth::id(),
            'posted_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse a Posted Voucher (Immutability Principal - Anti-Voucher)
     */
    public function voucherReverse(Request $request, $id)
    {
        $voucher = AccountingVoucher::with(['entries', 'billAllocations'])->findOrFail($id);

        if ($voucher->status !== 'posted') {
            return back()->with('error', 'শুধুমাত্র পোস্ট করা ভাউচার রিভার্স (বিপরীত দাখিলা) করা যায়।');
        }

        if ($voucher->is_reversed) {
            return back()->with('error', 'এই ভাউচারটি ইতোমধ্যেই রিভার্স করা হয়েছে।');
        }

        $reason = $request->input('reversal_reason', 'ভুল এন্ট্রির কারণে রিভার্স করা হলো');

        DB::beginTransaction();
        try {
            $year = date('Y');
            $revNo = sprintf("REV-%s-%05d", $year, AccountingVoucher::where('voucher_no', 'like', "REV-{$year}-%")->count() + 1);

            // Create Reversing Voucher
            $revVoucher = AccountingVoucher::create([
                'voucher_no' => $revNo,
                'voucher_type' => 'journal',
                'voucher_date' => Carbon::today(),
                'financial_year' => $voucher->financial_year,
                'reference_no' => 'Reversal of ' . $voucher->voucher_no,
                'payment_mode' => 'adjustment',
                'payee_payer_name' => $voucher->payee_payer_name,
                'total_debit' => $voucher->total_credit,
                'total_credit' => $voucher->total_debit,
                'amount_in_words' => $voucher->amount_in_words,
                'general_narration' => "বিপরীত দাখিলা (Reversal of {$voucher->voucher_no}). কারণ: {$reason}",
                'status' => 'posted',
                'prepared_by' => Auth::id(),
                'posted_by' => Auth::id(),
                'posted_at' => Carbon::now(),
            ]);

            // Reverse each entry: Swapping Debits to Credits, and Credits to Debits
            foreach ($voucher->entries as $entry) {
                $account = ChartOfAccount::find($entry->account_id);
                $reversedType = ($entry->entry_type === 'debit') ? 'credit' : 'debit';
                $debit = ($reversedType === 'debit') ? (float) $entry->amount : 0.00;
                $credit = ($reversedType === 'credit') ? (float) $entry->amount : 0.00;

                $revVoucher->entries()->create([
                    'account_id' => $entry->account_id,
                    'account_code' => $entry->account_code,
                    'account_name' => $entry->account_name,
                    'entry_type' => $reversedType,
                    'amount' => $entry->amount,
                    'line_narration' => 'Reversal entry for line in ' . $voucher->voucher_no,
                ]);

                if ($account) {
                    if (in_array($account->type, ['asset', 'expense'])) {
                        $newBalance = $account->current_balance + $debit - $credit;
                    } else {
                        $newBalance = $account->current_balance + $credit - $debit;
                    }
                    $account->update(['current_balance' => $newBalance]);

                    GeneralLedgerEntry::create([
                        'voucher_id' => $revVoucher->id,
                        'voucher_no' => $revVoucher->voucher_no,
                        'voucher_type' => 'journal',
                        'transaction_date' => Carbon::today(),
                        'account_id' => $account->id,
                        'account_code' => $account->code,
                        'account_name' => $account->name,
                        'debit' => $debit,
                        'credit' => $credit,
                        'running_balance' => $newBalance,
                        'narration' => "বিপরীত দাখিলা (Reversal of {$voucher->voucher_no})",
                        'reference_no' => $revVoucher->reference_no,
                    ]);
                }
            }

            // Revert Bill Allocations
            foreach ($voucher->billAllocations as $alloc) {
                $bill = FinancialBill::find($alloc->bill_id);
                if ($bill) {
                    $newPaid = max(0, $bill->paid_amount - $alloc->allocated_amount);
                    $newOutstanding = $bill->grand_total - $newPaid;
                    $status = ($newPaid <= 0.01) ? 'unpaid' : 'partially_paid';

                    $bill->update([
                        'paid_amount' => $newPaid,
                        'outstanding_amount' => $newOutstanding,
                        'payment_status' => $status,
                    ]);
                }
            }

            // Mark Original as Reversed
            $voucher->update([
                'is_reversed' => true,
                'reversal_voucher_id' => $revVoucher->id,
                'reversal_reason' => $reason,
                'status' => 'reversed',
            ]);

            DB::commit();

            return redirect()->route('admin.bills_vouchers.vouchers.show', $voucher->id)
                ->with('success', "ভাউচারটি সফলভাবে রিভার্স করা হয়েছে। নতুন বিপরীত ভাউচার নং: {$revVoucher->voucher_no}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'ভাউচার রিভার্স করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Bills List
     */
    public function billsIndex(Request $request)
    {
        $query = FinancialBill::with('creator');

        if ($request->filled('bill_type') && $request->bill_type !== 'all') {
            $query->where('bill_type', $request->bill_type);
        }

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('bill_no', 'like', "%{$search}%")
                    ->orWhere('party_name', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        $bills = $query->orderBy('bill_date', 'desc')->paginate(15)->withQueryString();

        $allBillsCount = FinancialBill::count();
        $unpaidCount = FinancialBill::where('payment_status', 'unpaid')->count();
        $partialCount = FinancialBill::where('payment_status', 'partially_paid')->count();
        $paidCount = FinancialBill::where('payment_status', 'paid')->count();

        return view('admin.bills_vouchers.bills.index', compact(
            'bills',
            'allBillsCount',
            'unpaidCount',
            'partialCount',
            'paidCount'
        ));
    }

    /**
     * Create Bill Form
     */
    public function billCreate()
    {
        $year = date('Y');
        $count = FinancialBill::where('bill_no', 'like', "BILL-{$year}-%")->count() + 1;
        $suggestedBillNo = sprintf("BILL-%s-%04d", $year, $count);

        return view('admin.bills_vouchers.bills.create', compact('suggestedBillNo'));
    }

    /**
     * Store Bill with Multi Items
     */
    public function billStore(Request $request)
    {
        $request->validate([
            'bill_type' => 'required|string',
            'bill_date' => 'required|date',
            'party_name' => 'required|string|max:150',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'party_name.required' => 'সরবরাহকারী বা ভেন্ডরের নাম দিন।',
            'items.min' => 'অন্তত একটি আইটেম যুক্ত করতে হবে।',
        ]);

        $year = date('Y', strtotime($request->bill_date));
        $billNo = $request->bill_no;
        if (empty($billNo) || FinancialBill::where('bill_no', $billNo)->exists()) {
            $count = FinancialBill::where('bill_no', 'like', "BILL-{$year}-%")->count() + 1;
            $billNo = sprintf("BILL-%s-%04d", $year, $count);
        }

        $subtotal = 0.00;
        $itemsData = [];

        foreach ($request->input('items', []) as $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $itemSubtotal = $qty * $price;
            $taxPercent = (float) ($item['tax_percent'] ?? 0);
            $taxAmount = ($itemSubtotal * $taxPercent) / 100;
            $totalAmount = $itemSubtotal + $taxAmount;

            $subtotal += $itemSubtotal;

            $itemsData[] = [
                'item_name' => $item['item_name'],
                'description' => $item['description'] ?? null,
                'quantity' => $qty,
                'unit' => $item['unit'] ?? 'Pcs',
                'unit_price' => $price,
                'subtotal' => $itemSubtotal,
                'tax_percent' => $taxPercent,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ];
        }

        $discount = (float) ($request->discount_amount ?? 0);
        $taxVat = (float) ($request->tax_vat_amount ?? 0);
        $additional = (float) ($request->additional_charges ?? 0);
        $grandTotal = max(0, $subtotal - $discount + $taxVat + $additional);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bills'), $fileName);
            $attachmentPath = 'uploads/bills/' . $fileName;
        }

        DB::beginTransaction();
        try {
            $bill = FinancialBill::create([
                'bill_no' => $billNo,
                'reference_no' => $request->reference_no,
                'bill_type' => $request->bill_type,
                'bill_date' => $request->bill_date,
                'due_date' => $request->due_date,
                'financial_year' => $request->financial_year ?? (date('Y') . '-' . (date('Y') + 1)),
                'party_type' => $request->party_type ?? 'supplier',
                'party_name' => $request->party_name,
                'party_phone' => $request->party_phone,
                'party_address' => $request->party_address,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'tax_vat_amount' => $taxVat,
                'additional_charges' => $additional,
                'grand_total' => $grandTotal,
                'paid_amount' => 0.00,
                'outstanding_amount' => $grandTotal,
                'payment_status' => 'unpaid',
                'approval_status' => 'approved',
                'notes' => $request->notes,
                'attachment' => $attachmentPath,
                'created_by' => Auth::id(),
            ]);

            foreach ($itemsData as $itemRow) {
                $bill->items()->create($itemRow);
            }

            DB::commit();

            return redirect()->route('admin.bills_vouchers.bills.show', $bill->id)
                ->with('success', 'বিল সফলভাবে সংরক্ষণ করা হয়েছে।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'বিল সংরক্ষণ করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Show Bill Details & Payment Ledger
     */
    public function billShow($id)
    {
        $bill = FinancialBill::with(['items', 'paymentAllocations.voucher', 'creator', 'approver'])->findOrFail($id);
        return view('admin.bills_vouchers.bills.show', compact('bill'));
    }

    /**
     * Chart of Accounts (COA) Directory & Balance View
     */
    public function chartOfAccounts()
    {
        $accounts = ChartOfAccount::orderBy('code')->get();

        $accountsGrouped = [
            'asset' => $accounts->where('type', 'asset'),
            'liability' => $accounts->where('type', 'liability'),
            'equity' => $accounts->where('type', 'equity'),
            'income' => $accounts->where('type', 'income'),
            'expense' => $accounts->where('type', 'expense'),
        ];

        $totalAssets = $accountsGrouped['asset']->sum('current_balance');
        $totalLiabilities = $accountsGrouped['liability']->sum('current_balance');
        $totalEquity = $accountsGrouped['equity']->sum('current_balance');
        $totalIncome = $accountsGrouped['income']->sum('current_balance');
        $totalExpense = $accountsGrouped['expense']->sum('current_balance');

        return view('admin.bills_vouchers.chart_of_accounts', compact(
            'accountsGrouped',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'totalIncome',
            'totalExpense'
        ));
    }

    /**
     * Store Custom Account Head
     */
    public function chartOfAccountsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'opening_balance' => 'nullable|numeric',
        ]);

        $code = $request->code;
        if (empty($code)) {
            $baseCode = match ($request->type) {
                'asset' => 1000,
                'liability' => 2000,
                'equity' => 3000,
                'income' => 4000,
                'expense' => 5000,
            };
            $maxCode = ChartOfAccount::where('type', $request->type)->max('code');
            $code = $maxCode ? (int) $maxCode + 1 : $baseCode + 1;
        }

        $opening = (float) ($request->opening_balance ?? 0);

        ChartOfAccount::create([
            'code' => (string) $code,
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'opening_balance' => $opening,
            'current_balance' => $opening,
            'description' => $request->description,
            'status' => true,
        ]);

        return back()->with('success', 'নতুন হিসাবের খাত (Account Head) সফলভাবে চার্ট অব অ্যাকাউন্টসে যুক্ত হয়েছে।');
    }

    /**
     * General Ledger Report
     */
    public function generalLedger(Request $request)
    {
        $accounts = ChartOfAccount::where('status', true)->orderBy('code')->get();
        $selectedAccount = null;
        $ledgerEntries = collect();
        $openingBalance = 0.00;
        $totalDebit = 0.00;
        $totalCredit = 0.00;
        $closingBalance = 0.00;

        $accountId = $request->input('account_id', $accounts->first()?->id);
        $dateFrom = $request->input('date_from', date('Y-01-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        if ($accountId) {
            $selectedAccount = ChartOfAccount::find($accountId);

            if ($selectedAccount) {
                // Calculate opening balance before date_from
                $prevDebit = GeneralLedgerEntry::where('account_id', $accountId)
                    ->whereDate('transaction_date', '<', $dateFrom)
                    ->sum('debit');

                $prevCredit = GeneralLedgerEntry::where('account_id', $accountId)
                    ->whereDate('transaction_date', '<', $dateFrom)
                    ->sum('credit');

                if (in_array($selectedAccount->type, ['asset', 'expense'])) {
                    $openingBalance = $selectedAccount->opening_balance + $prevDebit - $prevCredit;
                } else {
                    $openingBalance = $selectedAccount->opening_balance + $prevCredit - $prevDebit;
                }

                // Entries in period
                $ledgerEntries = GeneralLedgerEntry::where('account_id', $accountId)
                    ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                    ->orderBy('transaction_date')
                    ->orderBy('id')
                    ->get();

                $totalDebit = $ledgerEntries->sum('debit');
                $totalCredit = $ledgerEntries->sum('credit');

                if (in_array($selectedAccount->type, ['asset', 'expense'])) {
                    $closingBalance = $openingBalance + $totalDebit - $totalCredit;
                } else {
                    $closingBalance = $openingBalance + $totalCredit - $totalDebit;
                }
            }
        }

        return view('admin.bills_vouchers.reports.general_ledger', compact(
            'accounts',
            'selectedAccount',
            'ledgerEntries',
            'openingBalance',
            'totalDebit',
            'totalCredit',
            'closingBalance',
            'accountId',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Trial Balance Report (রেওয়ামিল)
     */
    public function trialBalance(Request $request)
    {
        $asOfDate = $request->input('as_of_date', date('Y-m-d'));
        $accounts = ChartOfAccount::where('status', true)->orderBy('code')->get();

        $rows = [];
        $grandDebit = 0.00;
        $grandCredit = 0.00;

        foreach ($accounts as $acc) {
            $debitSum = GeneralLedgerEntry::where('account_id', $acc->id)
                ->whereDate('transaction_date', '<=', $asOfDate)
                ->sum('debit');

            $creditSum = GeneralLedgerEntry::where('account_id', $acc->id)
                ->whereDate('transaction_date', '<=', $asOfDate)
                ->sum('credit');

            // Apply opening balance
            if (in_array($acc->type, ['asset', 'expense'])) {
                $debitSum += (float) $acc->opening_balance;
            } else {
                $creditSum += (float) $acc->opening_balance;
            }

            $netBalance = $debitSum - $creditSum;
            $rowDebit = 0.00;
            $rowCredit = 0.00;

            if ($netBalance > 0) {
                $rowDebit = $netBalance;
            } elseif ($netBalance < 0) {
                $rowCredit = abs($netBalance);
            }

            if ($rowDebit > 0 || $rowCredit > 0 || $debitSum > 0 || $creditSum > 0) {
                $rows[] = [
                    'account' => $acc,
                    'total_debit' => $debitSum,
                    'total_credit' => $creditSum,
                    'net_debit' => $rowDebit,
                    'net_credit' => $rowCredit,
                ];

                $grandDebit += $rowDebit;
                $grandCredit += $rowCredit;
            }
        }

        $isBalanced = abs($grandDebit - $grandCredit) <= 0.01;

        return view('admin.bills_vouchers.reports.trial_balance', compact(
            'rows',
            'grandDebit',
            'grandCredit',
            'isBalanced',
            'asOfDate'
        ));
    }
}
