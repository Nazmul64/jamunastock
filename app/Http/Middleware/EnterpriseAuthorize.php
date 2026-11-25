<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnterpriseAuthorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  One or more permissions (pipe-separated: 'perm1|perm2')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'লগইন করা আবশ্যক। সেশন মেয়াদোত্তীর্ণ হতে পারে।',
                ], Response::HTTP_UNAUTHORIZED);
            }
            return redirect()->guest(route('login'));
        }

        $user = Auth::user();

        // 1. Super Admin has unrestricted system bypass
        if ($user->id == 1 || $user->role === 'super_admin' || $user->hasRole('super_admin')) {
            $this->injectDataScope($request, 'all');
            return $next($request);
        }

        // 2. Parse permissions (supports 'hrm.attendances.view|hrm.attendances.create')
        $permissions = explode('|', $permission);
        $hasAccess = false;

        foreach ($permissions as $perm) {
            $perm = trim($perm);
            if ($user->hasPermission($perm)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 403,
                    'error_type' => 'FORBIDDEN_ACCESS',
                    'message' => "আপনার অ্যাকাউন্টে '{$permission}' অ্যাকশন পরিচালনার অনুমতি (Permission) নেই। অনুগ্রহ করে অ্যাডমিনিস্ট্রেটরের সাথে যোগাযোগ করুন।",
                ], Response::HTTP_FORBIDDEN);
            }

            // Render custom 403 error page or abort
            return response()->view('errors.403_enterprise', [
                'requiredPermission' => $permission,
                'user' => $user,
            ], Response::HTTP_FORBIDDEN);
        }

        // 3. Inject Data Scope for ABAC into request attributes
        $primaryRole = $user->roles()->first();
        $scope = $primaryRole ? ($primaryRole->data_scope ?? 'all') : ($user->data_scope ?? 'all');
        $this->injectDataScope($request, $scope);

        return $next($request);
    }

    /**
     * Inject Data Scope into Request for Controller & Query Level Scoping
     */
    protected function injectDataScope(Request $request, string $scope): void
    {
        $request->attributes->set('data_scope', $scope);
        $request->attributes->set('scope_branch_id', Auth::user()->branch_id ?? null);
        $request->attributes->set('scope_department_id', Auth::user()->department_id ?? null);
        $request->attributes->set('scope_user_id', Auth::id());
    }
}
