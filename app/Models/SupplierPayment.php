<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function generatePaymentNo(): string
    {
        $year = date('Y');
        $prefix = "PAY-{$year}-";
        $last = self::where('payment_no', 'like', $prefix . '%')->orderByDesc('id')->value('payment_no');
        $next = $last ? ((int) explode('-', $last)[2]) + 1 : (self::count() + 1);
        return $prefix . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function allocations()
    {
        return $this->hasMany(SupplierPaymentAllocation::class, 'supplier_payment_id');
    }
}
