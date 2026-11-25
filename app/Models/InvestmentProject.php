<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_id',
        'project_name',
        'project_id_code',
        'target_budget',
        'min_investment',
        'max_investment',
        'total_raised',
        'investors_count',
        'duration',
        'progress_percentage',
        'description',
        'field_location',
        'expected_roi_policy',
        'banner_image',
        'status', // 'open', 'ongoing', 'completed', 'paused'
        'is_featured',
    ];

    protected $casts = [
        'target_budget' => 'decimal:2',
        'min_investment' => 'decimal:2',
        'max_investment' => 'decimal:2',
        'total_raised' => 'decimal:2',
        'investors_count' => 'integer',
        'progress_percentage' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function sector()
    {
        return $this->belongsTo(InvestmentSector::class, 'sector_id');
    }

    public function investments()
    {
        return $this->hasMany(UserInvestment::class, 'project_id');
    }
}

