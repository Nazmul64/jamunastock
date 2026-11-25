<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public static function notifyUser(?int $userId, ?string $targetRole, string $title, string $message, string $type = 'info', ?string $refType = null, ?int $refId = null, ?string $actionUrl = null, ?int $branchId = null): self
    {
        return self::create([
            'user_id' => $userId,
            'target_role' => $targetRole,
            'branch_id' => $branchId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'reference_type' => $refType,
            'reference_id' => $refId,
            'action_url' => $actionUrl,
        ]);
    }
}
