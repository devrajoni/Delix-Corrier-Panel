<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ForgotPasswordPostRequest;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use App\Http\Requests\Admin\Auth\ResetPasswordPostRequest;
use App\Http\Resources\Api\ParcelResource;
use App\Http\Resources\Api\PayoutLogResource;
use App\Repositories\Interfaces\UserInterface;
use App\Models\Account\DeliveryManAccount;
use Tymon\JWTAuth\Exceptions\JWTException;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Http\Resources\Api\LoginActivity;
use App\Models\Account\MerchantAccount;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Profile;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiReturnFormatTrait;
use Carbon\Carbon;
use App\Models\Merchant;
use App\Models\LogActivity;
use App\Models\Parcel;
use App\Models\Apikey;
use App\Models\User;
use App\Traits\RandomStringTrait;
use App\Traits\SmsSenderTrait;
use Illuminate\Http\Request;
use App\Traits\SendMailTrait;
use App\Models\Notice;
use Reminder;
use JWTAuth;
use Str;
use DB;


class AuthController extends Controller
{
    use ApiReturnFormatTrait;
    use RandomStringTrait;
    use SmsSenderTrait;
    use SendMailTrait;

    protected $merchantRepo;
    protected $userRepo;

    public function __construct(MerchantInterface $merchantRepo, UserInterface $userRepo)
    {
        $this->merchantRepo = $merchantRepo;
        $this->userRepo     = $userRepo;
    }

