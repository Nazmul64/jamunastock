<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionAuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'permission_audit_logs';

    protected $fillable = [
        'performed_by',
        'performer_name',
        'target_type',
        'target_id',
        'target_name',
        'event_type',
        'details',
        'ip_address',
        'user_agent',
        'reason',
        'created_at',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
