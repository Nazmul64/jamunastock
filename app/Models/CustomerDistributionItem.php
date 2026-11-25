<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDistributionItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function distribution()
    {
        return $this->belongsTo(CustomerDistribution::class, 'distribution_id');
    }
}
