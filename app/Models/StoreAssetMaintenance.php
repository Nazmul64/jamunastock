<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAssetMaintenance extends Model
{
    use HasFactory;

    protected $table = 'store_asset_maintenances';
    protected $guarded = [];

    protected $casts = [
        'service_date' => 'date',
        'next_service_due' => 'date',
        'cost' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(StoreAsset::class, 'asset_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning text-dark">অপেক্ষমাণ (Pending)</span>',
            'in_progress' => '<span class="badge bg-info text-dark">চলমান (In Progress)</span>',
            'completed' => '<span class="badge bg-success">সম্পন্ন (Completed)</span>',
            'cancelled' => '<span class="badge bg-danger">বাতিল (Cancelled)</span>',
            default => '<span class="badge bg-light text-dark">' . $this->status . '</span>',
        };
    }
}

