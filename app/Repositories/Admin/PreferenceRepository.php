<?php

namespace App\Repositories\Admin;

use App\Traits\CommonHelperTrait;
use App\Traits\SmsSenderTrait;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Interfaces\Admin\PreferenceInterface;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Image;
use Sentinel;
use SoapClient;

class PreferenceRepository implements PreferenceInterface
{
    use CommonHelperTrait;
    use ImageTrait;

    public function update($request): bool
    {
        try{

            $result = $this->updateSmsProviderSettings($request);
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function statusChange($request): bool
    {
        try{
            $this->updateSmsProviderSettings($request);
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    private function updateSmsProviderSettings($request)
    {

        $sms_provider = $request['provider'];
        switch ($sms_provider) {
            case 'twilio':
                envWrite('TWILIO_SMS_SID', $request['twilio_sms_sid']);
                envWrite('TWILIO_SMS_AUTH_TOKEN', $request['twilio_sms_auth_token']);
                envWrite('TWILIO_SMS_NUMBER', $request['valid_twilio_sms_number']);
                break;
            case 'fast2':
                envWrite('FAST_2_AUTH_KEY', $request['fast_2_auth_key']);
                envWrite('FAST_2_ENTITY_ID', $request['fast_2_entity_id']);
                envWrite('FAST_2_ROUTE', $request['fast_2_route']);
                envWrite('FAST_2_LANGUAGE', $request['fast_2_language']);
                envWrite('FAST_2_SENDER_ID', $request['fast_2_sender_id']);
                break;
            case 'spagreen':
                envWrite('SPAGREEN_SMS_KEY', $request['spagreen_sms_api_key']);
                envWrite('SPAGREEN_SECRET_KEY', $request['spagreen_secret_key']);
                envWrite('SPAGREEN_SENDER_ID',$request['spagreen_sender_id']);
                envWrite('SPAGREEN_SMS_URL', $request['spagreen_sms_url']);
                break;
            case 'mimo':
                envWrite('MIMO_USERNAME', $request['mimo_username']);
                envWrite('MIMO_SMS_PASSWORD', $request['mimo_username']);
                envWrite('MIMO_SMS_SENDER_ID', $request['mimo_sms_sender_id']);
                break;
            case 'nexmo':
                envWrite('NEXMO_SMS_KEY', $request['nexmo_sms_key']);
                envWrite('NEXMO_SMS_SECRET_KEY', $request['nexmo_sms_secret_key']);
                break;
            case 'ssl':
                envWrite('SSL_SMS_API_TOKEN', $request['ssl_sms_api_token']);
                envWrite('SSL_SMS_URL', $request['ssl_sms_sid']);
                envWrite('SSL_SMS_SID', $request['ssl_sms_url']);
                break;
        }

        envWrite('PROVIDER', $request->data['value']);
        return true;
    }
}
