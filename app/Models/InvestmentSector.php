<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Investment\InvestmentPlan;
use App\Models\Investment\EnterpriseInvestment;
use App\Models\Investment\InvestmentInquiry;
use App\Models\Investment\InvestmentReceipt;
use App\Models\Investment\InvestmentLedger;

class InvestmentSector extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_no',
        'name_bn',
        'name_en',
        'slug',
        'code',
        'receipt_prefix',
        'investment_range',
        'min_amount',
        'max_amount',
        'duration',
        'icon',
        'image',
        'banner_image',
        'description',
        'profit_loss_policy',
        'features',
        'faq',
        'terms',
        'is_active',
        'order_num',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'order_num' => 'integer',
        'features' => 'array',
        'faq' => 'array',
    ];

    public function projects()
    {
        return $this->hasMany(InvestmentProject::class, 'sector_id');
    }

    public function plans()
    {
        return $this->hasMany(InvestmentPlan::class, 'sector_id')->orderBy('order_num');
    }

    public function investments()
    {
        return $this->hasMany(EnterpriseInvestment::class, 'sector_id');
    }

    public function inquiries()
    {
        return $this->hasMany(InvestmentInquiry::class, 'sector_id');
    }

    public function receipts()
    {
        return $this->hasMany(InvestmentReceipt::class, 'sector_id');
    }

    public function ledgers()
    {
        return $this->hasMany(InvestmentLedger::class, 'sector_id');
    }
}
