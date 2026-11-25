<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmEmployeeProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'joining_date' => 'date',
        'probation_end_date' => 'date',
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(HrmDepartment::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(HrmDesignation::class, 'designation_id');
    }

    public function shift()
    {
        return $this->belongsTo(HrmShift::class, 'shift_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function biometrics()
    {
        return $this->hasMany(EmployeeBiometric::class, 'user_id', 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(HrmEmployeeDocument::class, 'user_id', 'user_id');
    }

    public function disciplinaryRecords()
    {
        return $this->hasMany(HrmDisciplinaryRecord::class, 'user_id', 'user_id');
    }

    public function assets()
    {
        return $this->hasMany(HrmCompanyAsset::class, 'assigned_user_id', 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(HrmAttendance::class, 'user_id', 'user_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(HrmLeaveRequest::class, 'user_id', 'user_id');
    }

    public function loans()
    {
        return $this->hasMany(HrmEmployeeLoan::class, 'user_id', 'user_id');
    }

    public function advances()
    {
        return $this->hasMany(HrmSalaryAdvance::class, 'user_id', 'user_id');
    }

    public function exitSettlement()
    {
        return $this->hasOne(HrmExitSettlement::class, 'user_id', 'user_id');
    }
}
