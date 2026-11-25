<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpItemCategory extends Model
{
    use HasFactory;

    protected $table = 'erp_item_categories';

    protected $fillable = [
        'code',
        'name_bn',
        'name_en',
        'icon',
        'is_asset',
        'description',
    ];

    public function items()
    {
        return $this->hasMany(ErpItem::class, 'category_id');
    }
}

