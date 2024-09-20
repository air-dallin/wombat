<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckTarif
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
       // $user = Auth::id();
        //->with('tarif');

      //  dump('check-tarif');
        //dump(Auth::id());

                return $next($request);
      //  return redirect(localeRoute('index'));

    }
}
