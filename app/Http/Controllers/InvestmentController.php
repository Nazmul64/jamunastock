<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvestmentSector;
use App\Models\InvestmentProject;
use App\Models\UserInvestment;
use App\Models\InvestmentWithdrawal;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    /**
     * Show all 10 Investment Sectors
     */
    public function sectors()
    {
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();
        return view('frontend.investments.sectors', compact('sectors'));
    }

    /**
     * Show single sector details and associated projects
     */
    public function sectorDetail($id)
    {
        $sector = InvestmentSector::with(['projects' => function ($q) {
            $q->where('status', 'open')->orWhere('status', 'ongoing');
        }])->findOrFail($id);

        $otherSectors = InvestmentSector::where('id', '!=', $id)->where('is_active', true)->take(5)->get();

        return view('frontend.investments.sector_detail', compact('sector', 'otherSectors'));
    }

    /**
     * Show all investment projects
     */
    public function projects(Request $request)
    {
        $sectorId = $request->query('sector_id');
        $query = InvestmentProject::with('sector');

        if ($sectorId) {
            $query->where('sector_id', $sectorId);
        }

        $projects = $query->orderByDesc('id')->paginate(9);
        $sectors = InvestmentSector::where('is_active', true)->get();

        return view('frontend.investments.projects', compact('projects', 'sectors', 'sectorId'));
    }

    /**
     * Show single project details
     */
    public function projectDetail($id)
    {
        $project = InvestmentProject::with('sector')->findOrFail($id);
        $relatedProjects = InvestmentProject::where('sector_id', $project->sector_id)
            ->where('id', '!=', $id)
            ->take(3)
            ->get();

        return view('frontend.investments.project_detail', compact('project', 'relatedProjects'));
    }

    /**
     * Store new user investment
     */
    public function storeInvestment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to apply for investment.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:investment_projects,id',
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $project = InvestmentProject::findOrFail($validated['project_id']);

        if ($validated['amount'] < $project->min_investment) {
            return back()->with('error', 'Minimum investment amount is BDT ' . number_format($project->min_investment) . '.');
        }

        if ($project->max_investment > 0 && $validated['amount'] > $project->max_investment) {
            return back()->with('error', 'Maximum investment amount is BDT ' . number_format($project->max_investment) . '.');
        }

        $filePath = null;
        if ($request->hasFile('payment_proof')) {
            $fileName = time() . '_' . uniqid() . '.' . $request->file('payment_proof')->getClientOriginalExtension();
            $request->file('payment_proof')->move(public_path('uploads/investments'), $fileName);
            $filePath = 'uploads/investments/' . $fileName;
        }

        $investment = UserInvestment::create([
            'investment_code' => 'INV-' . strtoupper(uniqid()),
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'sector_id' => $project->sector_id,
            'amount' => $validated['amount'],
            'investment_date' => now()->toDateString(),
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'payment_proof' => $filePath,
            'status' => 'pending',
        ]);

        AuditLog::log('user_investment_submitted', 'UserInvestment', $investment->id, 'User submitted investment of ' . $validated['amount']);

        return back()->with('success', 'Your investment application has been submitted successfully. It will become active after administrative verification.');
    }

    /**
     * Logged-in user investments list
     */
    public function myInvestments()
    {
        $userId = Auth::id();
        $investments = UserInvestment::with(['project', 'sector', 'dividends'])
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->paginate(10);

        $totalInvested = UserInvestment::where('user_id', $userId)->whereIn('status', ['approved', 'active', 'completed'])->sum('amount');
        $totalEarned = UserInvestment::where('user_id', $userId)->sum('return_earned');
        $paidDividends = UserInvestment::where('user_id', $userId)->sum('paid_dividend');
        $withdrawals = InvestmentWithdrawal::where('user_id', $userId)->orderByDesc('id')->take(5)->get();

        return view('user.investments.index', compact('investments', 'totalInvested', 'totalEarned', 'paidDividends', 'withdrawals'));
    }

    /**
     * User submit withdrawal request
     */
    public function requestWithdrawal(Request $request)
    {
        $validated = $request->validate([
            'user_investment_id' => 'nullable|exists:user_investments,id',
            'amount' => 'required|numeric|min:500',
            'withdrawal_type' => 'required|in:capital,profit,dividend',
            'payment_method' => 'required|string',
            'account_number' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        $withdrawal = InvestmentWithdrawal::create([
            'withdrawal_code' => 'WTH-' . strtoupper(uniqid()),
            'user_id' => Auth::id(),
            'user_investment_id' => $validated['user_investment_id'] ?? null,
            'amount' => $validated['amount'],
            'withdrawal_type' => $validated['withdrawal_type'],
            'payment_method' => $validated['payment_method'],
            'account_number' => $validated['account_number'],
            'reason' => $validated['reason'] ?? '',
            'status' => 'pending',
        ]);

        AuditLog::log('withdrawal_requested', 'InvestmentWithdrawal', $withdrawal->id, 'User requested withdrawal of ' . $validated['amount']);

        return back()->with('success', 'Your withdrawal request has been received successfully.');
    }
}

