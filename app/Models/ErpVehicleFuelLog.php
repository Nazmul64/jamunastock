<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpVehicleFuelLog extends Model
{
    use HasFactory;

    protected $table = 'erp_vehicle_fuel_logs';

    protected $fillable = [
        'vehicle_id',
        'log_date',
        'fuel_station',
        'fuel_type',
        'quantity_liters',
        'rate_per_liter',
        'total_cost',
        'odometer_km',
        'slip_no',
        'driver_name',
        'payment_method',
    ];

    public function vehicle()
    {
        return $this->belongsTo(ErpVehicle::class, 'vehicle_id');
    }
}

