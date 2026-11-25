<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherEntry extends Model
{
    use HasFactory;

    protected $table = 'voucher_entries';

    protected $fillable = [
        'voucher_id',
        'account_id',
        'account_code',
        'account_name',
        'entry_type',
        'amount',
        'line_narration',
        'cost_center',
        'reference_no',
    ];

    public function voucher()
    {
        return $this->belongsTo(AccountingVoucher::class, 'voucher_id');
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }
}

