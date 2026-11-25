<?php

namespace App\Models\Branch;

use App\Models\HRM\HrmBranch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchAsset extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
