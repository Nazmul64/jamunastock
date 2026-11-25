<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvestmentSector;
use App\Models\User;

class EnterpriseInvestment extends Model
{
    use HasFactory;

    protected $table = 'enterprise_investments';

    protected $fillable = [
        'investment_code',
        'agreement_number',
        'customer_id',
        'sector_id',
        'plan_id',
        'inquiry_id',
        'amount',
        'duration_years',
        'start_date',
        'maturity_date',
        'profit_type',
        'profit_rate',
        'profit_frequency',
        'expected_profit_amount',
        'total_expected_return',
        'principal_return_type',
        'agreement_file',
        'terms_conditions',
        'payment_method',
        'payment_status',
        'status',
        'paid_profit_total',
        'principal_refunded_total',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'profit_rate' => 'decimal:2',
        'expected_profit_amount' => 'decimal:2',
        'total_expected_return' => 'decimal:2',
        'paid_profit_total' => 'decimal:2',
        'principal_refunded_total' => 'decimal:2',
        'start_date' => 'date',
        'maturity_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(InvestmentCustomer::class, 'customer_id');
    }

    public function sector()
    {
        return $this->belongsTo(InvestmentSector::class, 'sector_id');
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'plan_id');
    }

    public function inquiry()
    {
        return $this->belongsTo(InvestmentInquiry::class, 'inquiry_id');
    }

    public function profitSchedules()
    {
        return $this->hasMany(InvestmentProfitSchedule::class, 'investment_id')->orderBy('installment_no');
    }

    public function receipts()
    {
        return $this->hasMany(InvestmentReceipt::class, 'investment_id')->orderByDesc('id');
    }

    public function settlements()
    {
        return $this->hasMany(InvestmentSettlement::class, 'investment_id');
    }

    public function ledgers()
    {
        return $this->hasMany(InvestmentLedger::class, 'investment_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper: Outstanding Profit Due
    public function getProfitDueAttribute()
    {
        return max(0, $this->expected_profit_amount - $this->paid_profit_total);
    }

    // Helper: Outstanding Principal Due
    public function getPrincipalDueAttribute()
    {
        return max(0, $this->amount - $this->principal_refunded_total);
    }
}
