<?php

namespace App\Models\HRM;

use App\Models\User;
use App\Models\Branch\BranchCommission;
use App\Models\Branch\BranchDailyReport;
use App\Models\Branch\BranchCashbook;
use App\Models\Branch\BranchTask;
use App\Models\Branch\BranchKpiTarget;
use App\Models\Branch\BranchAsset;
use App\Models\Branch\BranchBusinessLead;
use App\Models\Branch\BranchComplaint;
use App\Models\Branch\BranchDocument;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmBranch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function employeeProfiles()
    {
        return $this->hasMany(HrmEmployeeProfile::class, 'branch_id');
    }

    public function commissions()
    {
        return $this->hasMany(BranchCommission::class, 'branch_id');
    }

    public function dailyReports()
    {
        return $this->hasMany(BranchDailyReport::class, 'branch_id');
    }

    public function cashbooks()
    {
        return $this->hasMany(BranchCashbook::class, 'branch_id');
    }

    public function tasks()
    {
        return $this->hasMany(BranchTask::class, 'branch_id');
    }

    public function kpiTargets()
    {
        return $this->hasMany(BranchKpiTarget::class, 'branch_id');
    }

    public function assets()
    {
        return $this->hasMany(BranchAsset::class, 'branch_id');
    }

    public function leads()
    {
        return $this->hasMany(BranchBusinessLead::class, 'branch_id');
    }

    public function complaints()
    {
        return $this->hasMany(BranchComplaint::class, 'branch_id');
    }

    public function documents()
    {
        return $this->hasMany(BranchDocument::class, 'branch_id');
    }
}
