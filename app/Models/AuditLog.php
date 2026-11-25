<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_id',
        'actor_name',
        'actor_role',
        'action',
        'subject_type',
        'subject_id',
        'before_state',
        'after_state',
        'reason',
        'ip_address',
        'user_agent',
        'request_id',
    ];

    protected $casts = [
        'before_state' => 'array',
        'after_state' => 'array',
    ];

    public static function log($action, $subjectType = null, $subjectId = null, $reason = null, $before = null, $after = null)
    {
        $user = auth()->user();
        return self::create([
            'actor_id' => $user ? $user->id : null,
            'actor_name' => $user ? $user->name : 'System/Guest',
            'actor_role' => $user ? ($user->role ?? 'user') : 'guest',
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'before_state' => $before,
            'after_state' => $after,
            'reason' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_id' => bin2hex(random_bytes(8)),
        ]);
    }
}

