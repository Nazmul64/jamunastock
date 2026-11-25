<?php

namespace App\Models\Investment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InvestmentReceiptLog extends Model
{
    use HasFactory;

    protected $table = 'investment_receipt_logs';

    protected $fillable = [
        'receipt_id',
        'action',
        'user_id',
        'ip_address',
        'details',
    ];

    public function receipt()
    {
        return $this->belongsTo(InvestmentReceipt::class, 'receipt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
