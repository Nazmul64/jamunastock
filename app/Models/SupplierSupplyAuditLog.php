<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierSupplyAuditLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function supply()
    {
        return $this->belongsTo(SupplierSupply::class, 'supplier_supply_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
