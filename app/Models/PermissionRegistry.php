<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRegistry extends Model
{
    use HasFactory;

    protected $table = 'permission_registries';

    protected $fillable = [
        'module_key',
        'module_name',
        'menu_key',
        'menu_name',
        'submenu_key',
        'submenu_name',
        'page_key',
        'action_key',
        'action_name',
        'permission_key',
        'route_name',
        'http_method',
        'description',
        'is_active',
        'is_retired',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_retired' => 'boolean',
    ];

    /**
     * Scope for active permissions only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_retired', false);
    }
}
