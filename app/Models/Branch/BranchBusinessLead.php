<?php

namespace App\Models\Branch;

use App\Models\HRM\HrmBranch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchBusinessLead extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'estimated_value' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
