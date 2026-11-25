<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\EmployeeInfo;
use App\Models\HRM\HrmBranch;
use App\Models\HRM\HrmCompanyAsset;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\HrmDesignation;
use App\Models\HRM\HrmDisciplinaryRecord;
use App\Models\HRM\HrmEmployeeDocument;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmExitSettlement;
use App\Models\HRM\HrmShift;
use App\Models\User;
use App\Services\BiometricDeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HrmEmployeeManagementController extends Controller
{
    /**
     * Employee Directory
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['employee', 'staff', 'manager'])
            ->with(['hrmProfile.branch', 'hrmProfile.department', 'hrmProfile.designation', 'hrmProfile.shift']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('hrmProfile', function ($pq) use ($search) {
                        $pq->where('employee_code', 'like', "%{$search}%")
                           ->orWhere('biometric_user_id', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('department_id')) {
            $query->whereHas('hrmProfile', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('branch_id')) {
            $query->whereHas('hrmProfile', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('hrmProfile', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $employees = $query->latest('id')->paginate(15);

        $branches = HrmBranch::where('status', 'active')->get();
        $departments = HrmDepartment::where('status', 'active')->get();
        $designations = HrmDesignation::where('status', 'active')->get();
        $shifts = HrmShift::where('status', 'active')->get();

        // Statistics
        $totalCount = HrmEmployeeProfile::count();
        $activeCount = HrmEmployeeProfile::where('status', 'active')->count();
        $probationCount = HrmEmployeeProfile::where('status', 'probation')->count();
        $resignedCount = HrmEmployeeProfile::whereIn('status', ['resigned', 'terminated'])->count();

        // Auto next employee code
        $nextNum = HrmEmployeeProfile::count() + 1;
        $autoCode = 'EMP-' . date('Y') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        return view('admin.hrm.employees.index', compact(
            'employees',
            'branches',
            'departments',
            'designations',
            'shifts',
            'totalCount',
            'activeCount',
            'probationCount',
            'resignedCount',
            'autoCode'
        ));
    }

    /**
     * Store New Employee (Multi-tab Comprehensive Registration)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6',
            'employee_code' => 'required|string|unique:hrm_employee_profiles,employee_code',
            'biometric_user_id' => 'nullable|string|unique:hrm_employee_profiles,biometric_user_id',
            'branch_id' => 'nullable|exists:hrm_branches,id',
            'department_id' => 'nullable|exists:hrm_departments,id',
            'designation_id' => 'nullable|exists:hrm_designations,id',
            'shift_id' => 'nullable|exists:hrm_shifts,id',
            'basic_salary' => 'nullable|numeric|min:0',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $avatarName = 'default-user.png';
        if ($request->hasFile('avatar')) {
            $uploadDir = public_path('upload/user_images');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $avatarName = time() . '_' . Str::random(8) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move($uploadDir, $avatarName);
        }

        DB::beginTransaction();
        try {
            $username = 'emp_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->name)) . rand(10, 99);
            while (User::where('username', $username)->exists()) {
                $username = 'emp_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->name)) . rand(100, 999);
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'employee',
                'user_type' => 'internal',
                'avatar' => $avatarName,
                'status' => 'active',
                'is_active' => 1,
            ]);

            $basic = $request->basic_salary ?? 0;
            $houseRent = $basic * 0.4;
            $medical = $basic * 0.1;
            $transport = $request->transport_allowance ?? 1000;
            $gross = $basic + $houseRent + $medical + $transport;

            $profile = HrmEmployeeProfile::create([
                'user_id' => $user->id,
                'employee_code' => $request->employee_code,
                'biometric_user_id' => $request->biometric_user_id ?: (string) $user->id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'shift_id' => $request->shift_id,
                'manager_id' => $request->manager_id,
                'joining_date' => $request->joining_date ?? date('Y-m-d'),
                'employment_type' => $request->employment_type ?? 'full_time',
                'status' => $request->status ?? 'active',
                'work_location' => $request->work_location ?? 'Head Office',
                'basic_salary' => $basic,
                'house_rent' => $houseRent,
                'medical_allowance' => $medical,
                'transport_allowance' => $transport,
                'gross_salary' => $gross,
                'nid' => $request->nid,
                'passport' => $request->passport,
                'father_name' => $request->father_name,
                'father_phone' => $request->father_phone,
                'mother_name' => $request->mother_name,
                'mother_phone' => $request->mother_phone,
                'spouse_name' => $request->spouse_name,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'blood_group' => $request->blood_group,
                'marital_status' => $request->marital_status,
                'nationality' => $request->nationality ?? 'Bangladeshi',
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender ?? 'male',
                'present_address' => $request->present_address,
                'permanent_address' => $request->permanent_address,
            ]);

            // Auto-enroll default biometric placeholder if device_user_id provided
            if ($request->biometric_user_id) {
                app(BiometricDeviceService::class)->enrollEmployee(
                    userId: $user->id,
                    deviceUserId: $request->biometric_user_id,
                    fingerIndex: 0
                );
            }

            DB::commit();

            return redirect()->route('admin.hrm.employees.show', $user->id)
                ->with('success', "✓ নতুন কর্মী '{$user->name}' (ID: {$request->employee_code}) সফলভাবে সিস্টেমে যুক্ত করা হয়েছে!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'কর্মী তৈরিতে ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * Employee 360-degree Profile Details
     */
    public function show($id)
    {
        $employee = User::with([
            'hrmProfile.branch',
            'hrmProfile.department',
            'hrmProfile.designation',
            'hrmProfile.shift',
            'hrmProfile.manager',
            'biometrics.device',
            'hrmDocuments',
            'hrmAssets',
            'hrmDisciplinaryRecords.issuer',
            'hrmAttendances' => function ($q) {
                $q->latest('date')->take(30);
            },
        ])->findOrFail($id);

        $branches = HrmBranch::where('status', 'active')->get();
        $departments = HrmDepartment::where('status', 'active')->get();
        $designations = HrmDesignation::where('status', 'active')->get();
        $shifts = HrmShift::where('status', 'active')->get();
        $managers = User::whereIn('role', ['employee', 'manager', 'admin'])->get();

        return view('admin.hrm.employees.show', compact(
            'employee',
            'branches',
            'departments',
            'designations',
            'shifts',
            'managers'
        ));
    }

    /**
     * Update Employee Profile
     */
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $profile = HrmEmployeeProfile::firstOrCreate(['user_id' => $user->id]);

        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'employee_code' => 'required|string|unique:hrm_employee_profiles,employee_code,' . $profile->id,
            'biometric_user_id' => 'nullable|string|unique:hrm_employee_profiles,biometric_user_id,' . $profile->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $avatarName = $user->avatar;
        if ($request->hasFile('avatar')) {
            $uploadDir = public_path('upload/user_images');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $avatarName = time() . '_' . Str::random(8) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move($uploadDir, $avatarName);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'avatar' => $avatarName ?: 'default-user.png',
        ]);

        $basic = $request->basic_salary ?? $profile->basic_salary;
        $houseRent = $basic * 0.4;
        $medical = $basic * 0.1;
        $transport = $request->transport_allowance ?? $profile->transport_allowance;
        $gross = $basic + $houseRent + $medical + $transport;

        $profile->update([
            'employee_code' => $request->employee_code,
            'biometric_user_id' => $request->biometric_user_id,
            'branch_id' => $request->branch_id,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'shift_id' => $request->shift_id,
            'manager_id' => $request->manager_id,
            'employment_type' => $request->employment_type ?? $profile->employment_type,
            'status' => $request->status ?? $profile->status,
            'work_location' => $request->work_location ?? $profile->work_location,
            'basic_salary' => $basic,
            'house_rent' => $houseRent,
            'medical_allowance' => $medical,
            'transport_allowance' => $transport,
            'gross_salary' => $gross,
            'nid' => $request->nid,
            'passport' => $request->passport,
            'father_name' => $request->father_name,
            'father_phone' => $request->father_phone,
            'mother_name' => $request->mother_name,
            'mother_phone' => $request->mother_phone,
            'spouse_name' => $request->spouse_name,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relation' => $request->emergency_contact_relation,
            'blood_group' => $request->blood_group,
            'marital_status' => $request->marital_status,
            'gender' => $request->gender ?? $profile->gender,
            'present_address' => $request->present_address,
            'permanent_address' => $request->permanent_address,
        ]);

        return back()->with('success', "কর্মী '{$user->name}' এর প্রোফাইল সফলভাবে আপডেট করা হয়েছে।");
    }

    /**
     * Upload Employee Document
     */
    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'document_type' => 'required|string',
            'title' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $uploadDir = public_path('upload/hrm_documents');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . Str::random(8) . '.' . $request->file('file')->getClientOriginalExtension();
        $request->file('file')->move($uploadDir, $fileName);

        HrmEmployeeDocument::create([
            'user_id' => $id,
            'document_type' => $request->document_type,
            'title' => $request->title,
            'file_path' => 'upload/hrm_documents/' . $fileName,
            'expiry_date' => $request->expiry_date,
            'is_verified' => true,
            'verified_by' => auth()->id(),
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'ডকুমেন্ট সফলভাবে আপলোড ও সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Store Warning / Disciplinary Record
     */
    public function storeDisciplinary(Request $request, $id)
    {
        $request->validate([
            'record_type' => 'required|string',
            'issue_date' => 'required|date',
            'description' => 'required|string',
        ]);

        HrmDisciplinaryRecord::create([
            'user_id' => $id,
            'record_type' => $request->record_type,
            'issue_date' => $request->issue_date,
            'description' => $request->description,
            'action_taken' => $request->action_taken,
            'issued_by' => auth()->id(),
            'status' => 'open',
        ]);

        return back()->with('success', 'শাস্তিমূলক নোটিশ/ওয়ার্নিং সফলভাবে রেকর্ড করা হয়েছে।');
    }

    /**
     * Process Employee Resignation & Final Settlement (F&F)
     */
    public function storeExit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'exit_type' => 'required|in:resignation,termination,retirement',
            'last_working_day' => 'required|date',
            'reason' => 'required|string',
        ]);

        $pendingSalary = $request->pending_salary ?? 0;
        $leaveEncashment = $request->leave_encashment ?? 0;
        $bonus = $request->bonus ?? 0;
        $overtime = $request->overtime_amount ?? 0;

        $advanceDeduction = $request->advance_deduction ?? 0;
        $loanDeduction = $request->loan_deduction ?? 0;
        $otherDeduction = $request->other_deductions ?? 0;

        $grossPayable = $pendingSalary + $leaveEncashment + $bonus + $overtime;
        $totalDeductions = $advanceDeduction + $loanDeduction + $otherDeduction;
        $netPayable = max(0, $grossPayable - $totalDeductions);

        HrmExitSettlement::updateOrCreate(
            ['user_id' => $user->id],
            [
                'exit_type' => $request->exit_type,
                'notice_date' => $request->notice_date ?? date('Y-m-d'),
                'last_working_day' => $request->last_working_day,
                'reason' => $request->reason,
                'handover_completed' => $request->has('handover_completed'),
                'assets_returned' => $request->has('assets_returned'),
                'pending_salary' => $pendingSalary,
                'leave_encashment' => $leaveEncashment,
                'bonus' => $bonus,
                'overtime_amount' => $overtime,
                'advance_deduction' => $advanceDeduction,
                'loan_deduction' => $loanDeduction,
                'other_deductions' => $otherDeduction,
                'net_payable' => $netPayable,
                'status' => 'settled',
                'settled_at' => now(),
                'settled_by' => auth()->id(),
                'notes' => $request->notes,
            ]
        );

        // Update profile status
        if ($user->hrmProfile) {
            $user->hrmProfile->update(['status' => $request->exit_type]);
        }

        return back()->with('success', "কর্মী '{$user->name}' এর ফাইনাল সেটেলমেন্ট (F&F) সফলভাবে সম্পন্ন করা হয়েছে। মোট প্রদেয়: ৳ " . number_format($netPayable, 2));
    }
}
