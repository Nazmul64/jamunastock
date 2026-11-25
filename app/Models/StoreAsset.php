<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAsset extends Model
{
    use HasFactory;

    protected $table = 'store_assets';
    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date',
        'quantity' => 'decimal:2',
        'purchase_cost' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    public function maintenances()
    {
        return $this->hasMany(StoreAssetMaintenance::class, 'asset_id')->latest('service_date');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'furniture' => 'আসবাবপত্র (Furniture)',
            'vehicle' => 'গাড়ি / যানবাহন (Vehicle)',
            'stationery' => 'খাতাপত্র / স্টেশনারি (Stationery)',
            'electronics' => 'ইলেকট্রনিক্স ও আইটি (Electronics)',
            'equipment' => 'যন্ত্রপাতি ও টুলস (Equipment)',
            default => 'অন্যান্য মালামাল (Other)',
        };
    }

    public function getConditionBadgeAttribute()
    {
        return match($this->condition) {
            'new' => '<span class="badge bg-success">নতুন (New)</span>',
            'good' => '<span class="badge bg-primary">ভালো (Good)</span>',
            'fair' => '<span class="badge bg-info text-dark">মোটামুটি (Fair)</span>',
            'needs_repair' => '<span class="badge bg-warning text-dark">মেরামত প্রয়োজন (Needs Repair)</span>',
            'damaged' => '<span class="badge bg-danger">অকেজো (Damaged)</span>',
            'disposed' => '<span class="badge bg-secondary">বাতিলকৃত (Disposed)</span>',
            default => '<span class="badge bg-light text-dark">' . $this->condition . '</span>',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'in_use' => '<span class="badge bg-success">ব্যবহারাধীন (In Use)</span>',
            'in_store' => '<span class="badge bg-secondary">স্টোরে সংরক্ষিত (In Store)</span>',
            'under_maintenance' => '<span class="badge bg-warning text-dark">মেরামত চলছে (Under Maintenance)</span>',
            'scrapped' => '<span class="badge bg-dark">স্ক্র্যাপ (Scrapped)</span>',
            default => '<span class="badge bg-light text-dark">' . $this->status . '</span>',
        };
    }
}

