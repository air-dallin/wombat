<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        app()->setLocale($request->segment(1));
        return $next($request);
    }
}
