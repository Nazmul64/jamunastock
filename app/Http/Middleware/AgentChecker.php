<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentChecker
{
    /**
     * Handle an incoming request for Agent Portal.
     * Enforces Strict RBAC & Data Isolation for Agent Points.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Super Admin or Administrator can always access
            if ($user->id == 1 || $user->role === 'super_admin' || $user->role === 'admin') {
                return $next($request);
            }

            // 2. Verified Marketing / Distribution Agent
            if ($user->isAgent()) {
                // Ensure Agent is bound to an active Agent Point
                if (!$user->getAssignedAgentPointId()) {
                    Auth::logout();
                    return redirect()->route('agent.login')->with('error', 'আপনার অ্যাকাউন্টের সাথে কোনো অনুমোদিত এজেন্ট পয়েন্ট সংযুক্ত করা হয়নি। অনুগ্রহ করে কেন্দ্রীয় অ্যাডমিনের সাথে যোগাযোগ করুন।');
                }
                return $next($request);
            }

            return redirect()->route('home')->with('error', 'শুধুমাত্র অনুমোদিত এজেন্টগণ এই পোর্টালে প্রবেশ করতে পারেন।');
        }

        return redirect()->route('agent.login');
    }
}
