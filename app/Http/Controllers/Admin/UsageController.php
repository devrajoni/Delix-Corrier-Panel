<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\DeliveryMan;
use App\Models\Branch;
use App\Models\User;
use App\Models\ThirdParty;
use Illuminate\Http\Request;

class UsageController extends Controller
{
    public function index()
    {
        $data['active_merchant']            = Merchant::where('status', 1)->get()->count();
        $data['inactive_merchant']          = Merchant::where('status', 0)->get()->count();
        $data['parcel']                     = Parcel::get()->count();
        $data['rider']                      = DeliveryMan::get()->count();
        $data['branch']                     = Branch::get()->count();
        $data['staff']                      = User::where('user_type', 'staff')->where('is_super_admin', '!=', 1)->where('is_admin', '!=', 1)->get()->count();
        $data['delivery_partner']           = ThirdParty::get()->count();

        return view('admin.subscription.usage', $data);
    }
}
