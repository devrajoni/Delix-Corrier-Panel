<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ParcelsImport;
use App\Repositories\Interfaces\ParcelInterface;
use File;
use Response;
use Brian2694\Toastr\Facades\Toastr;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class ImportExportController extends Controller
{

    protected $parcels;

    public function __construct(ParcelInterface $parcels)
    {
        $this->parcels          = $parcels;
    }

    public function importExportView()
    {
        return view('admin.bulk.import');
    }
    public function export()
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        try{
            $filename = (Sentinel::getUser()->user_type == 'merchant' || Sentinel::getUser()->user_type == 'merchant_staff') ? 'admin/excel/merchant-parcel-import-sample.xlsx' : 'admin/excel/staff-parcel-import-sample.xlsx';
            if (file_exists(public_path($filename))):
                $filepath = public_path($filename);
                return Response::download($filepath);
            else:
                return back()->with('danger',__('file_not_found'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }
    public function import()
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        try{

            $parcelLimit                                = env('MONTHLY_PARCEL');
            $currentParcelsCount                        = 0;
            $currentParcelsCount                        = $this->parcels->all()->where('created_at', '>=', now()->startOfMonth())->count();

            if ($currentParcelsCount >= $parcelLimit):
                return back()->with('danger', __('parcel_creation_limit_exceeded'));
            endif;

            $extension         = request()->file('file')->getClientOriginalExtension();
            if ($extension    != 'xlsx' && $extension != 'csv'):
                return back()->with('danger', __('file_type_not_supported'));
            endif;
            $file             = request()->file('file')->store('import');
            $import           = new ParcelsImport();
            $import->import($file);
            unlink(storage_path('app/'.$file));

            return back()->with('success',__('successfully_imported'));
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }
}
