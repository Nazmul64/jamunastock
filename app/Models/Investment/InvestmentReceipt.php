<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvestmentSector;
use App\Models\User;

class InvestmentReceipt extends Model
{
    use HasFactory;

    protected $table = 'investment_receipts';

    protected $fillable = [
        'receipt_number',
        'receipt_type',
        'investment_id',
        'customer_id',
        'sector_id',
        'profit_schedule_id',
        'amount',
        'amount_in_words',
        'payment_method',
        'transaction_ref',
        'received_by',
        'is_void',
        'void_reason',
        'voided_by',
        'voided_at',
        'print_count',
        'last_printed_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_void' => 'boolean',
        'voided_at' => 'datetime',
        'last_printed_at' => 'datetime',
        'print_count' => 'integer',
    ];

    public function investment()
    {
        return $this->belongsTo(EnterpriseInvestment::class, 'investment_id');
    }

    public function customer()
    {
        return $this->belongsTo(InvestmentCustomer::class, 'customer_id');
    }

    public function sector()
    {
        return $this->belongsTo(InvestmentSector::class, 'sector_id');
    }

    public function profitSchedule()
    {
        return $this->belongsTo(InvestmentProfitSchedule::class, 'profit_schedule_id');
    }

    public function voidedByUser()
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function logs()
    {
        return $this->hasMany(InvestmentReceiptLog::class, 'receipt_id')->orderByDesc('id');
    }
}
