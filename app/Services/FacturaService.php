<?php

namespace App\Services;

use App\Helpers\Elog;
use App\Models\Token;
use App\Models\User;

class FacturaService
{

    public static $test = false;

    public static function getToken(){

        ELog::save('FacturaService::getToken');

        if(self::$test) return ['access_token'=>true,'expires_in'=>time()+10000000];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://stagingaccount.faktura.uz/Token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=password&username=998946942741&password=123&client_id=FakturaClient&client_secret=LHtFamC41s8VGVUjlkWiWldvgtBvgH0uhRJ5CBroYpwFEYeuMbm96lOtdUBh',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        ELog::save('FACTURA RESPONSE getToken:');
        ELog::save($response);

        curl_close($curl);
        $result = json_decode($response,true);

        self::refreshToken($result);
        return $result;


    }

    public static function refreshToken($result){
        ELog::save('refresh token');

        if($token = Token::where(['service'=>'factura'])->first()){
            $token->token = $result['access_token'];
            $token->expire = time() + $result['expires_in'];
            $token->save();
        }else {
            Token::create([
                'service' => 'factura',
                'token' => $result['access_token'],
                'expire' => time() + $result['expires_in']
            ]);
        }
        return true;

    }

    public static function getCompanyInfo($inn,$token=null){

        ELog::save('FacturaService::getCompanyInfo');

        if(self::$test) {
            return json_decode('{
            "CompanyInn": "309868581",
            "Pinfl": null,
            "CompanyName": "\"GEOTEHNOLOGY BUSINESS\" MCHJ",
            "CompanyAddress": "Байналминал МФЙ, Нукус кучаси, 85/1-уй  ",
            "PhoneNumber": null,
            "Email": null,
            "VatCode": "303010203295",
            "SpecialAccount": "20208000405568384001",
            "Accounts": [
                {
                    "BankName": "ТОШКЕНТ Ш., \"КАПИТАЛБАНК\" АТ БАНКИНИНГ ТОШКЕНТ ШАХАР ФИЛИАЛИ",
                    "BankMfo": "00445",
                    "AccountCode": "20208000405568384001",
                    "IsPrimary": true
                }
            ],
            "DirectorInn": null,
            "DirectorPinfl": "30605725000015",
            "DirectorName": "KORNILTSEV ALEKSANDR XXX",
            "Accountant": null,
            "Oked": "46690",
            "TaxGap": null,
            "TaxPayerTypeName": "Плательщик НДС+ (сертификат активный)",
            "Branches": []
        }', true);

        }

        if(empty($token)) {
            $result = self::getToken();
            $token = $result['access_token'];
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://stagingapi.faktura.uz/Api/Company/GetCompanyBasicDetails?companyInn=' . $inn,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);
        ELog::save('FACTURA RESPONSE:');
        ELog::save($response);

        curl_close($curl);
        return json_decode($response,true);

    }

    public static function checkTokenExpire(){

        $result = ['access_token' => '','expires_in'=>0];

        if($token = Token::where(['service'=>'factura'])->first()){
            $result = [
                'access_token' => $token->token,
                'expires_in' => $token->expire
            ];
        }

        return time() > $result['expires_in'] ? self::getToken() : ['access_token'=>$result['access_token'],'expires_in'=>$result['expires_in']];
    }


    public static function getPrimaryAccount($companyInfo){
        if(!empty($companyInfo['Accounts'])){
            foreach ($companyInfo['Accounts'] as $account){
                if($account['IsPrimary']) {
                    $result = ['code'=>$account['AccountCode'],'name'=>$account['BankName'],'mfo'=>$account['BankMfo']];
                    break;
                }
            }
        }else{
            if(!empty($companyInfo['SpecialAccount'])){
                $result = [
                    'code' => $companyInfo['SpecialAccount'],
                    'name' => '',
                    'mfo' => ''
                ];
            }
        }

        return $result ;

    }

    public static function getPrimaryAccountBankMfo($companyInfo,$accountCode){
        $result = '';
        if(!empty($companyInfo['Accounts'])){
            foreach ($companyInfo['Accounts'] as $account){
                if($account['AccountCode']==$accountCode) {
                    $result = $account['BankMfo'];
                    break;
                }
            }
        }

        return $result ;

    }



}
