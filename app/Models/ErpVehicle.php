<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpVehicle extends Model
{
    use HasFactory;

    protected $table = 'erp_vehicles';

    protected $fillable = [
        'asset_id',
        'reg_no',
        'vehicle_name',
        'vehicle_type',
        'brand',
        'model',
        'manufacturing_year',
        'chassis_no',
        'engine_no',
        'fuel_type',
        'driver_name',
        'driver_phone',
        'driver_license',
        'current_mileage',
        'fitness_expiry',
        'tax_token_expiry',
        'insurance_expiry',
        'route_permit_expiry',
        'status',
        'image',
    ];

    protected $dates = [
        'fitness_expiry',
        'tax_token_expiry',
        'insurance_expiry',
        'route_permit_expiry',
    ];

    public function trips()
    {
        return $this->hasMany(ErpVehicleTrip::class, 'vehicle_id');
    }

    public function fuelLogs()
    {
        return $this->hasMany(ErpVehicleFuelLog::class, 'vehicle_id');
    }

    public function statusBadge()
    {
        $badges = [
            'active' => '<span class="badge bg-success rounded-pill px-3 py-1 fs-11">সক্রিয় (Active)</span>',
            'in_trip' => '<span class="badge bg-primary rounded-pill px-3 py-1 fs-11">ট্রিপে চলমান (In Trip)</span>',
            'under_maintenance' => '<span class="badge bg-warning text-dark rounded-pill px-3 py-1 fs-11">মেরামতে (Under Maintenance)</span>',
            'retired' => '<span class="badge bg-secondary rounded-pill px-3 py-1 fs-11">অবসরপ্রাপ্ত (Retired)</span>',
        ];
        return $badges[$this->status] ?? ucfirst($this->status);
    }
}

