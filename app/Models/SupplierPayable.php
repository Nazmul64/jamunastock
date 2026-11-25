<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayable extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function supply()
    {
        return $this->belongsTo(SupplierSupply::class, 'supplier_supply_id');
    }

    public function allocations()
    {
        return $this->hasMany(SupplierPaymentAllocation::class, 'supplier_payable_id');
    }

    public function recalculateStatus(): void
    {
        $paid = (float) $this->allocations()->sum('allocated_amount');
        $payable = (float) $this->payable_amount;
        $due = max(0, $payable - $paid);
        $status = 'unpaid';
        if ($payable > 0 && $due <= 0.01) {
            $status = 'paid';
            $due = 0;
        } elseif ($paid > 0) {
            $status = 'partial';
        }

        $this->update([
            'paid_amount' => $paid,
            'due_amount' => $due,
            'payment_status' => $status,
        ]);
    }
}
