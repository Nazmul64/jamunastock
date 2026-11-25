<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Get avatar url with graceful fallback
     */
    public function avatarUrl()
    {
        if (!empty($this->avatar)) {
            if (file_exists(public_path('upload/user_images/' . $this->avatar))) {
                return asset('upload/user_images/' . $this->avatar);
            }
            if (file_exists(public_path('upload/agents/' . $this->avatar))) {
                return asset('upload/agents/' . $this->avatar);
            }
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?: 'Agent User') . '&background=0c5c2d&color=ffffff&size=128';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function supplierProfile()
    {
        return $this->hasOne(SupplierProfile::class, 'user_id');
    }

    public function supplies()
    {
        return $this->hasMany(SupplierSupply::class, 'supplier_id');
    }

    public function supplierPayments()
    {
        return $this->hasMany(SupplierPayment::class, 'supplier_id');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'user_id');
    }

    public function employeeInfo()
    {
        return $this->hasOne(EmployeeInfo::class, 'user_id');
    }

    public function hrmProfile()
    {
        return $this->hasOne(\App\Models\HRM\HrmEmployeeProfile::class, 'user_id');
    }

    public function biometrics()
    {
        return $this->hasMany(\App\Models\HRM\EmployeeBiometric::class, 'user_id');
    }

    public function hrmAttendances()
    {
        return $this->hasMany(\App\Models\HRM\HrmAttendance::class, 'user_id');
    }

    public function hrmDocuments()
    {
        return $this->hasMany(\App\Models\HRM\HrmEmployeeDocument::class, 'user_id');
    }

    public function hrmAssets()
    {
        return $this->hasMany(\App\Models\HRM\HrmCompanyAsset::class, 'assigned_user_id');
    }

    public function hrmDisciplinaryRecords()
    {
        return $this->hasMany(\App\Models\HRM\HrmDisciplinaryRecord::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function directPermissions()
    {
        return $this->belongsToMany(RbacPermission::class, 'user_direct_permissions', 'user_id', 'permission_id');
    }

    /**
     * Check if user has a specific role by slug or name.
     */
    public function hasRole($roleSlug)
    {
        if ($this->role === $roleSlug) {
            return true;
        }

        return $this->roles->contains('slug', $roleSlug) || $this->roles->contains('name', $roleSlug);
    }

    /**
     * Assign a role to this user.
     */
    public function assignRole($role)
    {
        $roleModel = is_numeric($role) ? Role::find($role) : (is_string($role) ? Role::where('slug', $role)->first() : $role);
        if ($roleModel) {
            $this->roles()->syncWithoutDetaching([$roleModel->id]);
            $this->update([
                'role' => $roleModel->slug,
                'role_id' => $roleModel->id,
            ]);
        }
    }

    /**
     * Get a human-readable role badge title.
     */
    public function roleTitle()
    {
        $firstRole = $this->roles()->first();
        if ($firstRole) {
            return $firstRole->name;
        }

        $map = [
            'super_admin' => 'সুপার এডমিন (Super Admin)',
            'admin' => 'সিস্টেম এডমিন (Admin)',
            'employee' => 'কর্মকর্তা-কর্মচারী (Employee)',
            'manager' => 'ম্যানেজার (Manager)',
            'hr_manager' => 'এইচআর ম্যানেজার (HR Manager)',
            'finance_admin' => 'ফাইন্যান্স এডমিন (Finance)',
            'investment_manager' => 'বিনিয়োগ ব্যবস্থাপক (Investment)',
            'store_manager' => 'স্টোর ও সম্পদ ম্যানেজার (Store Manager)',
            'agent' => 'এজেন্ট ও ডিলার (Agent)',
            'supplier' => 'সাপ্লায়ার / ভেন্ডর (Supplier)',
            'user' => 'গ্রাহক (Customer / Member)',
        ];

        return $map[$this->role] ?? ucfirst($this->role ?? 'User');
    }

    /**
     * Check if user is a supplier / vendor.
     */
    public function isSupplier()
    {
        return $this->role === 'supplier' || $this->hasRole('supplier');
    }

    /**
     * Check if user is an administrator or staff member who should see the administrative portal.
     * External users (suppliers, customers/users, agents, investors) are strictly excluded.
     */
    public function isStaffOrAdmin()
    {
        // 1. Explicitly exclude external non-staff roles
        if (in_array($this->role, ['supplier', 'user', 'agent', 'shareholder', 'investor'])) {
            return false;
        }

        // 2. Primary Admin/Staff roles
        if ($this->id == 1 || in_array($this->role, ['super_admin', 'admin', 'employee', 'manager', 'branch_director']) || in_array($this->role_id, [1, 2])) {
            return true;
        }

        // 3. Defined internal staff/management roles
        $staffRoles = [
            'super_admin', 'admin', 'manager', 'employee', 'staff', 'support_lead', 
            'support_agent', 'security_analyst', 'auditor', 'chairman', 
            'managing_director', 'ceo', 'finance_admin', 'investment_manager', 
            'hr_manager', 'marketing_manager', 'store_manager', 'attendance_lecturer', 
            'support_staff', 'branch_director'
        ];

        // Check if user has any assigned staff roles in roles relation
        if ($this->roles()->whereIn('slug', $staffRoles)->exists()) {
            return true;
        }

        // Check user role slug
        if (!empty($this->role) && in_array($this->role, $staffRoles)) {
            return true;
        }

        return false;
    }

    /**
     * Enterprise Level Check if user has permission for a specific module or action key.
     */
    public function hasPermission($permissionKey)
    {
        // 1. Super Admin has unrestricted full access
        if ($this->id == 1 || $this->role === 'super_admin' || $this->hasRole('super_admin')) {
            return true;
        }

        // System Admin also has broad access
        if ($this->role === 'admin' || $this->role_id == 1 || $this->role_id == 2) {
            return true;
        }

        // Comprehensive Aliases / Cross-mapping for seamless compatibility
        $aliases = [
            'dashboard' => ['dashboard.view'],
            'dashboard.view' => ['dashboard'],
            'store.view' => ['store_assets', 'store.assets.view'],
            'store_assets' => ['store.view', 'store.assets.view', 'store.assets.create', 'store.assets.edit', 'store.assets.delete', 'store.maintenances.manage', 'store.vouchers.manage', 'store.reports.view'],
            'corporate.view' => ['corporate', 'corporate.board.view', 'corporate.board.manage'],
            'corporate' => ['corporate.view', 'corporate.board.view', 'corporate.board.manage', 'corporate.profile.manage'],
            'investments.view' => ['investments', 'investments.sectors.view'],
            'investments' => ['investments.view', 'investments.sectors.manage', 'investments.projects.manage'],
            'shareholders.view' => ['shareholders', 'shareholders.manage'],
            'shareholders' => ['shareholders.view', 'shareholders.manage', 'shareholders.partnerships.manage', 'shareholders.settings.manage'],
            'finance.view' => ['finance', 'central_finance', 'bills_vouchers'],
            'finance' => ['finance.view', 'central_finance', 'central_finance.manage', 'bills_vouchers'],
            'central_finance' => ['finance.view', 'finance', 'central_finance.manage', 'bills_vouchers'],
            'bills_vouchers' => ['finance.view', 'finance', 'central_finance', 'central_finance.manage', 'bills_vouchers'],
            'notices.view' => ['notices', 'notices.manage'],
            'notices' => ['notices.view', 'notices.manage'],
            'live_chat' => ['chat.view', 'chat.reply'],
            'chat.view' => ['live_chat'],
            'contact_messages' => ['contact_messages.view'],
            'contact_messages.view' => ['contact_messages'],
            'our_packages' => ['packages.view', 'packages.manage'],
            'packages.view' => ['our_packages'],
            'card_benefits' => ['card_benefits.manage'],
            'card_numbers' => ['card_numbers.manage'],
            'careers' => ['careers.view', 'careers.manage'],
            'careers.view' => ['careers'],
            'stocks.view' => ['stocks', 'stocks.manage', 'stock_management'],
            'stocks' => ['stocks.view', 'stocks.manage', 'stock_management'],
            'stock_management' => ['stocks.view', 'stocks.manage', 'stocks'],
            'set_stock_price' => ['stocks.manage', 'stocks.view', 'stocks'],
            'stock_buy_sell' => ['stocks.manage', 'stocks.view', 'stocks'],
            'bazaar.view' => ['bazaar', 'monthly_bazaar'],
            'monthly_bazaar' => ['bazaar.view', 'bazaar.items.manage', 'bazaar.orders.manage', 'bazaar.agent_points.manage'],
            'user_financial' => ['users.view', 'users.manage', 'users'],
            'users.view' => ['users', 'users.manage', 'user_financial'],
            'users' => ['users.view', 'users.manage', 'user_financial'],
            'withdraw' => ['users.withdraw.manage', 'users.manage', 'user_financial'],
            'hrm.view' => ['hrm', 'hrm.manage', 'hrm_management'],
            'hrm' => ['hrm.view', 'hrm.manage', 'hrm_management'],
            'hrm_management' => ['hrm.view', 'hrm.manage', 'hrm', 'hrm.employees.manage', 'hrm.departments.manage', 'hrm.attendances.manage', 'hrm.leaves.manage', 'hrm.payroll.manage', 'hrm.reports.view'],
            'employees' => ['employees.view', 'employees.manage', 'employee_agent'],
            'employees.view' => ['employees', 'employee_agent'],
            'employee_agent' => ['employees.view', 'employees.manage', 'agents.manage', 'employees'],
            'settings.view' => ['settings', 'settings.manage'],
            'settings' => ['settings.view', 'settings.manage'],
            'suppliers.view' => ['suppliers', 'suppliers.manage', 'supplier_management'],
            'suppliers' => ['suppliers.view', 'suppliers.manage', 'supplier_management'],
            'supplier_management' => ['suppliers.view', 'suppliers.manage', 'suppliers'],
            'roles.view' => ['roles', 'roles.manage', 'roles_permissions'],
            'roles' => ['roles.view', 'roles.manage', 'roles_permissions'],
            'roles_permissions' => ['roles.view', 'roles.manage', 'roles', 'permissions.assign'],
        ];

        $keysToCheck = [$permissionKey];
        if (isset($aliases[$permissionKey])) {
            $keysToCheck = array_merge($keysToCheck, $aliases[$permissionKey]);
        }

        // 2. Retrieve all granted permission slugs for this user (with in-memory/cache optimization)
        $userRoles = $this->roles()->with('permissions')->get();

        // Fallback: If no pivot record found, try to look up the role from the 'role' column
        if ($userRoles->isEmpty() && !empty($this->role)) {
            $fallbackRole = Role::where('slug', $this->role)->with('permissions')->first();
            if ($fallbackRole) {
                $userRoles = collect([$fallbackRole]);
            }
        }

        $grantedSlugs = collect();
        foreach ($userRoles as $r) {
            foreach ($r->permissions as $p) {
                if (!empty($p->slug)) {
                    $grantedSlugs->push($p->slug);
                }
                if (!empty($p->code)) {
                    $grantedSlugs->push($p->code);
                }
            }
        }

        // Add direct user permissions if table exists
        if (\Illuminate\Support\Facades\Schema::hasTable('user_direct_permissions')) {
            $directPerms = $this->directPermissions()->get();
            foreach ($directPerms as $dp) {
                if (!empty($dp->slug)) {
                    $grantedSlugs->push($dp->slug);
                }
                if (!empty($dp->code)) {
                    $grantedSlugs->push($dp->code);
                }
            }
        }

        // Check exact match or alias match
        foreach ($keysToCheck as $k) {
            if ($grantedSlugs->contains($k)) {
                return true;
            }
        }

        // Dynamic Parent-Child Module Inheritance:
        // If checking a module parent (e.g. 'hrm', 'store', 'support', 'finance', 'investments', 'bazaar')
        // and user has ANY permission under that module prefix, return true so parent menu stays open!
        $modulePrefixes = [
            'hrm' => 'hrm.',
            'hrm_management' => 'hrm.',
            'store' => 'store.',
            'store_assets' => 'store.',
            'support' => 'support.',
            'live_chat' => 'support.',
            'finance' => 'finance.',
            'central_finance' => 'finance.',
            'bills_vouchers' => 'bills_vouchers.',
            'investments' => 'investments.',
            'shareholders' => 'shareholders.',
            'stocks' => 'stocks.',
            'bazaar' => 'bazaar.',
            'monthly_bazaar' => 'bazaar.',
            'corporate' => 'corporate.',
            'suppliers' => 'suppliers.',
            'supplier_management' => 'suppliers.',
            'users' => 'users.',
            'user_financial' => 'users.',
            'roles' => 'roles.',
            'roles_permissions' => 'roles.',
            'settings' => 'settings.',
            'gallery' => 'gallery.',
        ];

        if (isset($modulePrefixes[$permissionKey])) {
            $prefix = $modulePrefixes[$permissionKey];
            foreach ($grantedSlugs as $slug) {
                if (str_starts_with($slug, $prefix)) {
                    return true;
                }
            }
        }

        // Also check if user has a '.manage' permission that implies '.view'
        if (str_ends_with($permissionKey, '.view')) {
            $base = substr($permissionKey, 0, -5);
            if ($grantedSlugs->contains($base . '.manage')) {
                return true;
            }
        }

        // 4. Check Legacy Employee Permission table fallback
        $legacy = $this->permission;
        if ($legacy && !empty($legacy->permission_list)) {
            $list = json_decode($legacy->permission_list, true);
            if (!is_array($list)) {
                $list = array_map('trim', explode(',', $legacy->permission_list));
            }
            foreach ($keysToCheck as $k) {
                if (in_array($k, $list)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Agent Point relationship
     */
    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    /**
     * Area relationship
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * Check if user is an Agent
     */
    public function isAgent()
    {
        return $this->role === 'agent' || $this->user_type === 'agent' || $this->agent_type === 'agent' || $this->hasRole('agent');
    }

    /**
     * Get assigned Agent Point ID for this user (direct or managed)
     */
    public function getAssignedAgentPointId()
    {
        if (!empty($this->agent_point_id)) {
            return $this->agent_point_id;
        }

        $managed = AgentPoint::where('agent_user_id', $this->id)->first();
        if ($managed) {
            return $managed->id;
        }

        return null;
    }

    /**
     * Branch relationships & helpers for A-Z Branch Director System
     */
    public function branch()
    {
        return $this->belongsTo(\App\Models\HRM\HrmBranch::class, 'branch_id');
    }

    public function managedBranch()
    {
        return $this->hasOne(\App\Models\HRM\HrmBranch::class, 'manager_id');
    }

    public function isBranchDirector(): bool
    {
        return $this->role === 'branch_director' 
            || $this->hasRole('branch_director') 
            || (!empty($this->managedBranch));
    }

    public function isSuperAdmin(): bool
    {
        return $this->id == 1 || $this->role === 'super_admin' || $this->hasRole('super_admin');
    }

    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Get effective branch ID strictly enforcing data isolation:
     * - Branch Director gets ONLY their own branch ID
     * - Admins / Super Admin can switch via query parameter or default
     */
    public function getEffectiveBranchId(?int $requestedBranchId = null): ?int
    {
        if ($this->isAdmin()) {
            if ($requestedBranchId) {
                return $requestedBranchId;
            }
            if ($this->branch_id) {
                return $this->branch_id;
            }
            $first = \App\Models\HRM\HrmBranch::where('status', 'active')->first();
            return $first ? $first->id : null;
        }

        // Branch Director / Employee: Strict isolation to assigned branch
        return $this->branch_id ?: ($this->managedBranch?->id ?: null);
    }
}

