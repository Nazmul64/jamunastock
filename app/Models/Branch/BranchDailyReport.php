<?php

namespace App\Models\Branch;

use App\Models\HRM\HrmBranch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchDailyReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'report_date' => 'date',
        'total_sales' => 'decimal:2',
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'net_balance' => 'decimal:2',
        'cash_in_hand' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
