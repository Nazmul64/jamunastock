<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request and set the active application locale.
     * Default locale is 'en' (English). When user selects 'bn', it switches to Bengali.
     */
    public function handle(Request $request, Closure $next)
    {
        // Default to English as explicitly requested by user
        $locale = Session::get('locale', config('app.locale', 'en'));

        if (!in_array($locale, ['en', 'bn'])) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
