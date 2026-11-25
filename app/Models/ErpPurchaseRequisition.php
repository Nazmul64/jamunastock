<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpPurchaseRequisition extends Model
{
    use HasFactory;

    protected $table = 'erp_purchase_requisitions';

    protected $fillable = [
        'pr_no',
        'requested_by',
        'department',
        'store_id',
        'priority',
        'purpose',
        'estimated_cost',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    public function statusBadge()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">অনুমোদনাধীন</span>',
            'approved' => '<span class="badge bg-success">অনুমোদিত (Approved)</span>',
            'rejected' => '<span class="badge bg-danger">প্রত্যাখ্যাত</span>',
            'po_created' => '<span class="badge bg-primary">PO সম্পন্ন</span>',
        ];
        return $badges[$this->status] ?? ucfirst($this->status);
    }
}

