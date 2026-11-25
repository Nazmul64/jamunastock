<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricDevice extends Model
{
    use HasFactory;

    protected $table = 'biometric_devices';
    protected $guarded = [];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }

    public function events()
    {
        return $this->hasMany(AttendanceEvent::class, 'device_id');
    }

    public function biometrics()
    {
        return $this->hasMany(EmployeeBiometric::class, 'device_id');
    }
}
