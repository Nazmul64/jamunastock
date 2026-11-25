<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentDispatchItem extends Model
{
    use HasFactory;

    protected $table = 'agent_dispatch_items';
    protected $guarded = [];

    public function dispatch()
    {
        return $this->belongsTo(AgentDispatch::class, 'dispatch_id');
    }
}
