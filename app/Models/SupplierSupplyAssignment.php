<?php

namespace App\Models;

use App\Models\HRM\HrmBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierSupplyAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supply()
    {
        return $this->belongsTo(SupplierSupply::class, 'supplier_supply_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }

    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
