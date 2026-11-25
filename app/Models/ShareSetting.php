<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_shares',
        'price_per_share',
        'total_share_capital',
        'office_expense_per_share',
        'allocated_shares',
        'registration_fee',
    ];

    protected $casts = [
        'total_shares' => 'integer',
        'price_per_share' => 'decimal:2',
        'total_share_capital' => 'decimal:2',
        'office_expense_per_share' => 'decimal:2',
        'allocated_shares' => 'integer',
        'registration_fee' => 'decimal:2',
    ];
}

