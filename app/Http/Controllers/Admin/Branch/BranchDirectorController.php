<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmBranch;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmLeaveRequest;
use App\Models\Branch\BranchCommission;
use App\Models\Branch\BranchDailyReport;
use App\Models\Branch\BranchCashbook;
use App\Models\Branch\BranchTask;
use App\Models\Branch\BranchKpiTarget;
use App\Models\Branch\BranchAsset;
use App\Models\Branch\BranchBusinessLead;
use App\Models\Branch\BranchComplaint;
use App\Models\Branch\BranchDocument;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BranchDirectorController extends Controller
{
    /**
     * Resolve the active branch strictly enforcing data isolation:
     * - Admins / Super Admin can view all branches and switch between them.
     * - Branch Directors / Staff can ONLY view their assigned branch.
     */
    protected function resolveBranch(Request $request): array
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $allBranches = collect();
        $isAdmin = $user->isSuperAdmin() || $user->isAdmin();

        if ($isAdmin) {
            $allBranches = HrmBranch::where('status', 'active')->orderBy('name')->get();
            $requestedId = $request->query('branch_id') ?: session('admin_selected_branch_id');

            if ($requestedId && $branch = HrmBranch::find($requestedId)) {
                session(['admin_selected_branch_id' => $branch->id]);
            } else {
                $branch = $allBranches->first() ?: HrmBranch::first();
                if ($branch) {
                    session(['admin_selected_branch_id' => $branch->id]);
                }
            }
        } else {
            // Strictly enforce isolation for Branch Director
            $branchId = $user->branch_id ?: ($user->managedBranch?->id ?: null);
            if (!$branchId) {
                // Fallback to first branch if user is assigned to manager
                $branch = HrmBranch::where('manager_id', $user->id)->first();
            } else {
                $branch = HrmBranch::find($branchId);
            }

            if (!$branch) {
                abort(403, 'আপনার অ্যাকাউন্টের সাথে কোনো অনুমোদিত ব্রাঞ্চ সংযুক্ত করা নেই। অনুগ্রহ করে প্রধান কার্যালয়ের অ্যাডমিনের সাথে যোগাযোগ করুন।');
            }
        }

        return [$branch, $allBranches, $isAdmin];
    }

    /**
     * Dashboard - Branch Director Overview
     */
    public function dashboard(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        // Cashbook metrics
        $todayIncome = BranchCashbook::where('branch_id', $branch->id)
            ->where('transaction_date', $today)
            ->where('transaction_type', 'income')
            ->sum('amount');

        $todayExpense = BranchCashbook::where('branch_id', $branch->id)
            ->where('transaction_date', $today)
            ->where('transaction_type', 'expense')
            ->sum('amount');

        $monthlyIncome = BranchCashbook::where('branch_id', $branch->id)
            ->where('transaction_date', 'like', "$thisMonth%")
            ->where('transaction_type', 'income')
            ->sum('amount');

        $monthlyExpense = BranchCashbook::where('branch_id', $branch->id)
            ->where('transaction_date', 'like', "$thisMonth%")
            ->where('transaction_type', 'expense')
            ->sum('amount');

        // Employees & Attendance
        $employeesCount = User::where('branch_id', $branch->id)->count();
        $presentToday = HrmAttendance::where('branch_id', $branch->id)
            ->where('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();
        $absentToday = max(0, $employeesCount - $presentToday);

        // Commissions
        $totalCommissionEarned = BranchCommission::where('branch_id', $branch->id)->sum('commission_amount');
        $pendingCommission = BranchCommission::where('branch_id', $branch->id)->where('status', 'pending')->sum('commission_amount');

        // Tasks & KPI
        $pendingTasks = BranchTask::where('branch_id', $branch->id)->where('status', 'pending')->count();
        $activeLeads = BranchBusinessLead::where('branch_id', $branch->id)->whereNotIn('stage', ['closed_won', 'closed_lost'])->count();
        $pendingComplaints = BranchComplaint::where('branch_id', $branch->id)->where('status', 'pending')->count();

        // Recent items
        $recentCashbooks = BranchCashbook::where('branch_id', $branch->id)->latest('transaction_date')->take(6)->get();
        $recentTasks = BranchTask::where('branch_id', $branch->id)->latest()->take(5)->get();
        $recentCommissions = BranchCommission::where('branch_id', $branch->id)->latest()->take(5)->get();
        $recentReports = BranchDailyReport::where('branch_id', $branch->id)->latest('report_date')->take(5)->get();

        $kpi = BranchKpiTarget::where('branch_id', $branch->id)->where('target_month', $thisMonth)->first();

        return view('admin.branch.dashboard', compact(
            'branch', 'allBranches', 'isAdmin',
            'todayIncome', 'todayExpense', 'monthlyIncome', 'monthlyExpense',
            'employeesCount', 'presentToday', 'absentToday',
            'totalCommissionEarned', 'pendingCommission',
            'pendingTasks', 'activeLeads', 'pendingComplaints',
            'recentCashbooks', 'recentTasks', 'recentCommissions', 'recentReports', 'kpi'
        ));
    }

    /**
     * A - Administration & Branch Profile
     */
    public function branchProfile(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);
        $manager = $branch->manager;
        return view('admin.branch.profile', compact('branch', 'allBranches', 'isAdmin', 'manager'));
    }

    public function updateBranchProfile(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'phone' => 'nullable|string|max:30',
            'emergency_phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string',
            'opening_time' => 'nullable|string|max:20',
            'closing_time' => 'nullable|string|max:20',
            'weekly_holiday' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:150',
            'bank_account_no' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'branch_name_bank' => 'nullable|string|max:100',
            'routing_no' => 'nullable|string|max:50',
        ]);

        $data = $request->only([
            'phone', 'emergency_phone', 'email', 'address',
            'opening_time', 'closing_time', 'weekly_holiday',
            'bank_account_name', 'bank_account_no', 'bank_name', 'branch_name_bank', 'routing_no'
        ]);

        if ($isAdmin && $request->filled('name')) {
            $data['name'] = $request->name;
            $data['code'] = $request->code;
            $data['petty_cash_limit'] = $request->petty_cash_limit;
        }

        $branch->update($data);

        return back()->with('success', 'ব্রাঞ্চ প্রোফাইল সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * B - Branch Operations & Tasks
     */
    public function tasks(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $tasks = BranchTask::where('branch_id', $branch->id)
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->priority, fn($q, $p) => $q->where('priority', $p))
            ->latest()
            ->paginate(15);

        $employees = User::where('branch_id', $branch->id)->get();

        return view('admin.branch.tasks', compact('branch', 'allBranches', 'isAdmin', 'tasks', 'employees'));
    }

    public function storeTask(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);

        BranchTask::create([
            'branch_id' => $branch->id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'নতুন অপারেশনাল টাস্ক সফলভাবে তৈরি করা হয়েছে।');
    }

    public function updateTaskStatus(Request $request, $id)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $task = BranchTask::where('branch_id', $branch->id)->findOrFail($id);
        $task->update([
            'status' => $request->status,
            'completion_notes' => $request->completion_notes,
        ]);

        return back()->with('success', 'টাস্ক স্ট্যাটাস আপডেট সম্পন্ন হয়েছে।');
    }

    /**
     * C - Commission Management
     */
    public function commissions(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $commissions = BranchCommission::where('branch_id', $branch->id)
            ->when($request->source_type, fn($q, $t) => $q->where('source_type', $t))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        $totalPaid = BranchCommission::where('branch_id', $branch->id)->where('status', 'paid')->sum('commission_amount');
        $totalApproved = BranchCommission::where('branch_id', $branch->id)->where('status', 'approved')->sum('commission_amount');
        $totalPending = BranchCommission::where('branch_id', $branch->id)->where('status', 'pending')->sum('commission_amount');

        return view('admin.branch.commissions', compact('branch', 'allBranches', 'isAdmin', 'commissions', 'totalPaid', 'totalApproved', 'totalPending'));
    }

    public function storeCommission(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'source_type' => 'required|in:product_sales,fmcg,property,membership,business,investment_referral,bonus',
            'reference_title' => 'required|string|max:255',
            'gross_amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $commAmount = ($request->gross_amount * $request->commission_rate) / 100;

        BranchCommission::create([
            'branch_id' => $branch->id,
            'director_id' => $branch->manager_id ?: Auth::id(),
            'source_type' => $request->source_type,
            'reference_no' => 'COMM-' . strtoupper(substr($request->source_type, 0, 4)) . '-' . date('YmdHis'),
            'reference_title' => $request->reference_title,
            'gross_amount' => $request->gross_amount,
            'commission_rate' => $request->commission_rate,
            'commission_amount' => $commAmount,
            'status' => $isAdmin ? 'approved' : 'pending',
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'কমিশন এন্ট্রি সফলভাবে সংরক্ষণ করা হয়েছে।');
    }

    /**
     * D - Daily Reporting
     */
    public function dailyReports(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $reports = BranchDailyReport::where('branch_id', $branch->id)
            ->latest('report_date')
            ->paginate(15);

        return view('admin.branch.daily_reports', compact('branch', 'allBranches', 'isAdmin', 'reports'));
    }

    public function createDailyReport(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $reportDate = $request->query('date') ?: date('Y-m-d');

        // Precalculate today's metrics
        $totalIncome = BranchCashbook::where('branch_id', $branch->id)
            ->where('transaction_date', $reportDate)
            ->where('transaction_type', 'income')
            ->sum('amount');

        $totalExpense = BranchCashbook::where('branch_id', $branch->id)
            ->where('transaction_date', $reportDate)
            ->where('transaction_type', 'expense')
            ->sum('amount');

        $netBalance = $totalIncome - $totalExpense;

        $presentEmployees = HrmAttendance::where('branch_id', $branch->id)
            ->where('date', $reportDate)
            ->whereIn('status', ['present', 'late'])
            ->count();

        $totalEmployees = User::where('branch_id', $branch->id)->count();
        $absentEmployees = max(0, $totalEmployees - $presentEmployees);

        $newCustomers = User::where('branch_id', $branch->id)
            ->whereDate('created_at', $reportDate)
            ->count();

        return view('admin.branch.daily_report_create', compact(
            'branch', 'allBranches', 'isAdmin', 'reportDate',
            'totalIncome', 'totalExpense', 'netBalance',
            'presentEmployees', 'absentEmployees', 'newCustomers'
        ));
    }

    public function storeDailyReport(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'report_date' => 'required|date',
            'total_sales' => 'nullable|numeric|min:0',
            'total_income' => 'nullable|numeric|min:0',
            'total_expense' => 'nullable|numeric|min:0',
            'cash_in_hand' => 'nullable|numeric|min:0',
            'daily_highlights' => 'nullable|string',
            'challenges_faced' => 'nullable|string',
            'tomorrow_plan' => 'nullable|string',
        ]);

        $netBalance = ($request->total_income ?: 0) - ($request->total_expense ?: 0);

        BranchDailyReport::updateOrCreate(
            [
                'branch_id' => $branch->id,
                'report_date' => $request->report_date,
            ],
            [
                'submitted_by' => Auth::id(),
                'total_sales' => $request->total_sales ?: 0,
                'total_income' => $request->total_income ?: 0,
                'total_expense' => $request->total_expense ?: 0,
                'net_balance' => $netBalance,
                'cash_in_hand' => $request->cash_in_hand ?: 0,
                'present_employees' => $request->present_employees ?: 0,
                'absent_employees' => $request->absent_employees ?: 0,
                'new_customers' => $request->new_customers ?: 0,
                'new_memberships' => $request->new_memberships ?: 0,
                'new_leads' => $request->new_leads ?: 0,
                'daily_highlights' => $request->daily_highlights,
                'challenges_faced' => $request->challenges_faced,
                'tomorrow_plan' => $request->tomorrow_plan,
                'status' => 'submitted',
            ]
        );

        return redirect()->route('admin.branch.daily_reports', ['branch_id' => $branch->id])
            ->with('success', 'প্রধান কার্যালয়ে আজকের দৈনিক রিপোর্ট সফলভাবে সাবমিট করা হয়েছে।');
    }

    public function showDailyReport($id)
    {
        $report = BranchDailyReport::with(['branch', 'submitter', 'reviewer'])->findOrFail($id);
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch(request());

        if (!$isAdmin && $report->branch_id !== $branch->id) {
            abort(403, 'Unauthorized access to another branch report.');
        }

        return view('admin.branch.daily_report_show', compact('report', 'branch', 'allBranches', 'isAdmin'));
    }

    /**
     * E & J - Employee Management & Job Responsibilities
     */
    public function employees(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $employees = User::where('branch_id', $branch->id)
            ->with('roles')
            ->latest()
            ->paginate(15);

        $roles = Role::whereIn('slug', ['employee', 'staff', 'manager', 'branch_director'])->get();

        return view('admin.branch.employees', compact('branch', 'allBranches', 'isAdmin', 'employees', 'roles'));
    }

    public function storeEmployee(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|min:6',
            'role_slug' => 'required|string',
            'remarks' => 'nullable|string', // Job responsibilities
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role_slug,
            'branch_id' => $branch->id,
            'status' => 'active',
            'remarks' => $request->remarks,
            'joining_date' => date('Y-m-d'),
        ]);

        $role = Role::where('slug', $request->role_slug)->first();
        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        return back()->with('success', 'নতুন কর্মকর্তা/কর্মচারী ব্রাঞ্চে সফলভাবে যুক্ত করা হয়েছে।');
    }

    /**
     * F - Financial Management & Cashbook
     */
    public function cashbook(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $query = BranchCashbook::where('branch_id', $branch->id);

        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        $cashbooks = $query->latest('transaction_date')->latest('id')->paginate(20);

        $totalIncome = BranchCashbook::where('branch_id', $branch->id)->where('transaction_type', 'income')->sum('amount');
        $totalExpense = BranchCashbook::where('branch_id', $branch->id)->where('transaction_type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('admin.branch.cashbook', compact('branch', 'allBranches', 'isAdmin', 'cashbooks', 'totalIncome', 'totalExpense', 'netBalance'));
    }

    public function storeIncome(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'transaction_date' => 'required|date',
            'category' => 'required|string|max:60',
            'amount' => 'required|numeric|min:1',
            'particulars' => 'required|string',
            'payment_method' => 'required|string',
            'received_by_or_paid_to' => 'nullable|string',
        ]);

        $voucherNo = 'INC-' . date('Ymd') . '-' . rand(1000, 9999);

        BranchCashbook::create([
            'branch_id' => $branch->id,
            'transaction_date' => $request->transaction_date,
            'transaction_type' => 'income',
            'category' => $request->category,
            'voucher_no' => $voucherNo,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'particulars' => $request->particulars,
            'received_by_or_paid_to' => $request->received_by_or_paid_to,
            'created_by' => Auth::id(),
            'approved_by' => Auth::id(),
        ]);

        // Update branch current cash balance
        $branch->increment('current_cash_balance', $request->amount);

        return back()->with('success', 'দৈনিক আয়ের এন্ট্রি সফলভাবে সম্পন্ন হয়েছে। ভাউচার নম্বর: ' . $voucherNo);
    }

    public function storeExpense(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'transaction_date' => 'required|date',
            'category' => 'required|string|max:60',
            'amount' => 'required|numeric|min:1',
            'particulars' => 'required|string',
            'payment_method' => 'required|string',
            'received_by_or_paid_to' => 'nullable|string',
        ]);

        $voucherNo = 'EXP-' . date('Ymd') . '-' . rand(1000, 9999);

        BranchCashbook::create([
            'branch_id' => $branch->id,
            'transaction_date' => $request->transaction_date,
            'transaction_type' => 'expense',
            'category' => $request->category,
            'voucher_no' => $voucherNo,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'particulars' => $request->particulars,
            'received_by_or_paid_to' => $request->received_by_or_paid_to,
            'created_by' => Auth::id(),
            'approved_by' => Auth::id(),
        ]);

        // Decrement branch cash balance
        $branch->decrement('current_cash_balance', $request->amount);

        return back()->with('success', 'দৈনিক ব্যয়ের এন্ট্রি সফলভাবে সম্পন্ন হয়েছে। ভাউচার নম্বর: ' . $voucherNo);
    }

    /**
     * H - HR, Attendance & Biometric
     */
    public function attendance(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $selectedDate = $request->query('date') ?: date('Y-m-d');
        $employees = User::where('branch_id', $branch->id)->get();

        $attendances = HrmAttendance::where('branch_id', $branch->id)
            ->where('date', $selectedDate)
            ->get()
            ->keyBy('employee_id');

        return view('admin.branch.attendance', compact('branch', 'allBranches', 'isAdmin', 'selectedDate', 'employees', 'attendances'));
    }

    public function storeAttendance(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $date = $request->date ?: date('Y-m-d');
        $attendanceData = $request->input('attendance', []);

        foreach ($attendanceData as $userId => $record) {
            if (!empty($record['status'])) {
                HrmAttendance::updateOrCreate(
                    [
                        'employee_id' => $userId,
                        'branch_id' => $branch->id,
                        'date' => $date,
                    ],
                    [
                        'in_time' => $record['in_time'] ?? null,
                        'out_time' => $record['out_time'] ?? null,
                        'status' => $record['status'],
                        'late_minutes' => $record['late_minutes'] ?? 0,
                        'notes' => $record['notes'] ?? null,
                    ]
                );
            }
        }

        return back()->with('success', $date . ' তারিখের কর্মচারীদের হাজিরা সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * I - Inventory & Assets Management
     */
    public function inventory(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $assets = BranchAsset::where('branch_id', $branch->id)
            ->when($request->category, fn($q, $c) => $q->where('category', $c))
            ->when($request->condition, fn($q, $cn) => $q->where('condition', $cn))
            ->latest()
            ->paginate(15);

        $employees = User::where('branch_id', $branch->id)->get();

        return view('admin.branch.inventory', compact('branch', 'allBranches', 'isAdmin', 'assets', 'employees'));
    }

    public function storeAsset(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:computer_electronics,furniture,stationary,appliances,vehicle,others',
            'quantity' => 'required|integer|min:1',
            'purchase_cost' => 'nullable|numeric|min:0',
            'condition' => 'required|in:good,fair,needs_repair,damaged,disposed',
            'location_in_office' => 'nullable|string',
        ]);

        $assetCode = 'AST-' . strtoupper($branch->code ?: 'BR') . '-' . rand(100, 999);

        BranchAsset::create([
            'branch_id' => $branch->id,
            'asset_code' => $assetCode,
            'name' => $request->name,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'purchase_cost' => $request->purchase_cost ?: 0,
            'purchase_date' => $request->purchase_date ?: date('Y-m-d'),
            'condition' => $request->condition,
            'assigned_to' => $request->assigned_to,
            'location_in_office' => $request->location_in_office,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'মালামাল/সম্পদ সফলভাবে যুক্ত করা হয়েছে। কোড: ' . $assetCode);
    }

    /**
     * G & K - Growth, KPI & Performance
     */
    public function kpiPerformance(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $currentMonth = date('Y-m');
        $targetMonth = $request->query('month') ?: $currentMonth;

        $kpi = BranchKpiTarget::firstOrCreate(
            ['branch_id' => $branch->id, 'target_month' => $targetMonth],
            [
                'sales_target' => 500000.00,
                'sales_achieved' => 0.00,
                'customer_target' => 50,
                'customer_achieved' => 0,
                'membership_target' => 20,
                'membership_achieved' => 0,
                'attendance_score' => 95.00,
                'reporting_score' => 95.00,
                'overall_score' => 90.00,
                'grade' => 'A',
            ]
        );

        $pastKpis = BranchKpiTarget::where('branch_id', $branch->id)
            ->orderBy('target_month', 'desc')
            ->take(12)
            ->get();

        return view('admin.branch.kpi', compact('branch', 'allBranches', 'isAdmin', 'targetMonth', 'kpi', 'pastKpis'));
    }

    /**
     * M - Membership Management
     */
    public function membership(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $members = User::where('branch_id', $branch->id)
            ->whereNotNull('membership_card_type')
            ->latest()
            ->paginate(15);

        return view('admin.branch.membership', compact('branch', 'allBranches', 'isAdmin', 'members'));
    }

    public function storeMembership(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'membership_card_type' => 'required|string',
            'nid_number' => 'nullable|string',
        ]);

        $cardNumber = 'JSM-' . strtoupper(substr($request->membership_card_type, 0, 3)) . '-' . rand(100000, 999999);
        $email = 'member_' . rand(1000, 9999) . '@jamunastock.com';

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $email,
            'password' => Hash::make('123456'),
            'role' => 'user',
            'branch_id' => $branch->id,
            'membership_card_type' => $request->membership_card_type,
            'nid_number' => $request->nid_number,
            'status' => 'active',
            'customer_id' => $cardNumber,
        ]);

        // Auto record 10% commission entry
        BranchCommission::create([
            'branch_id' => $branch->id,
            'director_id' => $branch->manager_id ?: Auth::id(),
            'source_type' => 'membership',
            'reference_no' => 'COMM-CARD-' . $cardNumber,
            'reference_title' => 'নতুন সদস্য নিবন্ধন: ' . $request->name . ' (' . $request->membership_card_type . ')',
            'gross_amount' => 10000.00,
            'commission_rate' => 10.00,
            'commission_amount' => 1000.00,
            'status' => 'approved',
            'notes' => 'কার্ড নম্বর: ' . $cardNumber,
        ]);

        return back()->with('success', 'নতুন মেম্বারশিপ কার্ড সফলভাবে ইস্যু করা হয়েছে। কার্ড নম্বর: ' . $cardNumber);
    }

    /**
     * N - New Business Development
     */
    public function businessLeads(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $leads = BranchBusinessLead::where('branch_id', $branch->id)
            ->latest()
            ->paginate(15);

        return view('admin.branch.business_leads', compact('branch', 'allBranches', 'isAdmin', 'leads'));
    }

    public function storeBusinessLead(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'lead_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'lead_type' => 'required|string',
            'estimated_value' => 'nullable|numeric|min:0',
        ]);

        BranchBusinessLead::create([
            'branch_id' => $branch->id,
            'created_by' => Auth::id(),
            'lead_name' => $request->lead_name,
            'organization_name' => $request->organization_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'lead_type' => $request->lead_type,
            'estimated_value' => $request->estimated_value ?: 0,
            'stage' => 'new_lead',
            'details' => $request->details,
        ]);

        return back()->with('success', 'নতুন ব্যবসায়িক সুযোগ/লিড সফলভাবে অন্তর্ভুক্ত করা হয়েছে।');
    }

    /**
     * P - Property Management & Inquiries
     */
    public function property(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $properties = \App\Models\Property::where('status', 'active')->latest()->paginate(12);

        return view('admin.branch.property', compact('branch', 'allBranches', 'isAdmin', 'properties'));
    }

    /**
     * S - Sales Management (FMCG & Products)
     */
    public function sales(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $salesCashbooks = BranchCashbook::where('branch_id', $branch->id)
            ->where('transaction_type', 'income')
            ->whereIn('category', ['product_sale', 'fmcg_sale'])
            ->latest('transaction_date')
            ->paginate(15);

        return view('admin.branch.sales', compact('branch', 'allBranches', 'isAdmin', 'salesCashbooks'));
    }

    /**
     * Z - Zero Tolerance & Complaints
     */
    public function complaints(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $complaints = BranchComplaint::where('branch_id', $branch->id)
            ->latest()
            ->paginate(15);

        return view('admin.branch.complaints', compact('branch', 'allBranches', 'isAdmin', 'complaints'));
    }

    public function storeComplaint(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $request->validate([
            'complainant_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'complaint_details' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
        ]);

        BranchComplaint::create([
            'branch_id' => $branch->id,
            'complainant_type' => $request->complainant_type ?: 'customer',
            'complainant_name' => $request->complainant_name,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'complaint_details' => $request->complaint_details,
            'severity' => $request->severity,
            'status' => 'pending',
        ]);

        return back()->with('success', 'অভিযোগটি জিরো টলারেন্স নীতিমালায় রেকর্ড করা হয়েছে এবং দ্রুত তদন্ত শুরু হবে।');
    }

    public function resolveComplaint(Request $request, $id)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $complaint = BranchComplaint::where('branch_id', $branch->id)->findOrFail($id);
        $complaint->update([
            'status' => 'resolved',
            'resolution_notes' => $request->resolution_notes,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'অভিযোগটি সফলভাবে সমাধান করা হয়েছে।');
    }

    /**
     * L - Legal, Compliance & Documents
     */
    public function documents(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        $documents = BranchDocument::where('branch_id', $branch->id)
            ->latest()
            ->paginate(15);

        return view('admin.branch.documents', compact('branch', 'allBranches', 'isAdmin', 'documents'));
    }

    /**
     * Complete A to Z Interactive Policy Document
     */
    public function policyAZ(Request $request)
    {
        [$branch, $allBranches, $isAdmin] = $this->resolveBranch($request);

        return view('admin.branch.policy_az', compact('branch', 'allBranches', 'isAdmin'));
    }
}

