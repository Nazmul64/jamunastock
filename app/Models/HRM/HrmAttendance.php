<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmAttendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function device()
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isLate(): bool
    {
        return $this->status === 'late' || $this->late_minutes > 0;
    }

    public function hasOvertime(): bool
    {
        return $this->overtime_minutes > 0;
    }

    public function isMissingPunch(): bool
    {
        return ($this->check_in && !$this->check_out) || (!$this->check_in && $this->check_out);
    }
}
