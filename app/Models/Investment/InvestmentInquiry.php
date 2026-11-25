<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvestmentSector;
use App\Models\User;

class InvestmentInquiry extends Model
{
    use HasFactory;

    protected $table = 'investment_inquiries';

    protected $fillable = [
        'inquiry_code',
        'sector_id',
        'plan_id',
        'customer_id',
        'name',
        'phone',
        'email',
        'address',
        'proposed_amount',
        'duration_years',
        'preferred_plan',
        'message',
        'status',
        'notes',
        'assigned_to',
        'converted_investment_id',
        'converted_at',
    ];

    protected $casts = [
        'proposed_amount' => 'decimal:2',
        'duration_years' => 'integer',
        'converted_at' => 'datetime',
    ];

    public function sector()
    {
        return $this->belongsTo(InvestmentSector::class, 'sector_id');
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'plan_id');
    }

    public function customer()
    {
        return $this->belongsTo(InvestmentCustomer::class, 'customer_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function investment()
    {
        return $this->belongsTo(EnterpriseInvestment::class, 'converted_investment_id');
    }

    // Helper for status badge class
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'new' => 'bg-info text-white',
            'pending_contact' => 'bg-warning text-dark',
            'contacted' => 'bg-primary text-white',
            'interested' => 'bg-secondary text-white',
            'meeting_scheduled' => 'bg-indigo text-white',
            'meeting_completed' => 'bg-teal text-white',
            'agreement_pending' => 'bg-orange text-white',
            'converted' => 'bg-success text-white',
            'rejected' => 'bg-danger text-white',
            'cancelled' => 'bg-secondary text-white',
            default => 'bg-light text-dark',
        };
    }
}
