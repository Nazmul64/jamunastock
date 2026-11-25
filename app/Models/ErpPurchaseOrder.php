<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpPurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'erp_purchase_orders';

    protected $fillable = [
        'po_no',
        'pr_id',
        'supplier_name',
        'supplier_contact',
        'store_id',
        'order_date',
        'delivery_date',
        'subtotal',
        'tax_vat',
        'discount',
        'grand_total',
        'payment_terms',
        'status',
        'approved_by',
        'notes',
    ];
}

