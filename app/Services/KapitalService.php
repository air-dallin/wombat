<?php
namespace App\Services;

use App\Helpers\CryptHelper;
use App\Helpers\Elog;
use App\Models\Kapital;

class KapitalService {

    const DIR_INNER = 0;    // внутренний
    const DIR_OUTGOING = 1; // входящий
    const DIR_INCOMING = 2; // исходящий

    public static function login(&$kapital){
        Elog::save('KapitalService::login for kapital_id:' .$kapital->id . ' company_id: '. $kapital->company_id,'cron_kapital');
        $kapitalLogin = $kapital->getLogin();
        $kapitalPassword = $kapital->getPassword();
        $sms = !empty($kapital->sms_code)?':'.$kapital->sms_code:'';

        if($kapitalLogin=='demo' && $kapitalPassword=='demo'){
            $auth = base64_encode( $kapitalLogin . ':' . $kapitalPassword);
            $url = 'https://m.bank24.uz:2777/Mobile.svc/APILogin';
        }else {
            $auth = base64_encode('IB#' . $kapitalLogin . ':' . $kapitalPassword . $sms);
            $url = 'https://m.bank24.uz:2713/Mobile.svc/APILogin';
        }

        Elog::save('KapitalService::auth:' .$auth,'cron_kapital');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => null,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic '. $auth
            ),
        ));

        $response = curl_exec($curl);
        Elog::save('response:','cron_kapital');
        Elog::save($response,'cron_kapital');

        $json = json_decode($response);
        if(empty($json->error) && isset($json->result)) {
            $expire = explode(':',$json->result->sid);
            $kapital->update(['response' => $response, 'client_id' => $json->result->clients[0]->id,'sid'=>CryptHelper::encrypt($json->result->sid),'expire'=>$expire[0]]);
        }

        curl_close($curl);

        return $json; // json_decode($response);

    }

    public static function getDocuments($data){
        Elog::save('KapitalService::getDocuments','cron_kapital');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://m.bank24.uz:2713/Mobile.svc/GetDoc1C',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode( curl_exec($curl),true);

        Elog::save('response:','cron_kapital');
        Elog::save($response,'cron_kapital');

        curl_close($curl);
        return $response;
    }

    public static function getAccounts(&$kapital,$data){
        Elog::save('KapitalService::getAccounts','cron_kapital');
        $errors = [];
        if(empty($data['branch'])) $errors[] = _('main.branch_not_set');
        if(empty($data['account'])) $errors[] = _('main.account_not_set');
        if(empty($data['date'])) $errors[] = _('main.date_not_set');
        if(KapitalService::isExpire($kapital)){
            $res = KapitalService::login($kapital);
        }

        if($errors){
            return ['status'=>false,'errors'=>implode(', ',$errors)];
        }

        $data['sid'] = $kapital->getSid();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://m.bank24.uz:2713/Mobile.svc/GetAcc1C',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);


        Elog::save('response:','cron_kapital');
        Elog::save($response,'cron_kapital');

        return $response;
    }

    /** создание поручения */
    public static function sendPaymentIB($document){
        Elog::save('KapitalService::sendPaymentIB','cron_kapital');

        $document['payment']['signs'] = [];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://m.bank24.uz:2713/Mobile.svc/SendPaymentIBK',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($document,JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        Elog::save('response:','cron_kapital');
        Elog::save($response,'cron_kapital');
        return $response;
    }

    /** отправка поручения */
    public static function sendPayment($document){
        Elog::save('KapitalService::sendPayment','cron_kapital');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://m.bank24.uz:2713/Mobile.svc/SendPayment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($document,JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        Elog::save('response:','cron_kapital');
        Elog::save($response,'cron_kapital');
        return $response;

    }

    /** получение статусов документов kapital
     */
    public static function checkPayment($kapital,$data){
        Elog::save('KapitalService::checkPayment','cron_kapital');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://m.bank24.uz:2713/Mobile.svc/CheckPayments2',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
               "client_id":'.$kapital->client_id.',
               "sid":"'.$kapital->getSid().'",
               "uniqs":'.json_encode($data,JSON_UNESCAPED_UNICODE).'
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        Elog::save('response:','cron_kapital');
        Elog::save($response,'cron_kapital');
        return $response;
    }

    /** проверка срока действия токена */
    public static function isExpire(&$kapital){
        if($kapital->expire < time()+14500){
            return true;
        }
        return false;
    }

    public static function getStatusLabel($status){
        switch($status){
            case 1:
                return '<label class="badge badge-warning bg-warning">'.__('main.ready_send') .'</label>';
            case 2:
                return '<label class="badge badge-warning bg-warning">'.__('main.confirmed') .'</label>';
            case 3:
                return '<label class="badge badge-success bg-success">'.__('main.performed') .'</label>';
            case 54:
                return '<label class="badge badge-success bg-success">'.__('main.processed') .'</label>';
            case 6:
                return '<label class="badge badge-danger bg-danger">'.__('main.deleted') .'</label>';
            case 16:
                return '<label class="badge badge-warning bg-warning">'.__('main.pending') .'</label>';
            case 53:
                return '<label class="badge badge-danger bg-danger">'.__('main.rejected') .'</label>';
            case 55:
                return '<label class="badge badge-danger bg-danger">'.__('main.in_process') .'</label>';
            case 56:
                return '<label class="badge badge-warning bg-warning">'.__('main.waiting') .'</label>';
            case 57:
                return '<label class="badge badge-danger bg-danger">'.__('main.expired') .'</label>';

            case 0:
            default:
                return '<label class="badge badge-default">'.__('main.unknown') .'</label>';
        }
    }

    public static function getTypeLabel($dir){
        switch($dir){
            case 0:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-success-50 px-4 py-1.5 text-sm font-semibold leading-[22px] text-success-400 dark:bg-darkblack-500">'.__('main.inner') .'</label></div>';
            case 1:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FAEFEE] px-4 py-1.5 text-sm font-semibold leading-[22px] text-[#FF4747] dark:bg-darkblack-500">'.__('main.outgoing') .'</label></div>';
            case 2:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-success-50 px-4 py-1.5 text-sm font-semibold leading-[22px] text-success-400 dark:bg-darkblack-500">'.__('main.incoming') .'</label></div>';
            default:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FAEFEE] px-4 py-1.5 text-sm font-semibold leading-[22px] text-[#FF4747] dark:bg-darkblack-500">'.__('main.unknown') .'</label></div>';
        }
    }




}
