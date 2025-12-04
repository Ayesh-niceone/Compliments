<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Force locale from session OR fallback
        if(session('locale')) {
            App::setLocale(session('locale'));
        }
        return $next($request);
    }
}

