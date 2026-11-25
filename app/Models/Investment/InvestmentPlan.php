<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvestmentSector;

class InvestmentPlan extends Model
{
    use HasFactory;

    protected $table = 'investment_plans';

    protected $fillable = [
        'sector_id',
        'name',
        'duration_years',
        'profit_type',
        'profit_rate',
        'profit_frequency',
        'min_amount',
        'max_amount',
        'principal_return_type',
        'description',
        'is_active',
        'order_num',
    ];

    protected $casts = [
        'duration_years' => 'integer',
        'profit_rate' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'order_num' => 'integer',
    ];

    public function sector()
    {
        return $this->belongsTo(InvestmentSector::class, 'sector_id');
    }

    public function investments()
    {
        return $this->hasMany(EnterpriseInvestment::class, 'plan_id');
    }
}
