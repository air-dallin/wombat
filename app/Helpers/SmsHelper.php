<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class SmsHelper {

    const SMS_SEND_SUCCESS = 'Request is received';

    public static function generateCode(){
        return rand(0,9) . rand(0,9) .rand(0,9) .rand(0,9);
    }

    /**
     * @param string $phone
     * @param string $text
     * @return bool|string
     */
    public static function sendSms($phone, $text){

        $phone = correct_phone($phone);

        return self::SMS_SEND_SUCCESS;

        //exit;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://91.204.239.44/broker-api/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "messages": [
                    {
                        "recipient": "'.$phone.'",
                        "message-id": "wom-'.time().'",
                        "sms": {
                            "originator": "3700",
                            "content": {
                                "text": "'.$text.'"
                            }
                        }
                    }
                ]
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic YWtpczpHKCpUM3M5ZHVYN0A=',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;


    }



}
