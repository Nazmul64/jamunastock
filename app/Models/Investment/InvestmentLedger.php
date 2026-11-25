<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvestmentSector;
use App\Models\User;

class InvestmentLedger extends Model
{
    use HasFactory;

    protected $table = 'investment_ledgers';

    protected $fillable = [
        'sector_id',
        'customer_id',
        'investment_id',
        'receipt_id',
        'transaction_type',
        'transaction_date',
        'receipt_number',
        'payment_method',
        'credit',
        'debit',
        'balance',
        'description',
        'entry_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'credit' => 'decimal:2',
        'debit' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function sector()
    {
        return $this->belongsTo(InvestmentSector::class, 'sector_id');
    }

    public function customer()
    {
        return $this->belongsTo(InvestmentCustomer::class, 'customer_id');
    }

    public function investment()
    {
        return $this->belongsTo(EnterpriseInvestment::class, 'investment_id');
    }

    public function receipt()
    {
        return $this->belongsTo(InvestmentReceipt::class, 'receipt_id');
    }

    public function entryUser()
    {
        return $this->belongsTo(User::class, 'entry_by');
    }
}
