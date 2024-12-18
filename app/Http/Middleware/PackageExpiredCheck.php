<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;  // Import Carbon

class PackageExpiredCheck
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
        if (expireSubscription() == true):
            \Session::flash('openModal', true);
            return redirect()->route('dashboard');
        endif;
        return $next($request);
    }
}
