<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentralFinance;
use App\Models\ShareholderProfile;
use App\Models\UserInvestment;
use App\Models\CardNumber;
use App\Models\Partnership;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AdminFinanceController extends Controller
{
    /**
     * Display Central Financial Management Ledger
     */
    public function index(Request $request)
    {
        $type = $request->query('entry_type');
        $query = CentralFinance::query();

        if ($type) {
            $query->where('entry_type', $type);
        }

        $entries = $query->orderByDesc('entry_date')->orderByDesc('id')->paginate(20);

        $totalIncome = CentralFinance::where('entry_type', 'income')->sum('amount');
        $totalExpense = CentralFinance::where('entry_type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('admin.finance.index', compact('entries', 'totalIncome', 'totalExpense', 'netBalance', 'type'));
    }

    /**
     * Store new income / expense entry
     */
    public function storeEntry(Request $request)
    {
        $validated = $request->validate([
            'entry_type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'entry_date' => 'required|date',
            'payment_method' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['transaction_code'] = 'CF-' . strtoupper(uniqid());
        $validated['created_by'] = Auth::id() ?? 1;

        $entry = CentralFinance::create($validated);
        AuditLog::log('central_finance_entry', 'CentralFinance', $entry->id, 'Added ' . $validated['entry_type'] . ' of ' . $validated['amount']);

        return back()->with('success', 'আর্থিক লেনদেন বিবরণী সফলভাবে সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Unified Account Overview across the 5 account types specified in Module 4
     */
    public function unifiedAccounts()
    {
        // 1. SME Card Accounts (using card_numbers table)
        $smeCardCount = CardNumber::where('is_used', 1)->count();
        if ($smeCardCount === 0) {
            $smeCardCount = CardNumber::count();
        }
        $smeCardRevenue = $smeCardCount * 300; // 300 TK per SME card

        // 2. SME Gold Card Accounts
        // 3. Property Card Accounts
        // 4. Shareholder Accounts
        $shareholdersCount = ShareholderProfile::where('status', 'active')->count();
        $shareCapital = ShareholderProfile::where('status', 'active')->sum('total_investment');
        $shareOfficeFund = ShareholderProfile::where('status', 'active')->sum('office_expense_paid');

        // 5. Investment Projects Capital
        $investmentsCapital = UserInvestment::whereIn('status', ['approved', 'active', 'completed'])->sum('amount');

        // 6. Partnership Capital
        $partnershipCapital = Partnership::where('status', 'active')->sum('capital_contribution');

        // Central Finances
        $totalIncome = CentralFinance::where('entry_type', 'income')->sum('amount');
        $totalExpense = CentralFinance::where('entry_type', 'expense')->sum('amount');
        $centralBalance = $totalIncome - $totalExpense;

        return view('admin.finance.unified_accounts', compact(
            'smeCardCount',
            'smeCardRevenue',
            'shareholdersCount',
            'shareCapital',
            'shareOfficeFund',
            'investmentsCapital',
            'partnershipCapital',
            'totalIncome',
            'totalExpense',
            'centralBalance'
        ));
    }
}
