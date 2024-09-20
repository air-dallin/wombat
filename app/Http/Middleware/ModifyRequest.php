<?php

namespace App\Http\Middleware;

use Closure;
use http\Env\Request;

class ModifyRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->route()->parameter('locale');
        $request->route()->forgetParameter('locale');
        app()->setLocale($locale);
        return $next($request);
    }

}
