<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Force locale from session OR fallback
        $locale = session()->get('locale', config('app.locale'));
        app()->setLocale($locale);

        // Also force Carbon locale if needed
        \Carbon\Carbon::setLocale($locale);

        return $next($request);
    }
}