    public function signUp(Request $request)
    {
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'first_name'            => 'required|max:50',
                'last_name'             => 'required|max:50',
                'company'               => 'required|unique:merchants,company',
                'email'                 => 'required|unique:users,email',
                'phone_number'          => 'required|unique:users,phone_number',

            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $user = $this->merchantRepo->tempStore($request);
            if(!$user){
              return $this->responseWithError(__('sms_credit_is_not_available'), [], 500);
            }
            // $data['message'] = 'thank you for register.here is your otp'. ' ' .$user['otp']. ' ' . 'here is your id'. ' ' .$user['temp_id'];
            $data = [
                'otp'    => $user['otp'],
                'id'     => $user['temp_id'],
            ];

            DB::commit();

            return $this->responseWithSuccess(__('successfully_registered'), [], $data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function otp(Request $request)
    {
        DB::beginTransaction();

        try{
            $validator   = Validator::make($request->all(), [
                'otp'    => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            if ($user = $this->merchantRepo->otpConfirm($request)){
                $log                = [];
                $log['url']         = \Request::fullUrl();
                $log['method']      = \Request::method();
                $log['ip']          = \Request::ip();
                $log['browser']     = $this->getBrowser(\Request::header('user-agent'));
                $log['platform']    = $this->getPlatForm(\Request::header('user-agent'));
                $log['user_id']     = $user->id;
                LogActivity::create($log);

                $data = Sentinel::authenticate($user);

            }

            if(!$user){
                return $this->responseWithError(__('user_not_found'), [], 500);
            }

            $data = [
                'info'              => $data,
                'api_key'           => $user->merchant->api_key,
            ];

            DB::commit();

            return $this->responseWithSuccess(__('successfully_registered'), [], $data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError($e->getMessage(), [], 500);
        }
    }

    public function confirmOtp(Request $request)
    {
        DB::beginTransaction();

        try{
            $validator   = Validator::make($request->all(), [
                'otp'    => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            if ($user = $this->merchantRepo->otpConfirm($request)){
                $log                = [];
                $log['url']         = \Request::fullUrl();
                $log['method']      = \Request::method();
                $log['ip']          = \Request::ip();
                $log['browser']     = $this->getBrowser(\Request::header('user-agent'));
                $log['platform']    = $this->getPlatForm(\Request::header('user-agent'));
                $log['user_id']     = $user->id;
                LogActivity::create($log);

                $data = Sentinel::authenticate($user);

            }

            $data = [
                'info'              => $data,
                'api_key'           => $user->merchant->api_key,
            ];

            DB::commit();

            return $this->responseWithSuccess(__('successfully_registered'), [], $data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError($e->getMessage(), [], 500);
        }
    }

    public function otpRequest($id){
        if ($this->merchantRepo->resendOtp($id)):
            $success = __('we_have_send_you_another_otp');
        else:
            $danger  = __('unable_to_send_otp');
            return response()->json($danger);
        endif;
    }

    public function activation($email, $activationCode)
    {
        $user       = User::whereEmail($email)->first();

        sendMail($user, '', 'verify_email_success', '');

        return redirect()->route('login')->with('success', __('email_verified_successfully'));
    }

    public function login(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'email'     => 'required',
                'password'  => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $user = User::where('email', $request->email)->first();


            if (blank($user)) :
                return $this->responseWithError( __('user_not_found'), [], 422);
            endif;

            if($user->status == \App\Enums\StatusEnum::INACTIVE) :
                return $this->responseWithError( __('your_account_is_inactive'), [], 401);
            elseif($user->status == 2):
                return $this->responseWithError( __('your_account_is_suspend'), [], 401);
            endif;

            if (!Hash::check($request->password, $user->password)) :
                return $this->responseWithError(__('password_mismatch'), $validator->errors(), 422);
            endif;

            $credentials = ['email'=>$request->email, 'password'=>$request->password];


            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return $this->responseWithError(__('unable_to_create_token'), [], 401);
                }
            } catch (JWTException $e) {
                return $this->responseWithError(__('could_not_create_token'), [] , 422);

            } catch (ThrottlingException $e) {
                return $this->responseWithError(__('suspicious_activity_on_your_ip'). $e->getDelay() .  __('seconds'), [], 500);

            } catch (NotActivatedException $e) {
                return $this->responseWithError(__('you_account_not_activated_check_mail_or_contact_support'),[],400);

            } catch (\Exception $e) {
                return $this->responseWithError($e->getMessage(), [], 500);
            }

            $id                     = $user->id;

            $profile                = $this->profileInfo($id);
            $data = $this->fetchParcelData($user);

            if ($user->user_type == 'merchant_staff') {
                $parcel = Parcel::where('merchant_id', $user->merchant_id)->orWhere('status', 'pending')->latest()->paginate(10);
            } elseif ($user->user_type == 'merchant') {
                $parcel = Parcel::where('merchant_id', $user->merchant->id)->orWhere('status', 'pending')->latest()->paginate(10);
            }



            $data = [
                'profile'           => $profile,
                'token'             => $token,
                'permissions'       => $user->permissions,
                'counts'            => $data,
                'parcel'            => ParcelResource::collection($parcel),
                'paginate'          => [
                    'total'         => $parcel->total(),
                    'current_page'  => $parcel->currentPage(),
                    'per_page'      => $parcel->perPage(),
                    'last_page'     => $parcel->lastPage(),
                    'prev_page_url' => $parcel->previousPageUrl(),
                    'next_page_url' => $parcel->nextPageUrl(),
                    'path'          => $parcel->path(),
                ],
            ];


            return $this->responseWithSuccess(__('successfully_login'), [], $data, 200);
        } catch (\Exception $e){
            return $this->responseWithError($e->getMessage(), [], 500);
        }
    }

    public function profile()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }
            $id                      = $user->id;
            $profile                 = $this->profileInfo($id);

            if ($user->user_type == 'merchant'){
                $balance = format_price($user->merchant->balance($user->merchant->id));
            }else{

                $balance = format_price($user->staffMerchant->payableBalance($user->staffMerchant->id));
            }

            $data = [
                'profile'                      => $profile,
                'available_balance'            => $balance,
            ];

            return $this->responseWithSuccess(__('successfully_found'), [], $data, 200);
        }catch (\Exception $e){
            return $this->responseWithError($e->getMessage(), [], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();

        try{
            $user               = jwtUser();

            $validator = Validator::make($request->all(), [
                'first_name'    => 'required|max:50',
                'last_name'     => 'required|max:50',
                'email'         => 'required|unique:users,email,' .$user->id,

            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }



            $this->userRepo->updateProfile($request);

            $id                      = $user->id;
            $profile                 = $this->profileInfo($id);

            $data = [
                'profile'           => $profile,
            ];

            DB::commit();

            return $this->responseWithSuccess(__('successfully_updated'), [], $data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function logout()
    {
        try {
            Sentinel::logout();
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->responseWithSuccess(__('successfully_logout'),[] ,200);
        } catch (JWTException $e) {
            JWTAuth::unsetToken();
            return $this->responseWithError(__('failed_to_logout'), [], 422);
        }
    }

    public function changePassword(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = [];
            $validator              = Validator::make($request->all(), [
                'current_password'  => 'required|max:50',
                'password' => [
                        'required',
                        'confirmed',
                        'min:6',
                    ],
                ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $hasher = Sentinel::getHasher();

            $current_password   = $request->current_password;
            $password           = $request->password;

            if (!$hasher->check($current_password, $user->password)) {
              return $this->responseWithError(__('current_password_is_invalid'), [], 500);
            }

            $user                           = User::find($user->id);
            $user->password                 = bcrypt($password);
            $user->last_password_change     = date('Y-m-d H:i:s');
            $user->save();
            $data['password']               = $password;

            DB::commit();

            return $this->responseWithSuccess(__('successfully_updated'), [], $data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function loginActivity()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $login_activities       = LogActivity::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(2);

            $data = [
                'login_activities'  => LoginActivity::collection($login_activities),
                'paginate' => [
                    'total'         => $login_activities->total(),
                    'current_page'  => $login_activities->currentPage(),
                    'per_page'      => $login_activities->perPage(),
                    'last_page'     => $login_activities->lastPage(),
                    'prev_page_url' => $login_activities->previousPageUrl(),
                    'next_page_url' => $login_activities->nextPageUrl(),
                    'path'          => $login_activities->path(),
                ],
            ];

            return $this->responseWithSuccess(__('successfully_found'), [], $data, 200);
        }catch (\Exception $e){
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    protected function fetchParcelData($user)
    {
        $data = [];

        $userPermissions = $user->permissions;

        if ($user->user_type == 'merchant_staff') {
            $data['cod'] = Parcel::where('merchant_id', $user->merchant_id)
                ->where(function ($query) {
                    $query->whereIn('status', ['delivered', 'delivered-and-verified'])
                        ->orWhere('is_partially_delivered', true);
                })
                ->sum('price');

            $parcels    = $user->staffMerchant->parcels()
                ->when(is_array($userPermissions) && !in_array('all_parcel', $userPermissions), function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereIn('status', ['delivered', 'delivered-and-verified'])
                ->orWhere('is_partially_delivered', true)->get();


        } elseif ($user->user_type == 'merchant') {
            $data['cod'] = Parcel::where('merchant_id', $user->merchant->id)
                ->where(function ($query) {
                    $query->whereIn('status', ['delivered', 'delivered-and-verified'])
                        ->orWhere('is_partially_delivered', true);
                })
                ->sum('price');

            $parcels    = $user->merchant->parcels()->get();
        }

        $data           = $this->get_counts($parcels, $user);


        return $data;
    }

    public function get_counts($parcels, $user)
    {
        $thirtyDaysAgo                      = Carbon::now()->subDays(30);
        $userPermissions                    = $user->permissions;


        if ($user->user_type == 'merchant') {
            $parcels                 = $user->merchant->parcels()->where('created_at', '>=', $thirtyDaysAgo)->get();
            $data['current_balance'] = $user->merchant->balance;
        } elseif ($user->user_type == 'merchant_staff') {
            $parcels                 = $user->staffMerchant->parcels()->where('created_at', '>=', $thirtyDaysAgo);
            if (!in_array('all_parcel', $userPermissions)) {
                $parcels = $parcels->whereHas('shop', function ($q) use ($user) {
                    $q->whereIn('id', $user->shops);
                });
            }
            $data['current_balance'] = $user->staffMerchant->balance;
        }



        $delivered_cod                      = $parcels->whereIn('status', ['delivered','delivered-and-verified'])->sum('price');
        $parcel                             = $parcels->whereIn('status', ['delivered','delivered-and-verified', 'is_partially_delivered'])->pluck('id');
        $data['total_cod']                  = format_price($parcels->where('is_partially_delivered', true)->sum('price') + $delivered_cod);
        $data['parcel_added']               = $parcels->count();
        $data['processing_for_delivery']    = $parcels->whereNotIn('status', ['delivered','delivered-and-verified', 'cancel', 'returned-to-merchant','deleted'])
                                             ->where('is_partially_delivered', false)
                                             ->count();

        $data['cancelled_count']            = $parcels->where('status','cancel')->count();
        $data['deleted_count']              = $parcels->where('status','deleted')->count();
        // $data['partial_delivered_count']    = $parcels->where('is_partially_delivered', true)->count();
        $data['returned_count']             = $parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['parcel_delivered']           = $parcels->whereIn('status', ['delivered','delivered-and-verified', 'is_partially_delivered'])->count();
        $query                              = MerchantAccount::where('source', '!=', 'paid_parcels_delivery_reverse');

        if ($user->user_type == 'merchant_staff') {
            $query->whereNotIn('source', ['previous_balance', 'cash_given_for_delivery_charge', 'opening_balance']);

            if (is_array($userPermissions) && !in_array('all_parcel_logs', $userPermissions) && !in_array('all_payment_logs', $userPermissions)) {
                $query->whereHas('parcel', function ($q) use ($user) {
                    $q->where('user_id', $user->id)->whereIn('status', ['delivered', 'delivered-and-verified', 'is_partially_delivered']);
                })->orWhereHas('withdraw', function ($q) use ($user) {
                    $q->where('created_by', $user->id)->whereIn('status', ['delivered', 'delivered-and-verified', 'is_partially_delivered']);
                });

            } elseif (is_array($userPermissions) && !in_array('all_parcel_logs', $userPermissions)) {
                $query->whereHas('parcel', function ($q) use ($user) {
                    $q->where('user_id', $user->id)->whereIn('status', ['delivered', 'delivered-and-verified', 'is_partially_delivered']);
                })->orWhereHas('withdraw');
            } elseif (is_array($userPermissions) && !in_array('all_payment_logs', $userPermissions)) {
                $query->whereHas('withdraw', function ($q) use ($user) {
                    $q->where('created_by', $user->id)->whereIn('status', ['delivered', 'delivered-and-verified', 'is_partially_delivered']);
                })->orWhereHas('parcel');
            }

        }

        if ($user->user_type == 'merchant') {
            $merchant_id            = $user->merchant->id;
            $query->where('merchant_id', $merchant_id);
        }

        $last7Days                  = Carbon::now()->subDays(7);
        $previous7Days              = Carbon::now()->subDays(14);

        $last7Days          	    = Carbon::now()->subDays(7);
        $previous7Days              = Carbon::now()->subDays(14);

        $currentPeriodStart 	    = $last7Days->format('Y-m-d');
        $currentPeriodEnd 	        = Carbon::now()->format('Y-m-d');
        $previousPeriodStart        = $previous7Days->format('Y-m-d');
        $previousPeriodEnd 	        = $last7Days->format('Y-m-d');

        $data['currentPeriodStart'] = $currentPeriodStart;
        $data['currentPeriodEnd'] 	= $currentPeriodEnd;

        // Current 7-day period calculations
        $calculate['current']['opening_balance'] = $query->clone()
            ->where('details', 'opening_balance')
            ->whereDate('created_at', '>=', $last7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        $calculate['current']['cod_collected'] = $query->clone()
            ->whereIn('parcel_id', $parcel)
            ->where('details', 'parcel_cod_collection_from_customer')
            ->whereDate('created_at', '>=', $last7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        $calculate['current']['delivery_charge'] = $query->clone()
            ->whereIn('parcel_id', $parcel)
            ->where('details', 'Total Delivery Charge')
            ->whereDate('created_at', '>=', $last7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        $calculate['current']['vat_adjustment'] = $query->clone()
            ->whereIn('parcel_id', $parcel)
            ->where('details', 'govt_vat_for_parcel_delivery')
            ->whereDate('created_at', '>=', $last7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        $calculate['current']['payout_processed'] = $query->clone()
            ->where('source', 'payment_withdraw_by_merchant')
            ->whereDate('created_at', '>=', $last7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        // Previous 7-day period calculations
        $calculate['previous']['opening_balance'] = $query->clone()
            ->where('details', 'opening_balance')
            ->whereDate('created_at', '<', $last7Days)
            ->whereDate('created_at', '>=', $previous7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        $calculate['previous']['cod_collected'] = $query->clone()
            ->whereIn('parcel_id', $parcel)
            ->where('details', 'parcel_cod_collection_from_customer')
            ->whereDate('created_at', '<', $last7Days)
            ->whereDate('created_at', '>=', $previous7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        $calculate['previous']['delivery_charge'] = $query->clone()
            ->whereIn('parcel_id', $parcel)
            ->where('details', 'Total Delivery Charge')
            ->whereDate('created_at', '<', $last7Days)
            ->whereDate('created_at', '>=', $previous7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        $calculate['previous']['vat_adjustment'] = $query->clone()
            ->whereIn('parcel_id', $parcel)
            ->where('details', 'govt_vat_for_parcel_delivery')
            ->whereDate('created_at', '<', $last7Days)
            ->whereDate('created_at', '>=', $previous7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        $calculate['previous']['payout_processed'] = $query->clone()
            ->where('source', 'payment_withdraw_by_merchant')
            ->whereDate('created_at', '<', $last7Days)
            ->whereDate('created_at', '>=', $previous7Days)
            ->latest()
            ->take(10)
            ->sum('amount');

        // Calculate the differences
        function getSign($current, $previous) {
            return $current > $previous ? '+' : ($current < $previous ? '-' : '==');
        }

        $data['difference']['opening_balance']     = getSign($calculate['current']['opening_balance'], $calculate['previous']['opening_balance']);
        $data['difference']['cod_collected']       = getSign($calculate['current']['cod_collected'], $calculate['previous']['cod_collected']);
        $data['difference']['delivery_charge']     = getSign($calculate['current']['delivery_charge'], $calculate['previous']['delivery_charge']);
        $data['difference']['vat_adjustment']      = getSign($calculate['current']['vat_adjustment'], $calculate['previous']['vat_adjustment']);
        $data['difference']['payout_processed']    = getSign($calculate['current']['payout_processed'], $calculate['previous']['payout_processed']);

        $data['opening_balance']  = $calculate['current']['opening_balance'];
        $data['cod_collected']    = $calculate['current']['cod_collected'];
        $data['delivery_charge']  = $calculate['current']['delivery_charge'];
        $data['vat_adjustment']   = $calculate['current']['vat_adjustment'];
        $data['payout_processed'] = $calculate['current']['payout_processed'];

        return $data;
    }

    public function getPlatForm($u_agent)
    {
        $platform = '';
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
        return $platform;
    }
    public function getBrowser($u_agent)
    {
        $bname = '';
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }elseif(preg_match('/Firefox/i',$u_agent)){
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }elseif(preg_match('/OPR/i',$u_agent)){
            $bname = 'Opera';
            $ub = "Opera";
        }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
            $bname = 'Apple Safari';
            $ub = "Safari";
        }elseif(preg_match('/Netscape/i',$u_agent)){
            $bname = 'Netscape';
            $ub = "Netscape";
        }elseif(preg_match('/Edge/i',$u_agent)){
            $bname = 'Edge';
            $ub = "Edge";
        }elseif(preg_match('/Trident/i',$u_agent)){
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        return $bname;
    }

    public function profileInfo($id)
    {

        $profile = User::where('id', $id)->first();

        $data = [
            'name'            => $profile->first_name .' '.$profile->last_name,
            'first_name'      => $profile->first_name,
            'last_name'       => $profile->last_name,
            'phone_number'    => $profile->phone_number,
            'email'           => $profile->email,
            'status'          => $profile->status,
            'currency'        => setting('default_currency_symbol'),
            'default_unit'    => setting('default_unit'),
            'merchant'        => $profile->merchant->company ?? $profile->staffMerchant->company,
            'image_id'        => getFileLink('80X80', $profile->image_id),
            'created_at'      => $profile->created_at->format('d-m-Y H:i:s'),
            'updated_at'      => $profile->updated_at->format('d-m-Y H:i:s'),
            'address'         => $profile->merchant ? $profile->merchant->address : null,
        ];

        return $data;
    }

    public function dashboard()
    {
        try {
            $user                   = jwtUser();
            $id                     = $user->id;
            $profile                = $this->profileInfo($id);

            $data                   = $this->fetchParcelData($user);
            if ($user->user_type == 'merchant_staff') {
                $parcel                  = Parcel::where('merchant_id', $user->merchant_id)->orWhere('status', 'pending')->latest()->paginate(10);
                $read_merchant_api       = settingHelper('preferences')->where('title', 'read_merchant_api')->first()->staff;
                $merchant_api_update     = settingHelper('preferences')->where('title', 'merchant_api_update')->first()->staff;
                $create_parcel           = settingHelper('preferences')->where('title', 'create_parcel')->first()->staff;
                $create_payment_request  = settingHelper('preferences')->where('title', 'create_payment_request')->first()->staff;

            } elseif ($user->user_type == 'merchant') {
                $parcel                  = Parcel::where('merchant_id', $user->merchant->id)->orWhere('status', 'pending')->latest()->paginate(10);
                $read_merchant_api       = settingHelper('preferences')->where('title', 'read_merchant_api')->first()->merchant;
                $merchant_api_update     = settingHelper('preferences')->where('title', 'merchant_api_update')->first()->merchant;
                $create_parcel           = settingHelper('preferences')->where('title', 'create_parcel')->first()->merchant;
                $create_payment_request  = settingHelper('preferences')->where('title', 'create_payment_request')->first()->merchant;

            }

            $result                 = $this->allPayoutLog();

            $data = [
                'profile'                    => $profile,
                'permissions'                => $user->permissions,
                'counts'                     => $data,
                'read_merchant_api'          => $read_merchant_api,
                'merchant_api_update'        => $merchant_api_update,
                'create_parcel'              => $create_parcel,
                'user_type'                  => $user->user_type,
                'create_payment_request'     => $create_payment_request,
                'parcel'                     => ParcelResource::collection($parcel),
                'paginate'                   => [
                    'total'                  => $parcel->total(),
                    'current_page'           => $parcel->currentPage(),
                    'per_page'               => $parcel->perPage(),
                    'last_page'              => $parcel->lastPage(),
                    'prev_page_url'          => $parcel->previousPageUrl(),
                    'next_page_url'          => $parcel->nextPageUrl(),
                    'path'                   => $parcel->path(),
                ],
            ];


            return $this->responseWithSuccess(__('data_retrived_successfully'), [], $data, 200);
        } catch (\Exception $e){
            return $this->responseWithError($e->getMessage(), [], 500);
        }

    }
    public function allPayoutLog(): \Illuminate\Http\JsonResponse
    {
        try {
            $user  = jwtUser();
            $query = MerchantAccount::query();

            $userPermissions = $user->permissions;

            if ($user->user_type == 'merchant_staff') {
                $query->whereNotIn('source', ['previous_balance', 'cash_given_for_delivery_charge', 'opening_balance'])
                    ->where('merchant_id', $user->merchant_id);

                if (is_array($userPermissions) && !in_array('all_parcel_logs', $userPermissions) && !in_array('all_payment_logs', $userPermissions)) {
                    $query->where(function ($query) use ($user) {
                        $query->whereHas('parcel', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        })->orWhereHas('withdraw', function ($q) use ($user) {
                            $q->where('created_by', $user->id);
                        });
                    });
                } elseif (is_array($userPermissions) && !in_array('all_parcel_logs', $userPermissions)) {
                    $query->whereHas('parcel', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->orWhereHas('withdraw');
                } elseif (is_array($userPermissions) && !in_array('all_payment_logs', $userPermissions)) {
                    $query->whereHas('withdraw', function ($q) use ($user) {
                        $q->where('created_by', $user->id);
                    })->orWhereHas('parcel');
                }
            } elseif ($user->user_type == 'merchant') {
                $merchant_id = $user->merchant->id;
                $query->where('merchant_id', $merchant_id);
            }else{
                return $this->responseWithError('Invalid user type');
            }

            $payout_log = $query->latest()->paginate();

            $data = [
                'payout_log' => PayoutLogResource::collection(resource: $payout_log),
                'paginate' => [
                    'total'         => $payout_log->total(),
                    'current_page'  => $payout_log->currentPage(),
                    'per_page'      => $payout_log->perPage(),
                    'last_page'     => $payout_log->lastPage(),
                    'prev_page_url' => $payout_log->previousPageUrl(),
                    'next_page_url' => $payout_log->nextPageUrl(),
                    'path'          => $payout_log->path(),
                ],
            ];

            return $this->responseWithSuccess('payout_log_retrieved_successfully', [], $data);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function forgotPasswordPost(Request $request)
    {
        try{
            $validator              = Validator::make($request->all(), [
                'email'             => 'email|required',
                ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $user                  = User::whereEmail($request->email)->first();

            if(blank($user)):
                return $this->responseWithError(__('invalid_email'), [], 500);
            endif;

            if (Reminder::exists($user)) :
                $remainder         = Reminder::where('user_id', $user->id)->first();
            else :
                $remainder         = Reminder::create(user: $user);
            endif;

            $randomNumber          = rand(1, 100000);

            $data              = [
                'subject'          => "Reset Password",
                'user'             => $user,
                'reset_link'       => url('/') . '/reset/' . $user->email . '/' . $remainder->code,
                'otp_number'       => $randomNumber,
                'template_title'   => 'password_reset',
            ];

            //send a mail to user
            $this->sendMail($request->email, 'admin.auth.mail.forgot-password-email', $data);
            $user->otp = $randomNumber;
            $user->save();

         return $this->responseWithSuccess('password_reset_mail_sent_successfully', [], $data);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], 500);
        }
    }

    public function forgotPasswordOtp(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            // Find the user by OTP
            $user = User::where('otp', $request->otp)->first();

            if (!$user) {
                return $this->responseWithError(__('your_otp_is_incorrect'), [], 404);
            }

            $data =[
                'otp' => $request->otp,
            ];

            DB::commit();

            return $this->responseWithSuccess(__('successfully_verified'), [], $data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError($e->getMessage(), [], 500);
        }
    }


    public function PostResetPassword(Request $request)
    {
        try {
            $validator                  = Validator::make($request->all(), [
                'password'              => 'confirmed|required|min:5|max:10',
                'password_confirmation' => 'required|min:5|max:10'
                ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $user           = User::byEmail($request->email);

            if ($user->otp == $request->otp) {
                $user->password                 = bcrypt($request->password);
                $user->last_password_change     = date('Y-m-d H:i:s');

                $data = [
                    'subject'        => 'Recovery Mail',
                    'user'           => $user,
                    'password'       => $request->password,
                    'template_title' => 'Recovery_mail',
                ];

                // Send a mail to user
                $result = $this->sendMail($user->email, 'admin.auth.mail.reset-success-email', $data);
                $user->save();

                return $this->responseWithSuccess('password_reset_successfully', [], 200);
            } else {
                return $this->responseWithError('Invalid reset code', [], 400);
            }
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], 500);
        }
    }

    public function privacyPolicy()
    {

        $data =[
            'privacy_policy' => setting('merchant_privacy_policy'),
        ];

        return $this->responseWithSuccess(__('successfully_retrived_privacy_policy'), [], $data, 200);
    }


}
