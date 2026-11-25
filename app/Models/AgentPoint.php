<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPoint extends Model
{
    use HasFactory;

    protected $table = 'agent_points';
    protected $guarded = [];

    public function areaModel()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function agentUser()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    public function cardHolders()
    {
        return $this->hasMany(User::class, 'agent_point_id');
    }

    public function localStocks()
    {
        return $this->hasMany(AgentStock::class, 'agent_id', 'agent_user_id');
    }

    public function stockTransactions()
    {
        return $this->hasMany(AgentStockTransaction::class, 'agent_id', 'agent_user_id');
    }

    public function dispatches()
    {
        return $this->hasMany(AgentDispatch::class, 'agent_point_id');
    }

    public function memberDeliveries()
    {
        return $this->hasMany(MemberDelivery::class, 'agent_point_id');
    }

    public function monthlyRequests()
    {
        return $this->hasMany(MonthlyBazaarOrder::class, 'agent_point_id');
    }
}
