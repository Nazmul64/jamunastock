<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentDispatch extends Model
{
    use HasFactory;

    protected $table = 'agent_dispatches';
    protected $guarded = [];

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function items()
    {
        return $this->hasMany(AgentDispatchItem::class, 'dispatch_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
