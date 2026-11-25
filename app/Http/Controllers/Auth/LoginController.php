<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'মোবাইল নম্বর, ইউজারনেম বা ইমেইল লিখুন',
            'password.required' => 'পাসওয়ার্ড দিন',
        ]);
    }

    /**
     * Attempt to log the user into the application using multi-field matching.
     * Checks phone number, username, direct email, and synthetic email suffix.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $loginInput = trim($request->input('username') ?? $request->input('email') ?? '');
        $password = $request->input('password');

        if (empty($loginInput) || empty($password)) {
            return false;
        }

        // Search user by mobile number, username, direct email, or @jamunastock.com / @ikrishiporibar.com suffix
        $user = User::where(function ($query) use ($loginInput) {
            $query->where('username', $loginInput)
                  ->orWhere('phone', $loginInput)
                  ->orWhere('email', $loginInput)
                  ->orWhere('email', $loginInput . '@jamunastock.com')
                  ->orWhere('email', $loginInput . '@ikrishiporibar.com');
        })->first();

        if ($user && Hash::check($password, $user->password)) {
            // Check account status if account is suspended or banned
            if (isset($user->status) && in_array($user->status, ['banned', 'suspended', 'inactive'])) {
                return false;
            }

            Auth::login($user, $request->boolean('remember'));
            return true;
        }

        return false;
    }

    /**
     * The user has been authenticated. Redirect them to their designated dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // 1. Agent Point User -> Dedicated Agent Dashboard
        if (method_exists($user, 'isAgent') && $user->isAgent()) {
            return redirect()->intended(route('agent.dashboard'));
        }

        // 2. Staff or Administrator -> Admin Dashboard
        if (method_exists($user, 'isStaffOrAdmin') && $user->isStaffOrAdmin()) {
            return redirect()->intended('/home');
        }

        // 3. Supplier -> Supplier Dashboard
        if ($user->role === 'supplier') {
            return redirect()->intended(route('supplier.dashboard'));
        }

        // 4. Customer / General User -> Customer Dashboard
        return redirect()->intended(route('dashboard'));
    }
}
