<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Permission;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;


class SystemInfoCheckMiddleware
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
        $requiredPermissions = [
            'email_configuration',
            'storage_setting',
            'pusher_setting',
            'cron_setting',
            'sms_gateway',
            'server_info',
            'system_update',
            'extension_library',
            'filesystem',
            'mobile_app',
            're_captcha_setting',
        ];

        $user = Sentinel::getUser();

        if ($user->is_super_admin == 1) {
            $userPermissions = $user->permissions;

            foreach ($requiredPermissions as $permission) {
                if (in_array($permission, $userPermissions)) {
                    return $next($request);
                }
            }
        }

        return abort(403, 'Access Denied');
    }


}
