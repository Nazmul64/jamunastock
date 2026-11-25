<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_name',
        'property_code',
        'property_type_id',
        'property_type_name',
        'division',
        'district',
        'upazila',
        'mouza',
        'village',
        'dag_number',
        'khatian_number',
        'jl_number',
        'total_area',
        'measurement_unit',
        'purchase_price',
        'selling_price',
        'booking_amount',
        'current_valuation',
        'featured_image',
        'description',
        'features',
        'owner_name',
        'owner_phone',
        'status', // 'available', 'booked', 'sold'
        'is_featured',
        'created_by',
    ];

    protected $casts = [
        'total_area' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'booking_amount' => 'decimal:2',
        'current_valuation' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function inquiries()
    {
        return $this->hasMany(PropertyInquiry::class, 'property_id');
    }
}

