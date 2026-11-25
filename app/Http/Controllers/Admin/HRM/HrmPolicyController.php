<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendancePolicy;
use Illuminate\Http\Request;

class HrmPolicyController extends Controller
{
    public function index()
    {
        $policy = AttendancePolicy::getActivePolicy();
        return view('admin.hrm.attendance.policy', compact('policy'));
    }

    public function update(Request $request)
    {
        $policy = AttendancePolicy::getActivePolicy();

        $request->validate([
            'name' => 'required|string|max:100',
            'default_start_time' => 'required',
            'default_end_time' => 'required',
            'grace_period_minutes' => 'required|integer|min:0',
            'late_threshold_minutes' => 'required|integer|min:0',
            'early_leave_minutes' => 'required|integer|min:0',
            'half_day_hours' => 'required|numeric|min:1',
            'full_day_hours' => 'required|numeric|min:4',
            'overtime_min_minutes' => 'required|integer|min:0',
            'overtime_rate_multiplier' => 'required|numeric|min:1',
            'lock_attendance_after_days' => 'required|integer|min:1',
        ]);

        $policy->update([
            'name' => $request->name,
            'default_start_time' => $request->default_start_time,
            'default_end_time' => $request->default_end_time,
            'grace_period_minutes' => $request->grace_period_minutes,
            'late_threshold_minutes' => $request->late_threshold_minutes,
            'early_leave_minutes' => $request->early_leave_minutes,
            'half_day_hours' => $request->half_day_hours,
            'full_day_hours' => $request->full_day_hours,
            'overtime_min_minutes' => $request->overtime_min_minutes,
            'overtime_rate_multiplier' => $request->overtime_rate_multiplier,
            'weekend_days' => $request->weekend_days ?? ['Friday'],
            'missing_punch_penalty' => $request->has('missing_punch_penalty'),
            'lock_attendance_after_days' => $request->lock_attendance_after_days,
            'is_active' => true,
        ]);

        return back()->with('success', 'আন্তর্জাতিক উপস্থিতি ও কাজের পলিসি সফলভাবে সংরক্ষণ করা হয়েছে।');
    }
}
