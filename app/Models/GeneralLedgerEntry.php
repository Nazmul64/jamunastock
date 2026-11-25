<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedgerEntry extends Model
{
    use HasFactory;

    protected $table = 'general_ledger_entries';

    protected $fillable = [
        'voucher_id',
        'voucher_no',
        'voucher_type',
        'transaction_date',
        'account_id',
        'account_code',
        'account_name',
        'debit',
        'credit',
        'running_balance',
        'narration',
        'reference_no',
    ];

    protected $dates = [
        'transaction_date',
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

