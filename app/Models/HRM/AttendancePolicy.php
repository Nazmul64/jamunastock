<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancePolicy extends Model
{
    use HasFactory;

    protected $table = 'attendance_policies';
    protected $guarded = [];

    protected $casts = [
        'weekend_days' => 'array',
        'missing_punch_penalty' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function getActivePolicy()
    {
        return static::where('is_active', true)->first() ?: static::create([
            'name' => 'Standard Corporate Attendance Policy',
            'default_start_time' => '09:00:00',
            'default_end_time' => '18:00:00',
            'grace_period_minutes' => 10,
            'late_threshold_minutes' => 15,
            'early_leave_minutes' => 15,
            'half_day_hours' => 4.0,
            'full_day_hours' => 8.0,
            'overtime_min_minutes' => 30,
            'overtime_rate_multiplier' => 1.5,
            'weekend_days' => ['Friday'],
            'missing_punch_penalty' => true,
            'lock_attendance_after_days' => 5,
            'is_active' => true,
        ]);
    }
}
