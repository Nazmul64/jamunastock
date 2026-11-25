<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpVehicleTrip extends Model
{
    use HasFactory;

    protected $table = 'erp_vehicle_trips';

    protected $fillable = [
        'vehicle_id',
        'driver_name',
        'start_location',
        'destination',
        'purpose',
        'start_time',
        'end_time',
        'start_km',
        'end_km',
        'total_km',
        'fuel_used_liters',
        'trip_cost',
        'status',
        'notes',
    ];

    public function vehicle()
    {
        return $this->belongsTo(ErpVehicle::class, 'vehicle_id');
    }
}

