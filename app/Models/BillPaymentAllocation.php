<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPaymentAllocation extends Model
{
    use HasFactory;

    protected $table = 'bill_payment_allocations';

    protected $fillable = [
        'voucher_id',
        'bill_id',
        'allocated_amount',
        'notes',
    ];

    public function voucher()
    {
        return $this->belongsTo(AccountingVoucher::class, 'voucher_id');
    }

    public function bill()
    {
        return $this->belongsTo(FinancialBill::class, 'bill_id');
    }
}

