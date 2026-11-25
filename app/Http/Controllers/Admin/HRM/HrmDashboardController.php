<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendanceCorrection;
use App\Models\HRM\AttendanceEvent;
use App\Models\HRM\BiometricDevice;
use App\Models\HRM\HrmAnnouncement;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmBranch;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmLeaveRequest;
use App\Models\HRM\HrmPayroll;
use App\Models\User;
use Illuminate\Http\Request;

class HrmDashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $currentMonth = date('Y-m');

        // Employee Headcount
        $totalEmployees = HrmEmployeeProfile::count();
        $activeEmployees = HrmEmployeeProfile::where('status', 'active')->count();
        $probationEmployees = HrmEmployeeProfile::where('status', 'probation')->count();
        $resignedEmployees = HrmEmployeeProfile::whereIn('status', ['resigned', 'terminated'])->count();

        // Today Attendance Metrics
        $todayAttendances = HrmAttendance::where('date', $today)->get();
        $todayPresent = $todayAttendances->whereIn('status', ['present', 'late'])->count();
        $todayLate = $todayAttendances->where('status', 'late')->count();
        $todayHalfDay = $todayAttendances->where('status', 'half_day')->count();
        $todayAbsent = max(0, $activeEmployees - $todayPresent);
        $todayOnLeave = HrmLeaveRequest::where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();

        $todayOvertimeHours = round($todayAttendances->sum('overtime_minutes') / 60, 1);
        $todayWorkingHours = round($todayAttendances->sum('working_hours'), 1);

        // Attendance & Absenteeism Rates
        $attendanceRate = $activeEmployees > 0 ? round(($todayPresent / $activeEmployees) * 100, 1) : 0;
        $absenteeismRate = round(100 - $attendanceRate, 1);

        // Biometric Devices
        $totalDevices = BiometricDevice::count();
        $onlineDevices = BiometricDevice::where('status', 'online')->count();
        $offlineDevices = BiometricDevice::where('status', 'offline')->count();

        // Departmental Breakdown
        $departments = HrmDepartment::withCount('employees')->where('status', 'active')->get();

        // Pending Actions
        $pendingLeaves = HrmLeaveRequest::where('status', 'pending')->count();
        $pendingCorrections = AttendanceCorrection::where('status', 'pending')->count();
        $latestAnnouncements = HrmAnnouncement::where('status', 'active')->latest()->take(5)->get();
        $currentMonthPayroll = HrmPayroll::where('month_year', $currentMonth)->first();

        // Live Feed of Today's Attendances
        $recentAttendances = HrmAttendance::with(['user.hrmProfile.department', 'user.hrmProfile.designation', 'device'])
            ->where('date', $today)
            ->latest('updated_at')
            ->take(12)
            ->get();

        // Recent Raw Biometric Events
        $recentEvents = AttendanceEvent::with(['user', 'device'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.hrm.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'probationEmployees',
            'resignedEmployees',
            'todayPresent',
            'todayLate',
            'todayHalfDay',
            'todayAbsent',
            'todayOnLeave',
            'todayOvertimeHours',
            'todayWorkingHours',
            'attendanceRate',
            'absenteeismRate',
            'totalDevices',
            'onlineDevices',
            'offlineDevices',
            'departments',
            'pendingLeaves',
            'pendingCorrections',
            'latestAnnouncements',
            'currentMonthPayroll',
            'recentAttendances',
            'recentEvents',
            'today'
        ));
    }
}
