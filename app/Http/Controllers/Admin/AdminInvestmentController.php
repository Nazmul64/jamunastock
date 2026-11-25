<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestmentSector;
use App\Models\InvestmentProject;
use App\Models\UserInvestment;
use App\Models\InvestmentDividend;
use App\Models\InvestmentWithdrawal;
use App\Models\AuditLog;
use Illuminate\Support\Facades\File;

class AdminInvestmentController extends Controller
{
    // ==========================================
    // 1. INVESTMENT SECTORS
    // ==========================================
    public function sectorsIndex()
    {
        $sectors = InvestmentSector::orderBy('order_num')->get();
        return view('admin.investments.sectors.index', compact('sectors'));
    }

    public function sectorsEdit($id)
    {
        $sector = InvestmentSector::findOrFail($id);
        return view('admin.investments.sectors.edit', compact('sector'));
    }

    public function sectorsUpdate(Request $request, $id)
    {
        $sector = InvestmentSector::findOrFail($id);
        $validated = $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'investment_range' => 'required|string|max:255',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
            'description' => 'nullable|string',
            'profit_loss_policy' => 'nullable|string',
            'order_num' => 'nullable|integer',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($sector->image && File::exists(public_path($sector->image))) {
                File::delete(public_path($sector->image));
            }
            $imgName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/sectors'), $imgName);
            $validated['image'] = 'uploads/sectors/' . $imgName;
        }

        $validated['is_active'] = $request->has('is_active');
        $sector->update($validated);

        AuditLog::log('sector_updated', 'InvestmentSector', $sector->id, 'Updated investment sector ' . $sector->name_bn);

