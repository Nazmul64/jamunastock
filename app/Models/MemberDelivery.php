<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberDelivery extends Model
{
    use HasFactory;

    protected $table = 'member_deliveries';
    protected $guarded = [];

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function agentUser()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    public function cardHolder()
    {
        return $this->belongsTo(User::class, 'card_holder_id');
    }

    public function order()
    {
        return $this->belongsTo(MonthlyBazaarOrder::class, 'order_id');
    }

    public function items()
    {
        return $this->hasMany(MemberDeliveryItem::class, 'delivery_id');
    }
}
