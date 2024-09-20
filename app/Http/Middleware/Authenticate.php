<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            $segment = $request->segment(1);

            if ( in_array($segment, config('app.locales'))) {
                $lang = $segment;
            }else{
                $lang = app()->getLocale();
            }
            if($request->segment(2)=='admin'){
               // dd($request->segments());
                return route('admin.login',$lang);
            }
            return route('frontend.login',$lang);
        }
    }
}
