<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'code',
        'guard_name',
        'portal_prefix',
        'description',
        'category',
        'data_scope',
        'is_system',
        'status',
        'created_by',
    ];

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(
            RbacPermission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }

    /**
     * Users that have this role.
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_roles',
            'role_id',
            'user_id'
        );
    }

    /**
     * Check if role has a specific permission slug.
     */
    public function hasPermission($slug)
    {
        if ($this->slug === 'super_admin') {
            return true;
        }

        return $this->permissions->contains('slug', $slug);
    }

    /**
     * Sync permissions by permission slugs or IDs.
     */
    public function syncPermissions(array $permissionIdsOrSlugs)
    {
        $ids = [];
        foreach ($permissionIdsOrSlugs as $val) {
            if (is_numeric($val)) {
                $ids[] = (int) $val;
            } else {
                $perm = RbacPermission::where('slug', $val)->first();
                if ($perm) {
                    $ids[] = $perm->id;
                }
            }
        }

        $this->permissions()->sync(array_unique($ids));

        // Also sync role_has_permissions if table exists
        if (\Illuminate\Support\Facades\Schema::hasTable('role_has_permissions')) {
            \Illuminate\Support\Facades\DB::table('role_has_permissions')->where('role_id', $this->id)->delete();
            $inserts = [];
            foreach (array_unique($ids) as $pid) {
                $inserts[] = ['role_id' => $this->id, 'permission_id' => $pid];
            }
            if (!empty($inserts)) {
                \Illuminate\Support\Facades\DB::table('role_has_permissions')->insert($inserts);
            }
        }
    }
}

