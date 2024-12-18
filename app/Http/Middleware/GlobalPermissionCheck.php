<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;


class GlobalPermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (Sentinel::check() && in_array($permission, Sentinel::getUser()->permissions) && ((Sentinel::getUser()->is_super_admin == 1) || (env('VERIENT') == "us"))) :
            return $next($request);
        endif;
        return abort(403, 'Access Denied');
    }
}
