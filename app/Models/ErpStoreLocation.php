<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpStoreLocation extends Model
{
    use HasFactory;

    protected $table = 'erp_store_locations';

    protected $fillable = [
        'store_id',
        'floor',
        'room_number',
        'rack_code',
        'shelf_number',
        'box_bin',
        'description',
    ];

    public function store()
    {
        return $this->belongsTo(ErpStore::class, 'store_id');
    }

    public function fullLocationString()
    {
        $parts = [];
        if ($this->floor) $parts[] = $this->floor;
        if ($this->room_number) $parts[] = 'Room: ' . $this->room_number;
        if ($this->rack_code) $parts[] = 'Rack: ' . $this->rack_code;
        if ($this->shelf_number) $parts[] = 'Shelf: ' . $this->shelf_number;
        if ($this->box_bin) $parts[] = 'Bin: ' . $this->box_bin;
        return implode(' → ', $parts);
    }
}

