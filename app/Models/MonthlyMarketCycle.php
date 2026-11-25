<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyMarketCycle extends Model
{
    use HasFactory;

    protected $table = 'monthly_market_cycles';
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(MonthlyBazaarOrder::class, 'cycle_id');
    }
}
