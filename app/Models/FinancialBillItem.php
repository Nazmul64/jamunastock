<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialBillItem extends Model
{
    use HasFactory;

    protected $table = 'financial_bill_items';

    protected $fillable = [
        'bill_id',
        'item_name',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'subtotal',
        'tax_percent',
        'tax_amount',
        'total_amount',
    ];

    public function bill()
    {
        return $this->belongsTo(FinancialBill::class, 'bill_id');
    }
}

