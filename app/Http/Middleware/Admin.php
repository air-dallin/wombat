<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Admin
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
        $user = \Illuminate\Support\Facades\Auth::user();
        switch ($user->role){
            case User::ROLE_ADMIN:
            case User::ROLE_MODERATOR:
                return $next($request);
                break;
        }
        return redirect(localeRoute('index'));

    }
}
