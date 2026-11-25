<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpAssetDepreciation extends Model
{
    use HasFactory;

    protected $table = 'erp_asset_depreciations';

    protected $fillable = [
        'asset_id',
        'fiscal_year',
        'method',
        'purchase_cost',
        'salvage_value',
        'useful_life_years',
        'depreciation_rate',
        'annual_depreciation',
        'accumulated_depreciation',
        'current_book_value',
        'posted_to_gl',
        'voucher_ref',
        'calculated_at',
    ];

    public function asset()
    {
        return $this->belongsTo(StoreAsset::class, 'asset_id');
    }
}

