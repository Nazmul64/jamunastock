<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvestmentSector;
use App\Models\User;

class InvestmentSettlement extends Model
{
    use HasFactory;

    protected $table = 'investment_settlements';

    protected $fillable = [
        'investment_id',
        'customer_id',
        'sector_id',
        'settlement_type',
        'settlement_date',
        'original_amount',
        'total_profit_paid',
        'penalty_or_deduction',
        'net_refund_amount',
        'deduction_reason',
        'status',
        'approved_by',
        'approved_at',
        'receipt_id',
        'notes',
    ];

    protected $casts = [
        'settlement_date' => 'date',
        'original_amount' => 'decimal:2',
        'total_profit_paid' => 'decimal:2',
        'penalty_or_deduction' => 'decimal:2',
        'net_refund_amount' => 'decimal:2',
        'approved_at' => 'datetime',
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

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receipt()
    {
        return $this->belongsTo(InvestmentReceipt::class, 'receipt_id');
    }
}
