<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpMaterialIssue extends Model
{
    use HasFactory;

    protected $table = 'erp_material_issues';

    protected $fillable = [
        'min_no',
        'issue_date',
        'store_id',
        'department',
        'employee_id',
        'employee_name',
        'purpose',
        'issue_type',
        'total_items',
        'total_value',
        'issued_by',
        'status',
        'notes',
    ];
}

