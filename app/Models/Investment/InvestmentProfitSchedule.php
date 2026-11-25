<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InvestmentProfitSchedule extends Model
{
    use HasFactory;

    protected $table = 'investment_profit_schedules';

    protected $fillable = [
        'investment_id',
        'installment_no',
        'period_label',
        'due_date',
        'expected_profit',
        'paid_amount',
        'due_amount',
        'status',
        'paid_at',
        'payment_method',
        'transaction_ref',
        'remarks',
        'approved_by',
    ];

    protected $casts = [
        'expected_profit' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'date',
    ];

    public function investment()
    {
        return $this->belongsTo(EnterpriseInvestment::class, 'investment_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
