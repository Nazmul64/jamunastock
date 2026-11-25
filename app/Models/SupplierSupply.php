<?php

namespace App\Models;

use App\Models\HRM\HrmBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierSupply extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'approved_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'received_at' => 'datetime',
        'rejected_at' => 'datetime',
        'expected_delivery_date' => 'date',
    ];

    public const STATUS_META = [
        'draft' => ['label' => 'ড্রাফট (Draft)', 'badge' => 'secondary', 'icon' => 'fa-pencil'],
        'pending' => ['label' => 'অ্যাডমিন অনুমোদনের অপেক্ষায় (Pending)', 'badge' => 'warning', 'icon' => 'fa-clock-o'],
        'approved' => ['label' => 'অনুমোদিত (Approved)', 'badge' => 'info', 'icon' => 'fa-check'],
        'assigned' => ['label' => 'এজেন্ট ও ব্রাঞ্চ নির্ধারিত (Assigned)', 'badge' => 'primary', 'icon' => 'fa-map-marker'],
        'in_transit' => ['label' => 'পথে আছে (In Transit)', 'badge' => 'info', 'icon' => 'fa-truck'],
        'partially_received' => ['label' => 'আংশিক রিসিভ (Partially Received)', 'badge' => 'warning', 'icon' => 'fa-adjust'],
        'received' => ['label' => 'সম্পূর্ণ রিসিভড (Received)', 'badge' => 'success', 'icon' => 'fa-check-circle'],
        'discrepancy' => ['label' => 'পরিমাণ/অবস্থা অমিল (Discrepancy)', 'badge' => 'danger', 'icon' => 'fa-exclamation-triangle'],
        'rejected' => ['label' => 'প্রত্যাখ্যাত (Rejected)', 'badge' => 'danger', 'icon' => 'fa-times-circle'],
        'cancelled' => ['label' => 'বাতিল (Cancelled)', 'badge' => 'dark', 'icon' => 'fa-ban'],
    ];

    public function getStatusMetaAttribute(): array
    {
        return self::STATUS_META[$this->status] ?? ['label' => ucfirst((string) $this->status), 'badge' => 'secondary', 'icon' => 'fa-circle'];
    }

    public static function generateSupplyCode(): string
    {
        $year = date('Y');
        $prefix = "SUP-{$year}-";
        $last = self::where('supply_code', 'like', $prefix . '%')->orderByDesc('id')->value('supply_code');
        $nextNumber = 1;
        if ($last) {
            $parts = explode('-', $last);
            $nextNumber = ((int) end($parts)) + 1;
        } else {
            $nextNumber = self::count() + 1;
        }
        return $prefix . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function assignedAgentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'assigned_agent_point_id');
    }

    public function assignedBranch()
    {
        return $this->belongsTo(HrmBranch::class, 'assigned_branch_id');
    }

    public function items()
    {
        return $this->hasMany(SupplierSupplyItem::class, 'supplier_supply_id');
    }

    public function assignments()
    {
        return $this->hasMany(SupplierSupplyAssignment::class, 'supplier_supply_id')->orderByDesc('id');
    }

    public function activeAssignment()
    {
        return $this->hasOne(SupplierSupplyAssignment::class, 'supplier_supply_id')->where('status', 'active')->latestOfMany();
    }

    public function receipts()
    {
        return $this->hasMany(SupplierSupplyReceipt::class, 'supplier_supply_id')->orderByDesc('id');
    }

    public function statusHistories()
    {
        return $this->hasMany(SupplierSupplyStatusHistory::class, 'supplier_supply_id')->orderByDesc('id');
    }

    public function auditLogs()
    {
        return $this->hasMany(SupplierSupplyAuditLog::class, 'supplier_supply_id')->orderByDesc('id');
    }

    public function attachments()
    {
        return $this->hasMany(SupplierSupplyAttachment::class, 'supplier_supply_id');
    }

    public function getPayableAmountAttribute(): float
    {
        if (in_array($this->status, ['received', 'partially_received', 'discrepancy'], true)) {
            return (float) $this->actual_received_amount;
        }
        if ($this->status === 'approved' && $this->actual_received_amount == 0 && !$this->assigned_agent_id) {
            return (float) ($this->approved_amount ?? $this->total_amount);
        }
        return 0.0;
    }
}
