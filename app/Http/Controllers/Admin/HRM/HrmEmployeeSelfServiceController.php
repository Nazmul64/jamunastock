<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendanceCorrection;
use App\Models\HRM\HrmAnnouncement;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmLeaveRequest;
use App\Models\HRM\HrmLeaveType;
use App\Models\HRM\HrmPayrollItem;
use App\Models\HRM\HrmSalaryAdvance;
use App\Services\BiometricDeviceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrmEmployeeSelfServiceController extends Controller
{
    protected BiometricDeviceService $biometricService;

    public function __construct(BiometricDeviceService $biometricService)
    {
        $this->biometricService = $biometricService;
    }

    /**
     * ESS Main Dashboard (Matching NeuralHR / Image 10 & 42)
     */
    public function dashboard()
    {
        $user = Auth::user();
        $today = date('Y-m-d');
        $month = date('m');
        $year = date('Y');

        $profile = HrmEmployeeProfile::with(['branch', 'department', 'designation', 'shift'])
            ->firstOrCreate(['user_id' => $user->id], [
                'employee_code' => 'EMP-' . date('Y') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'status' => 'active',
            ]);

        $todayAttendance = HrmAttendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // Monthly stats
        $monthStart = "{$year}-{$month}-01";
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

        $monthAttendances = HrmAttendance::where('user_id', $user->id)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $presentDays = $monthAttendances->whereIn('status', ['present', 'late'])->count();
        $lateDays = $monthAttendances->where('status', 'late')->count();
        $totalWorkingHours = $monthAttendances->sum('working_hours');
        $totalOvertime = $monthAttendances->sum('overtime_minutes');

        $leaveTypes = HrmLeaveType::where('status', 'active')->get();
        $leaveRequests = HrmLeaveRequest::where('user_id', $user->id)->latest()->take(5)->get();
        $announcements = HrmAnnouncement::where('status', 'active')->latest()->take(4)->get();
        $corrections = AttendanceCorrection::where('user_id', $user->id)->latest()->take(5)->get();
        $recentPayslips = HrmPayrollItem::where('user_id', $user->id)->latest()->take(3)->get();

        return view('admin.hrm.ess.dashboard', compact(
            'user',
            'profile',
            'todayAttendance',
            'presentDays',
            'lateDays',
            'totalWorkingHours',
            'totalOvertime',
            'leaveTypes',
            'leaveRequests',
            'announcements',
            'corrections',
            'recentPayslips'
        ));
    }

    /**
     * Live Web Punch In / Punch Out Action (Triggered by Button in Portal)
     */
    public function webPunch(Request $request)
    {
        $user = Auth::user();
        $profile = $user->hrmProfile;
        $deviceUserId = ($profile && $profile->biometric_user_id) ? $profile->biometric_user_id : (string) $user->id;

        $now = now();
        $rawRef = "WEB_PUNCH_U{$user->id}_" . $now->format('Ymd_His');

        $result = $this->biometricService->processAttendanceEvent(
            deviceUserId: $deviceUserId,
            eventTime: $now,
            eventType: null, // auto-detect IN or OUT
            verificationMethod: 'web_punch',
            source: 'web_portal',
            deviceId: null,
            rawRef: $rawRef
        );

        if ($result['status'] === 'success') {
            $typeText = $result['event_type'] === 'IN' ? 'চেক-ইন (IN)' : 'চেক-আউট (OUT)';
            return back()->with('success', "✓ সফলভাবে {$typeText} সম্পন্ন হয়েছে! সময়: {$result['time']}");
        } elseif ($result['status'] === 'ignored_duplicate') {
            return back()->with('error', 'অল্প সময়ের মধ্যে একাধিকবার পাঞ্চ করার কারণে এই এন্ট্রিটি ইগনোর করা হয়েছে।');
        }

        return back()->with('error', 'পাঞ্চ প্রক্রিয়াকরণে ত্রুটি: ' . ($result['message'] ?? 'Unknown error'));
    }

    /**
     * Submit Missed Punch Correction Request
     */
    public function storeCorrection(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'requested_check_in' => 'required',
            'requested_check_out' => 'nullable',
            'reason' => 'required|string|max:500',
        ]);

        AttendanceCorrection::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'requested_check_in' => $request->requested_check_in,
            'requested_check_out' => $request->requested_check_out,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'উপস্থিতি সংশোধনের আবেদন সফলভাবে সাবমিট করা হয়েছে। এটি এইচআর অনুমোদনের পর আপডেট হবে।');
    }

    /**
     * Submit Leave Application
     */
    public function storeLeave(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:hrm_leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $d1 = strtotime($request->start_date);
        $d2 = strtotime($request->end_date);
        $totalDays = max(1, round(($d2 - $d1) / 86400) + 1);

        HrmLeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', "ছুটির আবেদন ({$totalDays} দিন) সফলভাবে সাবমিট করা হয়েছে।");
    }

    /**
     * Submit Salary Advance Request
     */
    public function storeAdvance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500',
            'reason' => 'required|string|max:500',
            'deduction_month' => 'required|string',
        ]);

        HrmSalaryAdvance::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'reason' => $request->reason,
            'deduction_month' => $request->deduction_month,
            'status' => 'pending',
        ]);

        return back()->with('success', 'বেতন অগ্রিমের আবেদন সফলভাবে সাবমিট করা হয়েছে।');
    }
}
