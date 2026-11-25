<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierSupplyReceiptItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function receipt()
    {
        return $this->belongsTo(SupplierSupplyReceipt::class, 'receipt_id');
    }

    public function supplyItem()
    {
        return $this->belongsTo(SupplierSupplyItem::class, 'supply_item_id');
    }
}
