<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RbacPermission extends Model
{
    use HasFactory;

    protected $table = 'rbac_permissions';

    protected $fillable = [
        'name',
        'slug',
        'code',
        'module',
        'action',
        'guard_name',
        'description',
    ];

    /**
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id'
        );
    }
}

