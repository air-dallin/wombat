<?php

namespace App\Services;

use App\Helpers\CryptHelper;
use App\Models\Token;

class DibankService
{

    const STATUS_WAIT = 0;
    const STATUS_CONFIRM = 1;
    const STATUS_BIND = 2;
    const STATUS_SIGN = 3;

    const DOC_STATUS_WAIT = 0;
    const DOC_STATUS_CREATE = 2;
    const DOC_STATUS_SIGN = 3;
    const DOC_STATUS_SEND = 4;
    const DOC_STATUS_CHECK = 5;
    const DOC_STATUS_CONFIRM = 1;

    const DOC_TYPE_PAYMENT = 101;
    const DOC_TYPE_PAYMENT_ONLINE = 102;

    public static function login(&$dibank){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "method": "logon",
                "login": "'.$dibank->getLogin().'",
                "password": "'.$dibank->getPassword().'"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.206.0.7',
                'Content-Type: text/plain'
            ),
        ));

        $response = json_decode(curl_exec($curl),true);

        curl_close($curl);

        return $response;

    }

    public static function createProfile(&$dibank){

        Elog::save('createProfile');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/user',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "method": "register",
                "login": "'.$dibank->getLogin().'",
                "password": "'.$dibank->getPassword().'",
                "firstName": "'.$dibank->firstname.'",
                "lastName": "'.$dibank->lastname.'",
                "email": "'.$dibank->email.'",
                "phoneNumber": "'.$dibank->phone.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.206.0.7',
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        Elog::save('response');
        Elog::save($response);

        if(!empty($response) && $response['success']) {
            $response_login = self::login($dibank);
            if(!empty($response_login) && $response_login['success']) {
                $response_bind = self::bindAccount($dibank,$response_login['user-key']);
                if(!empty($response_bind) && $response_bind['success']){
                    $dibank->update(['user_key'=>$response_login['user-key'],'expire'=>date('Y-m-d H:i:s',time()+21600),'response_bind'=>json_encode($response_bind,JSON_UNESCAPED_UNICODE),'account_status'=>self::STATUS_BIND]);
                    $result = $response_login;
                }
            }

        }else{
            $result = false;
        }

        return $result;

    }

    public static function bindAccount(&$dibank,$user_key){
        Elog::save('bindAccount');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "method": "bind_acc",
                "bank_client_login": "'.$dibank->getLogin().'",
                "bank_client_password": "'.$dibank->getPassword().'",
                "confirm": false,
                "mfo": "'.$dibank->company->mfo.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.213.0.17',
                'user-key: ' . $user_key,
                'X_CURRENT_COMPANY_TIN: ' .$dibank->company->inn,
                'X_CURRENT_INTERFACE: WEB',
                'X_CLIENT_REMOTE_DEVICE: Postman test',
                'X_CURRENT_PARTNER: ' .$dibank->partner_key,
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl),true);
        curl_close($curl);
        Elog::save('response');
        Elog::save($response);
        return $response;


    }


    public static function signAccount($filter,&$dibank,$user_key){
        Elog::save('signAccount');

        $curl = curl_init();
        $method = !empty($filter['method']) ? $filter['method'] : 5;
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "method": "sign_bind_acc",
                "sign": {
                    "method": '.$method .',
                    "sert_num": "'.$filter['serial'].'",
                    "signature": "'.$filter['signature'] .'"
                },
                "bank_client_login": "'.$dibank->getLogin().'",
                "bank_client_password": "'.$dibank->getPassword().'",
                "mfo": "'.$dibank->company->mfo.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.213.0.17',
                'user-key: ' . $user_key,
                'X_CURRENT_COMPANY_TIN: ' .$dibank->company->inn,
                'X_CURRENT_INTERFACE: WEB',
                'X_CLIENT_REMOTE_DEVICE: Postman test',
                'X_CURRENT_PARTNER: ' .$dibank->partner_key,
                'Content-Type: application/json'
            ),
        ));
        $response = json_decode(curl_exec($curl),true);
        curl_close($curl);
        Elog::save('response');
        Elog::save($response);
        return $response;


    }

    public static function confirmAccount($filter,&$dibank,$user_key){
        Elog::save('confirmAccount');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "method": "bind_acc_confirm",
                "bank_client_login": "'.$dibank->getLogin().'",
                "bank_client_password": "'.$dibank->getPassword().'",
                "mfo": "'.$dibank->company->mfo.'"
                "sms_code": "'.$filter['sms'].'"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.213.0.17',
                'user-key: ' . $user_key,
                'X_CURRENT_COMPANY_TIN: ' .$dibank->company->inn,
                'X_CURRENT_INTERFACE: WEB',
                'X_CLIENT_REMOTE_DEVICE: Postman test',
                'X_CURRENT_PARTNER: ' .$dibank->partner_key,
                'Content-Type: application/json'
            ),
        ));
        $response = json_decode(curl_exec($curl),true);
        curl_close($curl);
        Elog::save('response');
        Elog::save($response);
        return $response;


    }

    public static function createDocument($filter,$data,&$dibank){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => !empty($data)?$data:null,
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.213.0.17',
                'user-key: ' . $dibank->user_key,
                'X_CURRENT_COMPANY_TIN: ' .$filter['company_inn'],
                'X_CURRENT_INTERFACE: WEB',
                'X_CLIENT_REMOTE_DEVICE: Postman test',
                'X_CURRENT_PARTNER: ' . $dibank->partner_key,
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl),true);
        curl_close($curl);

        return $response;

    }

    public static function signDocument($filter,&$dibank){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "method": "getstringtosign",
                "documentId": '.$filter['document_id'].'
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.213.0.17',
                'user-key: ' . $dibank->user_key,
                'X_CURRENT_COMPANY_TIN: ' .$filter['company_inn'],
                'X_CURRENT_INTERFACE: WEB',
                'X_CLIENT_REMOTE_DEVICE: Postman test',
                'X_CURRENT_PARTNER: ' . $dibank->partner_key,
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl),true);

        curl_close($curl);
        return $response;

    }

    public static function sendDocument($filter,&$dibank){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "method": "senddoc",
                "documentId": '.$filter['document_id'].',
                "signature": '.$filter['signature'].',
                "serialNumber": '.$filter['serial'] .'
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.213.0.17',
                'user-key: ' . $dibank->user_key,
                'X_CURRENT_COMPANY_TIN: ' .$filter['company_inn'],
                'X_CURRENT_INTERFACE: WEB',
                'X_CLIENT_REMOTE_DEVICE: Postman test',
                'X_CURRENT_PARTNER: ' . $dibank->partner_key,
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl),true);

        curl_close($curl);
        return $response;

    }

    public static function checkDocuments($filter,&$dibank){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "method": "checkdoc",
                "documentIds": ['.implode(',',$filter['document_ids']).']
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.213.0.17',
                'user-key: ' . $dibank->user_key,
                'X_CURRENT_COMPANY_TIN: ' .$filter['company_inn'],
                'X_CURRENT_INTERFACE: WEB',
                'X_CLIENT_REMOTE_DEVICE: Postman test',
                'X_CURRENT_PARTNER: ' . $dibank->partner_key,
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl),true);

        curl_close($curl);
        return $response;

    }

    public static function checkDocumentsConfirm($filter,&$dibank){

        $curl = curl_init();
        $method = !empty($filter['method']) ? $filter['method'] : 5;

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi2.dibank.uz:447/api/v1/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "method": "checkdoc_confirm",
                "documentIds": ['.implode(',',$filter['document_ids']).'],
                "sign": {
                    "method": '.$method .',
                    "sert_num": "'.$filter['serial'].'",
                    "signature": "'.$filter['signature'] .'"
                },
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Real-Ip: 210.213.0.17',
                'user-key: ' . $dibank->user_key,
                'X_CURRENT_COMPANY_TIN: ' .$filter['company_inn'],
                'X_CURRENT_INTERFACE: WEB',
                'X_CLIENT_REMOTE_DEVICE: Postman test',
                'X_CURRENT_PARTNER: ' . $dibank->partner_key,
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl),true);

        curl_close($curl);
        return $response;

    }
    public static function checkExpire(&$dibank){
        if(time()>$dibank->expire){
            $response = self::login($dibank);
            $result = $response['user-key'];
            $dibank->update(['user_key'=>$result,'expire'=>date('Y-m-d H:i:s',time()+21600)]);
        }else{
            $result = $dibank->user_key;
        }
        return $result;
    }

    public static function getStatusLabel($status){
        switch($status){
            case 0:
                return '<label class="badge badge-primary bg-primary">'.__('main.draft') .'</label>';
            case 1:
                return '<label class="badge badge-warning bg-warning">'.__('main.ready_send') .'</label>';
            case 2:
                return '<label class="badge badge-warning bg-warning">'.__('main.sended') .'</label>';
            case 3:
                return '<label class="badge badge-success bg-success">'.__('main.confirmed') .'</label>';
            case 4:
                return '<label class="badge badge-danger bg-danger">'.__('main.completed') .'</label>';
            case 5:
                return '<label class="badge badge-danger bg-danger">'.__('main.rejected') .'</label>';
            case 6:
                return '<label class="badge badge-warning bg-warning">'.__('main.wait') .'</label>';
            case 7:
                return '<label class="badge badge-success bg-success">'.__('main.expired') .'</label>';
            case 8:
                return '<label class="badge badge-danger bg-danger">'.__('main.received') .'</label>';
            case -1:
                return '<label class="badge badge-warning bg-warning">'.__('main.ready_delete') .'</label>';
            case -2:
                return '<label class="badge badge-warning bg-warning">'.__('main.deleted') .'</label>';
            case -3:
                return '<label class="badge badge-warning bg-warning">'.__('main.error_send') .'</label>';
            case -5:
            default:
                return '<label class="badge badge-default">'.__('main.unknown') .'</label>';
        }
    }


}
