<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class WebsiteCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('BRANDED_WEBSITE') != 0 &&  setting('website') != 0):
            return $next($request);
        endif;
        return redirect()->route('login');

    }
}
