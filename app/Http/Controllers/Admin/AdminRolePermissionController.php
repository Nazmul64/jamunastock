<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RbacPermission;
use App\Models\PermissionRegistry;
use App\Models\PermissionAuditLog;
use App\Models\User;
use App\Models\HRM\HrmEmployeeProfile;
use App\Services\PermissionRegistryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminRolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 1. Display list of all roles.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('id', 'asc')->get();
        $totalPermissions = PermissionRegistry::active()->count() ?: RbacPermission::count();
        $totalUsers = User::count();
        $recentAudits = PermissionAuditLog::orderBy('id', 'desc')->take(10)->get();

        return view('admin.roles.index', compact('roles', 'totalPermissions', 'totalUsers', 'recentAudits'));
    }

    /**
     * 2. Store newly created role.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:roles,slug',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:50',
            'data_scope' => 'nullable|string|max:50',
        ], [
            'name.required' => 'রোলের নাম প্রদান করা আবশ্যক।',
            'slug.unique' => 'এই স্লাগ দিয়ে ইতিমধ্যে একটি রোল রয়েছে।',
        ]);

        $slug = $request->slug ? Str::slug($request->slug, '_') : Str::slug($request->name, '_');
        $code = 'ROLE_' . strtoupper($slug);

        $role = Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'code' => $code,
            'guard_name' => 'web',
            'category' => $request->category ?? 'custom',
            'data_scope' => $request->data_scope ?? 'all',
            'description' => $request->description,
            'is_system' => 0,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        PermissionRegistryService::logAudit(
            'role',
            $role->id,
            $role->name,
            'role_created',
            ['name' => $role->name, 'slug' => $role->slug, 'scope' => $role->data_scope],
            'New Role created from Admin Dashboard'
        );

        return redirect()->route('admin.roles.matrix', ['role_id' => $role->id])
            ->with('success', "নতুন রোল '{$role->name}' সফলভাবে তৈরি হয়েছে! এখন এর পারমিশন ট্রি কনফিগার করুন।");
    }

    /**
     * 3. Update an existing role.
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:50',
            'data_scope' => 'nullable|string|max:50',
        ]);

        $old = ['name' => $role->name, 'category' => $role->category, 'data_scope' => $role->data_scope];

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category ?? $role->category,
            'data_scope' => $request->data_scope ?? $role->data_scope,
        ]);

        PermissionRegistryService::logAudit(
            'role',
            $role->id,
            $role->name,
            'role_updated',
            ['old' => $old, 'new' => ['name' => $role->name, 'category' => $role->category, 'data_scope' => $role->data_scope]],
            'Role information modified'
        );

        return back()->with('success', "রোল '{$role->name}' এর তথ্য সফলভাবে আপডেট হয়েছে।");
    }

    /**
     * 4. Delete custom role.
     */
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_system || in_array($role->slug, ['super_admin', 'admin'])) {
            return back()->with('error', 'সিস্টেমের ডিফল্ট বা সুপার এডমিন রোল মুছে ফেলা যাবে না!');
        }

        PermissionRegistryService::logAudit(
            'role',
            $role->id,
            $role->name,
            'role_deleted',
            ['deleted_role_name' => $role->name, 'slug' => $role->slug],
            'Role removed by Admin'
        );

        // Detach relations
        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        PermissionRegistryService::clearPermissionCache();

        return back()->with('success', "রোল '{$role->name}' সফলভাবে মুছে ফেলা হয়েছে।");
    }

    /**
     * 5. Clone a role with all its permissions.
     */
    public function cloneRole($id)
    {
        $source = Role::with('permissions')->findOrFail($id);
        $newSlug = $source->slug . '_copy_' . time();
        $newName = $source->name . ' (কপি)';

        $cloned = Role::create([
            'name' => $newName,
            'slug' => $newSlug,
            'code' => 'ROLE_' . strtoupper($newSlug),
            'guard_name' => 'web',
            'category' => $source->category ?? 'custom',
            'data_scope' => $source->data_scope,
            'description' => "Cloned from {$source->name}",
            'is_system' => 0,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        $permIds = $source->permissions->pluck('id')->toArray();
        $cloned->permissions()->sync($permIds);

        PermissionRegistryService::logAudit(
            'role',
            $cloned->id,
            $cloned->name,
            'role_cloned',
            ['cloned_from_id' => $source->id, 'cloned_from_name' => $source->name, 'permissions_count' => count($permIds)],
            "Cloned from {$source->name}"
        );

        return redirect()->route('admin.roles.matrix', ['role_id' => $cloned->id])
            ->with('success', "রোল '{$source->name}' সফলভাবে ডুপ্লিকেট করা হয়েছে।");
    }

    /**
     * Toggle Role Active/Inactive Status.
     */
    public function toggleRoleStatus($id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_system || $role->slug === 'super_admin') {
            return back()->with('error', 'সুপার এডমিন রোলের স্ট্যাটাস পরিবর্তন করা সম্ভব নয়!');
        }

        $newStatus = ($role->status === 'active' || $role->status == 1) ? 'inactive' : 'active';
        $role->update(['status' => $newStatus]);

        PermissionRegistryService::logAudit(
            'role',
            $role->id,
            $role->name,
            'role_status_toggled',
            ['new_status' => $newStatus],
            "Status changed to {$newStatus}"
        );

        return back()->with('success', "রোল '{$role->name}' এর স্ট্যাটাস '{$newStatus}' এ পরিবর্তন করা হয়েছে।");
    }

    /**
     * Trigger Automatic Permission Registry Synchronization.
     */
    public function syncRegistry(Request $request)
    {
        $res = PermissionRegistryService::syncRegistry();

        if ($res['success']) {
            return back()->with('success', "✓ পারমিশন রেজিস্ট্রি সফলভাবে সিঙ্ক সম্পন্ন হয়েছে! সর্বমোট {$res['total_synced']} টি অ্যাকশন পারমিশন রেজিস্টার্ড আছে।");
        }

        return back()->with('error', "রেজিস্ট্রি সিঙ্ক করতে ত্রুটি: " . ($res['error'] ?? 'Unknown Error'));
    }

    /**
     * 6. Interactive Dynamic Permission Tree & Matrix.
     */
    public function matrix(Request $request)
    {
        $roles = Role::orderBy('id', 'asc')->get();
        $selectedRoleId = $request->role_id ?? ($roles->first()->id ?? null);
        $selectedRole = Role::with('permissions')->find($selectedRoleId);

        // Build Hierarchical Permission Tree (Module -> Menu -> Submenu -> Actions)
        $permissionTree = PermissionRegistryService::getHierarchicalTree($selectedRole);

        // Summary Counts
        $totalSystemActions = 0;
        $totalGrantedActions = 0;
        foreach ($permissionTree as $mod) {
            $totalSystemActions += $mod['total_count'];
            $totalGrantedActions += $mod['granted_count'];
        }

        // Recent Audit logs for this role
        $roleAudits = PermissionAuditLog::where('target_type', 'role')
            ->where('target_id', $selectedRoleId)
            ->latest('id')
            ->take(5)
            ->get();

        return view('admin.roles.matrix', compact(
            'roles',
            'selectedRole',
            'permissionTree',
            'totalSystemActions',
            'totalGrantedActions',
            'roleAudits'
        ));
    }

    /**
     * 7. Save Dynamic Permission Tree updates (Supports standard form & AJAX).
     */
    public function updateMatrix(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $submittedPerms = $request->input('permissions', []);

        // Resolve submitted values (can be slugs or permission IDs)
        $resolvedIds = [];
        foreach ($submittedPerms as $val) {
            if (is_numeric($val)) {
                $resolvedIds[] = (int) $val;
            } else {
                $p = RbacPermission::where('slug', $val)->orWhere('code', $val)->first();
                if ($p) {
                    $resolvedIds[] = $p->id;
                }
            }
        }
        $resolvedIds = array_unique($resolvedIds);

        $oldPermIds = $role->permissions->pluck('id')->toArray();

        // Transactional Sync
        DB::beginTransaction();
        try {
            $role->permissions()->sync($resolvedIds);

            // Clear cache
            PermissionRegistryService::clearPermissionCache();

            // Record granular audit log
            $added = array_diff($resolvedIds, $oldPermIds);
            $removed = array_diff($oldPermIds, $resolvedIds);

            PermissionRegistryService::logAudit(
                'role',
                $role->id,
                $role->name,
                'permissions_synced',
                [
                    'total_assigned' => count($resolvedIds),
                    'added_count' => count($added),
                    'removed_count' => count($removed),
                ],
                'Permissions updated from Permission Tree Matrix'
            );

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => "রোল '{$role->name}' এর পারমিশন সফলভাবে সংরক্ষণ করা হয়েছে!",
                    'granted_count' => count($resolvedIds),
                ]);
            }

            return back()->with('success', "✓ রোল '{$role->name}' এর জন্য " . count($resolvedIds) . " টি পারমিশন সফলভাবে সংরক্ষণ করা হয়েছে!");
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'পারমিশন সংরক্ষণে ত্রুটি: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'পারমিশন সংরক্ষণে ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * 8. User Role & Permission Assignment.
     */
    public function userAssignment(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('role', 'like', "%{$s}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest('id')->paginate(15);
        $roles = Role::where('status', 'active')->orderBy('name')->get();

        return view('admin.roles.user_assignment', compact('users', 'roles'));
    }

    /**
     * 9. Update a user's role and direct permissions.
     */
    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'data_scope' => 'nullable|string|max:50',
            'expires_at' => 'nullable|date',
        ]);

        $role = Role::findOrFail($request->role_id);
        $oldRole = $user->role;

        // Update user primary role & pivot with ABAC attributes
        $user->roles()->sync([
            $role->id => [
                'data_scope' => $request->data_scope ?? $role->data_scope,
                'expires_at' => $request->expires_at,
            ]
        ]);

        $user->update([
            'role' => $role->slug,
            'role_id' => $role->id,
            'data_scope' => $request->data_scope ?? $role->data_scope,
        ]);

        PermissionRegistryService::clearPermissionCache($user->id);

        PermissionRegistryService::logAudit(
            'user',
            $user->id,
            $user->name,
            'user_role_assigned',
            [
                'old_role' => $oldRole,
                'new_role' => $role->slug,
                'data_scope' => $user->data_scope,
                'expires_at' => $request->expires_at,
            ],
            'User role assigned by Admin'
        );

        return back()->with('success', "ব্যবহারকারী '{$user->name}' কে সফলভাবে '{$role->name}' রোল প্রদান করা হয়েছে!");
    }

    /**
     * Store new User / Staff with Name, Email, Password, Role and Data Scope.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'role_id' => 'required|exists:roles,id',
            'data_scope' => 'nullable|string|max:50',
        ], [
            'name.required' => 'ব্যবহারকারীর পুরো নাম আবশ্যক।',
            'email.required' => 'লগইন ইমেইল ঠিকানা আবশ্যক।',
            'email.unique' => 'এই ইমেইল দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট তৈরি করা আছে।',
            'password.required' => 'লগইন পাসওয়ার্ড আবশ্যক।',
            'password.min' => 'পাসওয়ার্ড ন্যূনতম ৬ অক্ষরের হতে হবে।',
            'phone.unique' => 'এই ফোন নম্বরটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
            'role_id.required' => 'ব্যবহারকারীর জন্য একটি রোল নির্ধারণ করা আবশ্যক।',
        ]);

        $role = Role::findOrFail($request->role_id);
        $cleanUsername = Str::slug($request->name, '') . rand(100, 999);

        // Ensure username is unique
        while (User::where('username', $cleanUsername)->exists()) {
            $cleanUsername = Str::slug($request->name, '') . rand(1000, 9999);
        }

        $user = User::create([
            'name' => $request->name,
            'first_name' => $request->name,
            'email' => $request->email,
            'username' => $cleanUsername,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $role->slug,
            'role_id' => $role->id,
            'user_type' => 'internal',
            'data_scope' => $request->data_scope ?? $role->data_scope,
            'status' => 'active',
            'is_active' => 1,
            'avatar' => 'default-user.png',
        ]);

        // Attach Role in user_roles pivot with ABAC scope
        $user->roles()->sync([
            $role->id => [
                'data_scope' => $user->data_scope,
            ]
        ]);

        // Automatically create HRM Employee profile if role is employee/hrm/manager/support
        HrmEmployeeProfile::firstOrCreate(['user_id' => $user->id], [
            'employee_code' => 'EMP-' . date('Y') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'biometric_user_id' => (string) $user->id,
            'status' => 'active',
            'employment_type' => 'full_time',
            'joining_date' => date('Y-m-d'),
        ]);

        PermissionRegistryService::logAudit(
            'user',
            $user->id,
            $user->name,
            'user_created',
            ['email' => $user->email, 'role' => $role->slug, 'data_scope' => $user->data_scope],
            'New Staff created with login credentials'
        );

        return back()->with('success', "✓ নতুন ব্যবহারকারী '{$user->name}' (ইমেইল: {$user->email}) সফলভাবে তৈরি হয়েছে এবং '{$role->name}' রোল বরাদ্দ করা হয়েছে!");
    }

    /**
     * Reset / Update User Password.
     */
    public function resetUserPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ], [
            'password.required' => 'নতুন পাসওয়ার্ড প্রদান আবশ্যক।',
            'password.min' => 'পাসওয়ার্ড ন্যূনতম ৬ অক্ষরের হতে হবে।',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        PermissionRegistryService::logAudit(
            'user',
            $user->id,
            $user->name,
            'password_reset',
            ['user_id' => $user->id, 'email' => $user->email],
            'Password reset by Admin'
        );

        return back()->with('success', "ব্যবহারকারী '{$user->name}' এর পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।");
    }

    /**
     * 10. Access Testing & Permission Simulator.
     */
    public function simulator(Request $request)
    {
        $users = User::orderBy('name')->get();
        $roles = Role::with('permissions')->get();

        $selectedUser = null;
        $accessibleModules = [];
        $deniedModules = [];
        $routeTestResult = null;
        $totalRegistries = PermissionRegistry::active()->count();

        if ($request->filled('user_id')) {
            $selectedUser = User::with(['roles.permissions'])->find($request->user_id);

            if ($selectedUser) {
                // Get complete catalog tree
                $tree = PermissionRegistryService::getHierarchicalTree();

                foreach ($tree as $mKey => $mod) {
                    $hasAny = false;
                    foreach ($mod['menus'] as $menu) {
                        foreach ($menu['submenus'] as $sub) {
                            foreach ($sub['actions'] as $act) {
                                if ($selectedUser->hasPermission($act['permission_key'])) {
                                    $hasAny = true;
                                    break 3;
                                }
                            }
                        }
                    }

                    if ($hasAny || $selectedUser->id == 1 || $selectedUser->role === 'super_admin') {
                        $accessibleModules[] = $mod;
                    } else {
                        $deniedModules[] = $mod;
                    }
                }

                // Route Access Preview test
                if ($request->filled('test_permission')) {
                    $testKey = trim($request->test_permission);
                    $canAccess = $selectedUser->hasPermission($testKey);
                    $routeTestResult = [
                        'permission' => $testKey,
                        'allowed' => $canAccess,
                    ];
                }
            }
        }

        return view('admin.roles.simulator', compact(
            'users',
            'roles',
            'selectedUser',
            'accessibleModules',
            'deniedModules',
            'routeTestResult',
            'totalRegistries'
        ));
    }

    /**
     * Audit Logs Explorer.
     */
    public function auditLogs(Request $request)
    {
        $query = PermissionAuditLog::orderBy('id', 'desc');

        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        $auditLogs = $query->paginate(25);

        return view('admin.roles.audit_logs', compact('auditLogs'));
    }
}
