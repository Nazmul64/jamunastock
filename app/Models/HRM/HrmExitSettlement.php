<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmExitSettlement extends Model
{
    use HasFactory;

    protected $table = 'hrm_exit_settlements';
    protected $guarded = [];

    protected $casts = [
        'notice_date' => 'date',
        'last_working_day' => 'date',
        'settled_at' => 'datetime',
        'handover_completed' => 'boolean',
        'assets_returned' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function settledBy()
    {
        return $this->belongsTo(User::class, 'settled_by');
    }
}
