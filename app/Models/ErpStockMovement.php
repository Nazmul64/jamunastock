<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpStockMovement extends Model
{
    use HasFactory;

    protected $table = 'erp_stock_movements';

    protected $fillable = [
        'item_id',
        'store_id',
        'movement_type',
        'quantity',
        'unit_price',
        'total_cost',
        'running_stock',
        'reference_no',
        'department',
        'employee_id',
        'notes',
        'created_by',
    ];

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_id');
    }

    public function store()
    {
        return $this->belongsTo(ErpStore::class, 'store_id');
    }

    public function typeBadge()
    {
        $types = [
            'opening' => '<span class="badge bg-secondary">প্রারম্ভিক (Opening)</span>',
            'purchase_in' => '<span class="badge bg-success">ক্রয় (Purchase In)</span>',
            'grn_in' => '<span class="badge bg-primary">জিআরএন রিসিভ (GRN)</span>',
            'issue_out' => '<span class="badge bg-danger">ইস্যু/বিতরণ (Issue Out)</span>',
            'return_in' => '<span class="badge bg-info text-white">ফেরত (Return In)</span>',
            'adjustment' => '<span class="badge bg-warning text-dark">সমন্বয় (Adjustment)</span>',
            'transfer_in' => '<span class="badge bg-teal text-white">ট্রান্সফার আগমন</span>',
            'transfer_out' => '<span class="badge bg-secondary">ট্রান্সফার বহির্গমন</span>',
            'damage_out' => '<span class="badge bg-dark">নষ্ট/ক্ষতিগ্রস্ত (Damage)</span>',
            'disposal_out' => '<span class="badge bg-danger">বাতিল/স্ক্র্যাপ (Disposal)</span>',
        ];
        return $types[$this->movement_type] ?? ucfirst($this->movement_type);
    }
}

