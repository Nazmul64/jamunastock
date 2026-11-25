<?php

namespace App\Models\Branch;

use App\Models\HRM\HrmBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchKpiTarget extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'sales_target' => 'decimal:2',
        'sales_achieved' => 'decimal:2',
        'attendance_score' => 'decimal:2',
        'reporting_score' => 'decimal:2',
        'overall_score' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }
}
