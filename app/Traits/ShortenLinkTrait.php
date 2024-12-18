<?php

namespace App\Traits;

use Exception;

trait ShortenLinkTrait {
    public function get_link($parcel_no) {
        try {
            $data['url']        = url('/tracking/'.$parcel_no);
            $data['type']       = 'direct';
            $data['parameters'] = '[
                                {
                                    "name": "aff",
                                    "value": "3"
                                },
                                {
                                    "device": "gtm_source",
                                    "link": "api"
                                }
                            ]';

            $curl = \curl_init();


            \curl_setopt_array($curl, array(
                CURLOPT_URL                 => "https://6l.ink/api/url/add",
                CURLOPT_RETURNTRANSFER      => true,
                CURLOPT_ENCODING            => "",
                CURLOPT_MAXREDIRS           => 2,
                CURLOPT_TIMEOUT             => 10,
                CURLOPT_FOLLOWLOCATION      => true,
                CURLOPT_CUSTOMREQUEST       => "POST",
                CURLOPT_SSL_VERIFYHOST      => false,
                CURLOPT_SSL_VERIFYPEER      => false,
                CURLOPT_HTTPHEADER          => array(
                    "Authorization: Bearer 6cafaa2cb33ff651bf2c031c605f718b",
                    "Content-Type: application/json",
                ),
                CURLOPT_POSTFIELDS          => json_encode($data),
            ));

            $response = \curl_exec($curl);
            \curl_close($curl);

            $response = json_decode($response);


            if ($response->error == 0) :
                return $response->shorturl;
            else:
                return '';
            endif;
        }catch (Exception $e){
            return '';
        }
    }
}
