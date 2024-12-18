<?php

namespace App\Traits;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;
use App\Models\SmsTemplate;
use App\Models\SMSHistory;
use App\Models\SMSUsages;
use App\Models\SMSCredit;
use Twilio\Rest\Client;
use Vonage\SMS\Message\SMS;

trait SmsSenderTrait
{
    public function test($sms_body, $phone_number, $template_id, $provider, $masking = false): bool
    {

        if (env('VERIENT') == "us" || (setting('total_credit') > setting('total_usages'))):
            if ($this->send($sms_body, $phone_number, $template_id, $provider, true)):
                return true;
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }


    public function send($sms_body, $phone_number, $template_id = '', $provider = '', $masking = false)
    {
        // Output the character count for debugging purposes
        $provider = $provider != '' ? $provider : env('PROVIDER');
        if ($provider == 'twilio') {
            $sid    = env('TWILIO_SMS_AUTH_TOKEN');
            $token  = env('TWILIO_SMS_AUTH_TOKEN');
            $client = new Client($sid, $token);

            try {
                $client->messages->create(
                    $phone_number,
                    [
                        'from' => env('TWILIO_SMS_NUMBER'),
                        'body' => $sms_body,
                    ]
                );



                return true;
            } catch (\Exception $e) {
                return $e->getMessage();
            }

        } elseif ($provider == 'nexmo') {

            try {
                $basic    = new \Vonage\Client\Credentials\Basic(env('NEXMO_SMS_KEY'), env('NEXMO_SMS_SECRET_KEY'));
                $client   = new \Vonage\Client($basic);
                $response = $client->sms()->send(
                    new SMS($phone_number, BRAND_NAME, $sms_body)
                );
                $message  = $response->current();

                if ($message->getStatus() == 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } elseif ($provider == 'spagreen') {
            $phone_number = preg_replace('/^(\+88|88)/', '', $phone_number);
            $phone_number = preg_replace('/-/', '', $phone_number);
            $phone_number = preg_replace('/(\s)/', '', $phone_number);

            $url          = env('SPAGREEN_SMS_URL') ? env('SPAGREEN_SMS_URL') : 'https://smpp.ajuratech.com:7790/sendtext'; //http://apismpp.revesms.com

            $params       = [
                'apikey'         => env('SPAGREEN_SMS_KEY'),
                'secretkey'      => env('SPAGREEN_SECRET_KEY'),
                'callerID'       => env('SPAGREEN_SENDER_ID') ? : 'SENDER_ID',
                'toUser'         => is_array($phone_number) ? implode(',', $phone_number) : $phone_number,
                'messageContent' => $sms_body,
            ];

            $ch           = \curl_init();

            $data         = http_build_query($params);
            $getUrl       = $url.'?'.$data;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 80);

            $result       = \curl_exec($ch);

            \curl_close($ch);
            if ($success = str_contains($result, 'ACCEPTD')) {
                if(env('VERIENT') != "global"):
                    $this->smsHistory($result, $sms_body, $phone_number);
                endif;
                return true;
            } else {
                return false;
            }

        } elseif ($provider == 'mimo') {
            $token = $this->getToken();
            $this->sendMessage($phone_number, $sms_body, $token);
            $this->logout($token);

        } elseif ($provider == 'ssl' || $provider == 'ssl_wireless') {

            $token    = env('SSL_SMS_API_TOKEN');
            $sid      = env('SSL_SMS_SID');

            $data     = [
                'api_token' => $token,
                'sid'       => $sid,
                'msisdn'    => is_array($phone_number) ? implode(',', $phone_number) : $phone_number,
                'sms'       => $sms_body,
                'csms_id'   => date('dmYhhmi').rand(10000, 99999),
            ];

            $url      = env('SSL_SMS_URL');
            $data     = json_encode($data);

            $ch       = \curl_init();
            \curl_setopt($ch, CURLOPT_URL, $url);
            \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            \curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            \curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: '.strlen($data),
                'accept:application/json',
            ]);

            $response = \curl_exec($ch);

            \curl_close($ch);

            return true;

        } elseif ($provider == 'fast2') {
            if (strpos($phone_number, '+91') !== false) {
                $phone_number = substr($phone_number, 3);
            }

            if (env('fast_2_route') == 'dlt_manual') {
                $fields = [
                    'sender_id'   => env('fast_2_sender_id'),
                    'message'     => $sms_body,
                    'template_id' => $template_id,
                    'entity_id'   => env('fast_2_entity_id'),
                    'language'    => env('fast_2_language'),
                    'route'       => env('fast_2_route'),
                    'numbers'     => is_array($phone_number) ? implode(',', $phone_number) : $phone_number,
                ];
            } else {
                $fields = [
                    'sender_id' => env('fast_2_sender_id'),
                    'message'   => $sms_body,
                    'language'  => env('fast_2_language'),
                    'route'     => env('fast_2_route'),
                    'numbers'   => is_array($phone_number) ? implode(',', $phone_number) : $phone_number,
                ];
            }

            $auth_key = env('fast_2_auth_key');

            $curl     = \curl_init();

            \curl_setopt_array($curl, [
                CURLOPT_URL            => 'https://www.fast2sms.com/dev/bulkV2',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => json_encode($fields),
                CURLOPT_HTTPHEADER     => [
                    "authorization: $auth_key",
                    'accept: */*',
                    'cache-control: no-cache',
                    'content-type: application/json',
                ],
            ]);

            $response = \curl_exec($curl);
            $err      = \curl_error($curl);

            \curl_close($curl);

            return true;
        }
    }

    public function getToken()
    {
        $curl     = \curl_init();

        \curl_setopt_array($curl, [
            CURLOPT_URL            => '52.30.114.86:8080/mimosms/v1/user/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => '{
                "username": "'.env('MIMO_USERNAME').'",
                "password": "'.env('MIMO_SMS_PASSWORD').'"
            }',
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
            ],
        ]);


        $response = \curl_exec($curl);

        \curl_close($curl);

        return json_decode($response)->token;

    }

    public function sendMessage($phone_number, $sms_body, $token): bool
    {
        $curl     = \curl_init();

        $fields   = [
            'sender'     => env('MIMO_SMS_SENDER_ID'),
            'text'       => $sms_body,
            'recipients' => $phone_number,
        ];
        // dd($to);
        \curl_setopt_array($curl, [
            CURLOPT_URL            => '52.30.114.86:8080/mimosms/v1/message/send?token='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($fields),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
            ],
        ]);

        $response = \curl_exec($curl);

        \curl_close($curl);

        return true;
    }

    public function logout($token): bool
    {
        $curl     = \curl_init();

        \curl_setopt_array($curl, [
            CURLOPT_URL            => '52.30.114.86:8080/mimosms/v1/user/logout?token='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
        ]);

        $response = \curl_exec($curl);

        \curl_close($curl);

        return true;
    }

    public function sendSMS($phone_number, $key, $otp): bool|string|null
    {
        $sms_template = SmsTemplate::where('key', $key)->first();
        $tags         = ['{otp}', '{site_name}', '{phone_no}'];
        $replace      = [$otp, setting('system_name'), $phone_number];
        $sms_body     = str_replace($tags, $replace, @$sms_template->body);

        return $this->send($phone_number, $sms_body, $sms_template->template_id);
    }

    public function smsHistory($result, $sms_body, $phone_number)
    {
        $decoded_result                             = json_decode($result, true);
        $message_id                                 = $decoded_result['Message_ID'] ?? null;

        if ($result):
            $sms_length                             = strlen($sms_body);

            if ($sms_length <= 164):
                $count                              = 1;
            else:
                $count                              = 2;
            endif;

            $sms_history                            = new SMSHistory();
            $sms_history->to                        = $phone_number;
            $sms_history->message_id                = $message_id;
            $sms_history->message                   = $sms_body;
            $sms_history->count                     = $count;
            $sms_history->status                    = 'approved';
            $sms_history->save();

            $sms_calculate                          = Setting::where('title', 'total_usages')->first();

            if ($sms_calculate):
                $sms_calculate->value += $count;
            else:
                $sms_calculate                 = new Setting();
                $sms_calculate->title          = 'total_usages';
                $sms_calculate->value          = $count;
                $sms_calculate->lang           =  'en';

            endif;

            $sms_calculate->save();
        endif;

        Artisan::call('optimize:clear');

        return true;
    }


}
