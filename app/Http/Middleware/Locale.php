<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Locale
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

        if($request->method() === 'GET') {

            $segment = $request->segment(1);

            // смена языка lang/uz
		    if($segment =='lang'){
                app()->setLocale('ru');

                return $next($request);
            }

            $segments = $request->segments();

			if ( mb_strlen($segment)==2 ){ // язык задан

			    if(!in_array($segment, config('app.locales'))) {

                    $fallback = session('locale') ?: config('app.fallback_locale');

                    app()->setLocale($fallback);
                    session(['locale' => $fallback]);
                    $segments[0] = $fallback;
                    return redirect()->to(implode('/', $segments));
                }

			}else{ // не задан язык
                $fallback = session('locale') ? : config('app.fallback_locale');
                app()->setLocale($fallback);
                session(['locale' => $fallback]);
                $segments = Arr::prepend($segments, $fallback);

                return redirect()->to(implode('/', $segments));
            }

            session(['locale' => $segment]);
            app()->setLocale($segment);
        }

        return $next($request);
    }
}
