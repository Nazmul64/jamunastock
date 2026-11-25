<?php

namespace App\Services;

use App\Models\SupplierSupply;
use App\Models\SupplierSupplyAuditLog;
use App\Models\SupplierSupplyStatusHistory;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SupplyWorkflowService
{
    public static function recordStatusChange(SupplierSupply $supply, ?string $fromStatus, string $toStatus, ?string $remarks = null): void
    {
        $user = Auth::user();
        SupplierSupplyStatusHistory::create([
            'supplier_supply_id' => $supply->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => $user ? $user->id : null,
            'changed_by_role' => $user ? ($user->role ?? 'user') : 'system',
            'remarks' => $remarks,
        ]);
    }

    public static function logAudit(?int $supplyId, string $action, ?array $oldValues = null, ?array $newValues = null, ?string $remarks = null): void
    {
        $user = Auth::user();
        SupplierSupplyAuditLog::create([
            'supplier_supply_id' => $supplyId,
            'action' => $action,
            'user_id' => $user ? $user->id : null,
            'user_role' => $user ? ($user->role ?? 'user') : 'system',
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'remarks' => $remarks,
        ]);
    }

    public static function notifyParty(?int $userId, ?string $role, string $title, string $message, string $type, SupplierSupply $supply, ?string $actionUrl = null, ?int $branchId = null): void
    {
        SystemNotification::notifyUser(
            $userId,
            $role,
            $title,
            $message,
            $type,
            'SupplierSupply',
            $supply->id,
            $actionUrl,
            $branchId
        );
    }
}
