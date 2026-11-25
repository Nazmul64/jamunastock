<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InvestmentCustomer extends Model
{
    use HasFactory;

    protected $table = 'investment_customers';

    protected $fillable = [
        'user_id',
        'customer_code',
        'name',
        'father_name',
        'mother_name',
        'phone',
        'alt_phone',
        'email',
        'nid_number',
        'nid_front',
        'nid_back',
        'customer_photo',
        'present_address',
        'permanent_address',
        'occupation',
        'nominee_name',
        'nominee_phone',
        'nominee_nid',
        'nominee_relation',
        'nominee_photo',
        'status',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function investments()
    {
        return $this->hasMany(EnterpriseInvestment::class, 'customer_id');
    }

    public function inquiries()
    {
        return $this->hasMany(InvestmentInquiry::class, 'customer_id');
    }

    public function receipts()
    {
        return $this->hasMany(InvestmentReceipt::class, 'customer_id');
    }

    public function ledgers()
    {
        return $this->hasMany(InvestmentLedger::class, 'customer_id');
    }

    // Helper: Total Investment across all sectors
    public function getTotalInvestmentAttribute()
    {
        return $this->investments()->where('status', 'active')->sum('amount');
    }
}
