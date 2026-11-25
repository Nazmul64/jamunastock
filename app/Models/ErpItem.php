<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpItem extends Model
{
    use HasFactory;

    protected $table = 'erp_items';

    protected $fillable = [
        'item_code',
        'name_bn',
        'name_en',
        'category_id',
        'category_code',
        'brand',
        'model_no',
        'unit',
        'item_type',
        'purchase_price',
        'average_cost',
        'current_stock',
        'min_stock',
        'max_stock',
        'reorder_level',
        'warranty_months',
        'barcode',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(ErpItemCategory::class, 'category_id');
    }

    public function movements()
    {
        return $this->hasMany(ErpStockMovement::class, 'item_id');
    }

    public function stockStatusBadge()
    {
        if ($this->current_stock <= 0) {
            return '<span class="badge bg-danger rounded-pill px-3 py-1 fw-bold fs-11">স্টক আউট (Out of Stock)</span>';
        }
        if ($this->current_stock <= $this->reorder_level) {
            return '<span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold fs-11">সীমিত স্টক (Low Stock)</span>';
        }
        return '<span class="badge bg-success rounded-pill px-3 py-1 fw-bold fs-11">পর্যাপ্ত স্টক (In Stock)</span>';
    }

    public function itemTypeBadge()
    {
        if ($this->item_type === 'fixed_asset') {
            return '<span class="badge bg-purple rounded-pill px-2 py-1 fs-11">স্থায়ী সম্পদ (Fixed Asset)</span>';
        }
        return '<span class="badge bg-info text-white rounded-pill px-2 py-1 fs-11">ব্যবহার্য সামগ্রী (Consumable)</span>';
    }
}

