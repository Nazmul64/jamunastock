<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpAssetAssignment extends Model
{
    use HasFactory;

    protected $table = 'erp_asset_assignments';

    protected $fillable = [
        'asset_id',
        'employee_id',
        'employee_name',
        'department',
        'branch',
        'assignment_date',
        'expected_return_date',
        'actual_return_date',
        'initial_condition',
        'return_condition',
        'status',
        'handover_notes',
        'assigned_by',
    ];

    public function asset()
    {
        return $this->belongsTo(StoreAsset::class, 'asset_id');
    }
}

