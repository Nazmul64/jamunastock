<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpMaintenanceWorkOrder extends Model
{
    use HasFactory;

    protected $table = 'erp_maintenance_work_orders';

    protected $fillable = [
        'work_order_no',
        'asset_id',
        'category',
        'problem_title',
        'problem_description',
        'priority',
        'service_provider',
        'provider_contact',
        'is_amc',
        'spare_parts_cost',
        'labor_cost',
        'other_cost',
        'total_cost',
        'start_date',
        'completion_date',
        'status',
        'preventive_interval_months',
        'next_due_date',
        'notes',
        'created_by',
    ];

    public function asset()
    {
        return $this->belongsTo(StoreAsset::class, 'asset_id');
    }

    public function statusBadge()
    {
        $badges = [
            'requested' => '<span class="badge bg-secondary rounded-pill px-3 py-1 fs-11">আবেদনকৃত (Requested)</span>',
            'approved' => '<span class="badge bg-primary rounded-pill px-3 py-1 fs-11">অনুমোদিত (Approved)</span>',
            'in_progress' => '<span class="badge bg-info text-white rounded-pill px-3 py-1 fs-11">চলমান (In Progress)</span>',
            'completed' => '<span class="badge bg-success rounded-pill px-3 py-1 fs-11">সম্পন্ন (Completed)</span>',
            'cancelled' => '<span class="badge bg-danger rounded-pill px-3 py-1 fs-11">বাতিল (Cancelled)</span>',
        ];
        return $badges[$this->status] ?? ucfirst($this->status);
    }
}

