<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberDeliveryItem extends Model
{
    use HasFactory;

    protected $table = 'member_delivery_items';
    protected $guarded = [];

    public function delivery()
    {
        return $this->belongsTo(MemberDelivery::class, 'delivery_id');
    }
}
