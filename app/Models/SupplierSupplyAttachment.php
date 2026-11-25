<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierSupplyAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supply()
    {
        return $this->belongsTo(SupplierSupply::class, 'supplier_supply_id');
    }
}
