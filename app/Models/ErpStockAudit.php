<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpStockAudit extends Model
{
    use HasFactory;

    protected $table = 'erp_stock_audits';

    protected $fillable = [
        'audit_no',
        'store_id',
        'audit_date',
        'auditor_name',
        'total_items_checked',
        'total_variance_items',
        'total_variance_value',
        'status',
        'approved_by',
        'notes',
    ];

    public function store()
    {
        return $this->belongsTo(ErpStore::class, 'store_id');
    }
}

