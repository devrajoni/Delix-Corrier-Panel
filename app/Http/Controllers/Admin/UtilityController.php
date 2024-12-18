<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\UpdateTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    use UpdateTrait;

    public function serverInfo(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.utility.server_info');
    }

    public function systemUpdate(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $configFile = "/var/www/config.json";
            if (!file_exists($configFile)) {
                $configFile = base_path('config.json');
            }

            $installed_version_code         = setting('version_code');
            if($installed_version_code =="" || $installed_version_code==NULL):
                $installed_version_code = 100;
            endif;
            $installed_version_title        = setting('version_title');
            if($installed_version_title =="" || $installed_version_title==NULL):
                $installed_version_title = "V1.0.0";
            endif;

            $latest_version_code       = $installed_version_code ;
            $latest_version_title      = $installed_version_title ;

            $config = json_decode(file_get_contents($configFile), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Update version settings
                $latest_version_code       = (int)$config['version_code'];
                $latest_version_title      = $config['version_title'];
            }


            $is_old                             = $installed_version_code < $latest_version_code ;

            $data     = [
                'installed_version_code'        => $installed_version_code,
                'installed_version_title'       => $installed_version_title,
                'is_old'                        => $is_old,
                'latest_version_code'           => $latest_version_code,
                'latest_version_title'          => $latest_version_title,
            ];

            return view('admin.utility.system_update', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function downloadUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (config('app.demo_mode')) {
                return response()->json([
                    'message' => __('This function is disabled in demo server.'),
                    'type'    => __('Error').' !',
                    'class'   => 'danger',
                ]);
            }

            $update = $this->downloadUpdateFile($request->all());

            if (is_string($update)) {
                return response()->json([
                    'message' => $update,
                    'type'    => __('Error').' !',
                    'class'   => 'danger',
                ]);
            }

            return response()->json([
                'type'    => __('Success').' !',
                'class'   => 'success',
                'message' => __('Update Successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type'    => __('Error').' !',
                'class'   => 'danger',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