        return redirect()->route('admin.investments.sectors.index')->with('success', 'বিনিয়োগ খাত সফলভাবে আপডেট করা হয়েছে।');
    }

    // ==========================================
    // 2. INVESTMENT PROJECTS
    // ==========================================
    public function projectsIndex(Request $request)
    {
        $sectorId = $request->query('sector_id');
        $query = InvestmentProject::with('sector');

        if ($sectorId) {
            $query->where('sector_id', $sectorId);
        }

        $projects = $query->orderByDesc('id')->paginate(15);
        $sectors = InvestmentSector::where('is_active', true)->get();

        return view('admin.investments.projects.index', compact('projects', 'sectors', 'sectorId'));
    }

    public function projectsCreate()
    {
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();
        return view('admin.investments.projects.create', compact('sectors'));
    }

    public function projectsStore(Request $request)
    {
        $validated = $request->validate([
            'sector_id' => 'required|exists:investment_sectors,id',
            'project_name' => 'required|string|max:255',
            'target_budget' => 'required|numeric|min:0',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'nullable|numeric|min:0',
            'duration' => 'required|string|max:255',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string',
            'field_location' => 'nullable|string|max:255',
            'expected_roi_policy' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'status' => 'required|in:open,ongoing,completed,paused',
        ]);

        $validated['project_id_code'] = 'PRJ-' . strtoupper(uniqid());
        $validated['max_investment'] = $validated['max_investment'] ?? 0;
        $validated['total_raised'] = 0;
        $validated['investors_count'] = 0;
        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('banner_image')) {
            $imgName = time() . '_' . uniqid() . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $request->file('banner_image')->move(public_path('uploads/projects'), $imgName);
            $validated['banner_image'] = 'uploads/projects/' . $imgName;
        }

        $project = InvestmentProject::create($validated);
        AuditLog::log('project_created', 'InvestmentProject', $project->id, 'Created investment project ' . $project->project_name);

        return redirect()->route('admin.investments.projects.index')->with('success', 'প্রকল্প সফলভাবে তৈরি করা হয়েছে।');
    }

    public function projectsEdit($id)
    {
        $project = InvestmentProject::findOrFail($id);
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();
        return view('admin.investments.projects.edit', compact('project', 'sectors'));
    }

    public function projectsUpdate(Request $request, $id)
    {
        $project = InvestmentProject::findOrFail($id);
        $validated = $request->validate([
            'sector_id' => 'required|exists:investment_sectors,id',
            'project_name' => 'required|string|max:255',
            'target_budget' => 'required|numeric|min:0',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'nullable|numeric|min:0',
            'duration' => 'required|string|max:255',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string',
            'field_location' => 'nullable|string|max:255',
            'expected_roi_policy' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'status' => 'required|in:open,ongoing,completed,paused',
        ]);

        $validated['max_investment'] = $validated['max_investment'] ?? 0;
        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('banner_image')) {
            if ($project->banner_image && File::exists(public_path($project->banner_image))) {
                File::delete(public_path($project->banner_image));
            }
            $imgName = time() . '_' . uniqid() . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $request->file('banner_image')->move(public_path('uploads/projects'), $imgName);
            $validated['banner_image'] = 'uploads/projects/' . $imgName;
        }

        $project->update($validated);
        return redirect()->route('admin.investments.projects.index')->with('success', 'প্রকল্পের তথ্য সফলভাবে আপডেট হয়েছে।');
    }

    public function projectsDestroy($id)
    {
        $project = InvestmentProject::findOrFail($id);
        if ($project->banner_image && File::exists(public_path($project->banner_image))) {
            File::delete(public_path($project->banner_image));
        }
        $project->delete();

        return redirect()->route('admin.investments.projects.index')->with('success', 'প্রকল্প মুছে ফেলা হয়েছে।');
    }

    // ==========================================
    // 3. USER INVESTMENTS
    // ==========================================
    public function userInvestmentsIndex(Request $request)
    {
        $status = $request->query('status');
        $query = UserInvestment::with(['user', 'project', 'sector']);

        if ($status) {
            $query->where('status', $status);
        }

        $investments = $query->orderByDesc('id')->paginate(20);
        $totalInvested = UserInvestment::whereIn('status', ['approved', 'active', 'completed'])->sum('amount');
        $pendingCount = UserInvestment::where('status', 'pending')->count();
        $projects = InvestmentProject::where('status', 'open')->orWhere('status', 'ongoing')->get();
        $users = \App\Models\User::orderBy('name')->get();

        return view('admin.investments.user_investments.index', compact('investments', 'status', 'totalInvested', 'pendingCount', 'projects', 'users'));
    }

    public function storeUserInvestment(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:investment_projects,id',
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
            'status' => 'required|in:approved,pending',
        ]);

        $project = InvestmentProject::findOrFail($validated['project_id']);

        $investment = UserInvestment::create([
            'investment_code' => 'INV-' . strtoupper(uniqid()),
            'user_id' => $validated['user_id'],
            'project_id' => $project->id,
            'sector_id' => $project->sector_id,
            'amount' => $validated['amount'],
            'investment_date' => now()->toDateString(),
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'status' => $validated['status'],
        ]);

        if ($validated['status'] === 'approved') {
            $project->increment('total_raised', $validated['amount']);
            $project->increment('investors_count', 1);
        }

        AuditLog::log('admin_created_user_investment', 'UserInvestment', $investment->id, 'Admin created investment of ' . $validated['amount']);

        return back()->with('success', 'গ্রাহক বিনিয়োগ সফলভাবে অন্তর্ভুক্ত করা হয়েছে।');
    }

    public function updateInvestmentStatus(Request $request, $id)
    {
        $investment = UserInvestment::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,active,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $prevStatus = $investment->status;
        $investment->update($validated);

        if ($validated['status'] === 'approved' && $prevStatus !== 'approved') {
            // Update project raised amount & investor count
            if ($investment->project) {
                $investment->project->increment('total_raised', $investment->amount);
                $investment->project->increment('investors_count', 1);
            }
        }

        AuditLog::log('investment_status_updated', 'UserInvestment', $investment->id, "Status changed from $prevStatus to {$validated['status']}");

        return back()->with('success', 'বিনিয়োগ স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।');
    }

    // ==========================================
    // 4. DIVIDENDS & WITHDRAWALS
    // ==========================================
    public function dividendsIndex()
    {
        $dividends = InvestmentDividend::with(['user', 'investment.project'])->orderByDesc('id')->paginate(20);
        $totalPaid = InvestmentDividend::where('status', 'paid')->sum('amount');
        $totalPending = InvestmentDividend::where('status', 'pending')->sum('amount');

        return view('admin.investments.dividends.index', compact('dividends', 'totalPaid', 'totalPending'));
    }

    public function storeDividend(Request $request)
    {
        $validated = $request->validate([
            'user_investment_id' => 'required|exists:user_investments,id',
            'amount' => 'required|numeric|min:1',
            'declaration_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $investment = UserInvestment::findOrFail($validated['user_investment_id']);

        $dividend = InvestmentDividend::create([
            'user_investment_id' => $investment->id,
            'user_id' => $investment->user_id,
            'amount' => $validated['amount'],
            'declaration_date' => $validated['declaration_date'],
            'status' => 'pending',
            'remarks' => $validated['remarks'] ?? '',
        ]);

        $investment->increment('pending_dividend', $validated['amount']);
        AuditLog::log('dividend_declared', 'InvestmentDividend', $dividend->id, 'Declared dividend of ' . $validated['amount']);

        return back()->with('success', 'লভ্যাংশ সফলভাবে ঘোষণা করা হয়েছে।');
    }

    public function markDividendPaid(Request $request, $id)
    {
        $dividend = InvestmentDividend::findOrFail($id);
        $dividend->update([
            'status' => 'paid',
            'payment_date' => now()->toDateString(),
            'payout_method' => $request->input('payout_method', 'Bank Transfer'),
            'transaction_reference' => $request->input('transaction_reference', 'TXN-' . uniqid()),
        ]);

        if ($dividend->investment) {
            $dividend->investment->decrement('pending_dividend', $dividend->amount);
            $dividend->investment->increment('paid_dividend', $dividend->amount);
            $dividend->investment->increment('return_earned', $dividend->amount);
        }

        return back()->with('success', 'লভ্যাংশ পরিশোধ চিহ্নিত করা হয়েছে।');
    }

    public function withdrawalsIndex(Request $request)
    {
        $status = $request->query('status');
        $query = InvestmentWithdrawal::with(['user', 'investment']);

        if ($status) {
            $query->where('status', $status);
        }

        $withdrawals = $query->orderByDesc('id')->paginate(20);
        return view('admin.investments.withdrawals.index', compact('withdrawals', 'status'));
    }

    public function updateWithdrawalStatus(Request $request, $id)
    {
        $withdrawal = InvestmentWithdrawal::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'transaction_reference' => 'nullable|string',
            'admin_remarks' => 'nullable|string',
        ]);

        $validated['processed_at'] = now();
        $withdrawal->update($validated);

        AuditLog::log('withdrawal_status_updated', 'InvestmentWithdrawal', $withdrawal->id, 'Withdrawal status updated to ' . $validated['status']);

        return back()->with('success', 'উত্তোলন আবেদন স্ট্যাটাস আপডেট হয়েছে।');
    }
}

