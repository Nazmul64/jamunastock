<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';
    protected $guarded = [];

    public function agentPoints()
    {
        return $this->hasMany(AgentPoint::class, 'area_id');
    }

    public function cardHolders()
    {
        return $this->hasMany(User::class, 'area_id');
    }

    public function monthlyOrders()
    {
        return $this->hasMany(MonthlyBazaarOrder::class, 'area_id');
    }
}
