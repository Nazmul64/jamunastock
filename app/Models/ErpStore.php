<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpStore extends Model
{
    use HasFactory;

    protected $table = 'erp_stores';

    protected $fillable = [
        'name',
        'code',
        'location',
        'manager_name',
        'contact',
        'store_type',
        'capacity',
        'status',
    ];

    public function locations()
    {
        return $this->hasMany(ErpStoreLocation::class, 'store_id');
    }

    public function typeBangla()
    {
        $types = [
            'main' => 'কেন্দ্রীয় প্রধান স্টোর (Main Store)',
            'general' => 'সাধারণ স্টোর (General)',
            'stationery' => 'স্টেশনারি স্টোর (Stationery)',
            'it' => 'আইটি ইকুইপমেন্ট স্টোর (IT Equipment)',
            'furniture' => 'আসবাবপত্র গুদাম (Furniture)',
            'electrical' => 'ইলেকট্রিক্যাল স্টোর (Electrical)',
            'vehicle_parts' => 'যানবাহন ও স্পেয়ার পার্টস (Vehicle Parts)',
            'maintenance' => 'মেরামত ও রক্ষণাবেক্ষণ (Maintenance)',
        ];
        return $types[$this->store_type] ?? ucfirst($this->store_type);
    }
}

