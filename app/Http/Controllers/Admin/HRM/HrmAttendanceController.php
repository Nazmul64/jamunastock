<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendanceCorrection;
use App\Models\HRM\AttendancePolicy;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmShift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrmAttendanceController extends Controller
{
    /**
     * Daily Attendance View & Summary
     */
    public function index(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $departmentId = $request->department_id;
        $statusFilter = $request->status;

        $query = HrmAttendance::with(['user.hrmProfile.department', 'user.hrmProfile.designation', 'device'])
            ->where('date', $date);

        if ($departmentId) {
            $query->whereHas('user.hrmProfile', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $attendances = $query->latest('check_in')->get();

        // Statistics
        $totalEmployees = HrmEmployeeProfile::where('status', 'active')->count();
        $presentCount = HrmAttendance::where('date', $date)->whereIn('status', ['present', 'late'])->count();
        $lateCount = HrmAttendance::where('date', $date)->where('status', 'late')->count();
        $halfDayCount = HrmAttendance::where('date', $date)->where('status', 'half_day')->count();
        $absentCount = max(0, $totalEmployees - $presentCount);
        $totalWorkingHours = $attendances->sum('working_hours');
        $totalOvertimeMinutes = $attendances->sum('overtime_minutes');

        $departments = HrmDepartment::where('status', 'active')->get();
        $employees = User::whereIn('role', ['employee', 'staff', 'manager'])
            ->with(['hrmProfile.department', 'hrmProfile.designation'])
            ->get();

        return view('admin.hrm.attendance.index', compact(
            'attendances',
            'employees',
            'departments',
            'date',
            'totalEmployees',
            'presentCount',
            'lateCount',
            'halfDayCount',
            'absentCount',
            'totalWorkingHours',
            'totalOvertimeMinutes'
        ));
    }

    /**
     * Monthly Attendance Register & Matrix Calendar (Matching Images 8, 9, 10)
     */
    public function monthly(Request $request)
    {
        $year = (int) ($request->year ?? date('Y'));
        $month = (int) ($request->month ?? date('m'));
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $employees = User::whereIn('role', ['employee', 'staff', 'manager'])
            ->with(['hrmProfile.department', 'hrmProfile.designation'])
            ->get();

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

        $attendanceLogs = HrmAttendance::whereBetween('date', [$startDate, $endDate])->get();

        // Map logs by [user_id][day]
        $matrix = [];
        foreach ($attendanceLogs as $log) {
            $day = (int) date('d', strtotime($log->date));
            $matrix[$log->user_id][$day] = $log;
        }

        $policy = AttendancePolicy::getActivePolicy();

        return view('admin.hrm.attendance.monthly', compact(
            'employees',
            'matrix',
            'year',
            'month',
            'daysInMonth',
            'policy'
        ));
    }

    /**
     * Store Manual Attendance Record
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,half_day,leave,holiday,weekend',
            'remarks' => 'nullable|string',
        ]);

        $workingHours = 0;
        $overtimeMinutes = 0;
        $lateMinutes = 0;

        if ($request->check_in && $request->check_out) {
            $t1 = strtotime($request->check_in);
            $t2 = strtotime($request->check_out);
            if ($t2 > $t1) {
                $workingHours = round(($t2 - $t1) / 3600, 2);
            }
        }

        // Shift calculation
        $profile = HrmEmployeeProfile::where('user_id', $request->user_id)->first();
        $shift = $profile ? $profile->shift : HrmShift::first();
        if ($shift && $request->check_in) {
            $shiftStart = strtotime($shift->start_time);
            $checkInTime = strtotime($request->check_in);
            if ($checkInTime > ($shiftStart + ($shift->grace_period_minutes * 60))) {
                $lateMinutes = (int) round(($checkInTime - $shiftStart) / 60);
            }

            if ($request->check_out) {
                $shiftEnd = strtotime($shift->end_time);
                $checkOutTime = strtotime($request->check_out);
                if ($checkOutTime > $shiftEnd) {
                    $overtimeMinutes = (int) round(($checkOutTime - $shiftEnd) / 60);
                }
            }
        }

        HrmAttendance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'date' => $request->date,
            ],
            [
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'working_hours' => $workingHours,
                'late_minutes' => $lateMinutes,
                'overtime_minutes' => $overtimeMinutes,
                'status' => $request->status,
                'attendance_status' => $request->status,
                'source' => 'manual',
                'approved_by' => Auth::id(),
                'remarks' => $request->remarks ?: 'Manual attendance recorded by admin',
                'ip_address' => $request->ip(),
            ]
        );

        return back()->with('success', 'ম্যানুয়াল উপস্থিতি সফলভাবে সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Attendance Correction Requests List (Section 23)
     */
    public function corrections()
    {
        $corrections = AttendanceCorrection::with(['user.hrmProfile.department', 'reviewer'])->latest()->paginate(20);
        return view('admin.hrm.attendance.corrections', compact('corrections'));
    }

    /**
     * Approve Attendance Correction Request
     */
    public function approveCorrection(Request $request, $id)
    {
        $correction = AttendanceCorrection::findOrFail($id);

        $workingHours = 0;
        if ($correction->requested_check_in && $correction->requested_check_out) {
            $t1 = strtotime($correction->requested_check_in);
            $t2 = strtotime($correction->requested_check_out);
            if ($t2 > $t1) {
                $workingHours = round(($t2 - $t1) / 3600, 2);
            }
        }

        // Update attendance record
        HrmAttendance::updateOrCreate(
            [
                'user_id' => $correction->user_id,
                'date' => $correction->date,
            ],
            [
                'check_in' => $correction->requested_check_in,
                'check_out' => $correction->requested_check_out,
                'working_hours' => $workingHours,
                'status' => 'present',
                'attendance_status' => 'present',
                'source' => 'manual',
                'approved_by' => Auth::id(),
                'remarks' => 'Correction Approved: ' . $correction->reason,
            ]
        );

        $correction->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'review_remarks' => $request->review_remarks ?? 'Approved by HR',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'উপস্থিতি সংশোধনের আবেদনটি অনুমোদিত হয়েছে এবং রেকর্ড আপডেট করা হয়েছে।');
    }

    /**
     * Reject Attendance Correction Request
     */
    public function rejectCorrection(Request $request, $id)
    {
        $correction = AttendanceCorrection::findOrFail($id);
        $correction->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'review_remarks' => $request->review_remarks ?? 'Rejected by HR',
            'reviewed_at' => now(),
        ]);

        return back()->with('error', 'উপস্থিতি সংশোধনের আবেদনটি বাতিল করা হয়েছে।');
    }
}
