<?php

namespace App\Http\Controllers\Core;

use App\Helpers\TelegramHelper;
use App\Helpers\PaymentHelper;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\BuyerSetting;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class PaymeController extends Controller{
    //
    const ERROR_INTERNAL_SYSTEM         = -32400;
    const ERROR_INSUFFICIENT_PRIVILEGE  = -32504;
    const ERROR_INVALID_JSON_RPC_OBJECT = -32600;
    const ERROR_METHOD_NOT_FOUND        = -32601;
    const ERROR_INVALID_AMOUNT          = -31001;
    const ERROR_TRANSACTION_NOT_FOUND   = -31003;
    const ERROR_INVALID_ACCOUNT         = -31050;
    const ERROR_COULD_NOT_CANCEL        = -31007;
    const ERROR_COULD_NOT_PERFORM       = -31008;


    // данный url задается в кабинете payme
    public function init(){

        $requestBody = file_get_contents('php://input');
        $request = json_decode($requestBody,true);

        Log::channel('payme')->info($requestBody);

        $key = 'Paycom:B%&FRwgeKp%rTxNYKrF7ocfqcAx&b8qweq2we'; // ключ прод

        // authorize session
        $headers = getallheaders();

        if (!$headers || !isset($headers['Authorization']) ||
            !preg_match('/^\s*Basic\s+(\S+)\s*$/i', $headers['Authorization'], $matches) ||
            base64_decode($matches[1]) != $key
        ){
            Log::channel('payme')->error('Ошибка -32504 Не достаточно привелегий при входе');
            $arr_error = array('ru'=>'Недостаточно привелегий при входе','en'=>'Not enough privileges to execute method','uz'=>'Usulni bajarishga huquqlar etarli emas');
            return $this->error(
                self::ERROR_INSUFFICIENT_PRIVILEGE,
                $arr_error,
                $request['method']
            );
        }
        // handle request
        switch ($request['method']) {
            case 'CheckPerformTransaction':
                return $this->CheckPerformTransaction($request);
                break;
            case 'CheckTransaction':
                return $this->CheckTransaction($request);
                break;
            case 'CreateTransaction':
                return $this->CreateTransaction($request);
                break;
            case 'PerformTransaction':
                return $this->PerformTransaction($request);
                break;
            case 'CancelTransaction':
                return $this->CancelTransaction($request);
                break;
            case 'ChangePassword':
                return $this->ChangePassword($request);
                break;
            default:
                Log::channel('payme')->warning('Ошибка 32601 не верно указан метод при инициализации');
                $arrError = array('ru'=>'Метод не найден','en'=>'Method doesnt exist','uz'=>'Amaliyotni bajarib bo`lmadi');
                return $this->error(
                    self::ERROR_METHOD_NOT_FOUND,
                    $arrError,
                    $request['method']
                );
                break;
        }
    }

    private function CheckPerformTransaction($request){
        Log::channel('payme')->info("CheckPerformTransaction: \n Request: \n".print_r($request, 1));
        //Проверка на лицевой счет
        $buyer = User::where('phone',$request['params']['account']['Phone'])->where('status',User::STATUS_ACTIVE)->first();
        if($buyer == null) {
            Log::channel('payme')->error('Ошибка -31050 Лицевой счет не найден');
            $arrError = array('ru'=>'Ввведеные данные не корректны','en'=>'Entered data is not correct','uz'=>'Kiritilgan ma`lumotlar noto`g`ri');
            return $this->error(
                self::ERROR_INVALID_ACCOUNT,
                $arrError
            );
        }

        $amount = $request['params']['amount']/100; // 27.05 переводим из тийинов в суммы

        // Если сумма меньше 1000 сум ошибка

        if($amount<1000){
            $arrError = array('ru'=>'Ошибка суммы недостаточно','en'=>'Error invalid amount','uz'=>'Transakciya topilmadi');
            return $this->error(
                self::ERROR_INVALID_AMOUNT,
                $arrError
            );
        }
        //Если сумма и лицевой счет сходится то отправляем true

        $response = [];
        $response['jsonrpc'] = '2.0';
        $response['result']['allow']  = true;
        return $response;

    }

    private function CheckTransaction( $request ){
        Log::channel('payme')->info("CheckTransaction: \n Request: \n".print_r($request, 1));
        // Проверка отправлены ли все параметры
        if(!isset($request['params']['id'])){
            $arrError = array('ru'=>'Невозможно выполнить операцию','en'=>'Unable to complete the operation','uz'=>'Amaliyotni bajarib bo`lmadi');
            Log::channel('payme')->error('Error -31008 in request from payme');
            return $this->error(
                self::ERROR_COULD_NOT_PERFORM,
                $arrError
            );

        }

        $paymeTransact = Payment::where('transaction_id', $request['params']['id'])->where('payment_system', 'PAYME')->where('amount','>', 0)->first();
        Log::channel('payme')->info("CheckTransaction: \n payment: \n".print_r($paymeTransact, 1));

        if($paymeTransact) {

            $response = [];
            $response['jsonrpc'] = '2.0';
            $response['result']['create_time'] = intval($paymeTransact->create_at);

            if($paymeTransact->perform_at == null || $paymeTransact->perform_at == 0){
                $response['result']['perform_time'] = 0;
            }else{
                $response['result']['perform_time'] = strtotime($paymeTransact->perform_at)*1000;
                // $response['result']['perform_time'] = intval($paymeTransact->perform_time);
            }
            if($paymeTransact->cancel_at == null || $paymeTransact->cancel_at == 0){
                $response['result']['cancel_time'] = 0;
            }else{
                $response['result']['cancel_time'] = strtotime($paymeTransact->cancel_at)*1000; // intval
            }
            $response['result']['transaction'] = $paymeTransact->transaction_id;
            $response['result']['state'] = $paymeTransact->state;
            $response['result']['reason'] = $paymeTransact->reason;
            return $response;
        }else{
            $arrError = array('ru'=>'Транзакция не найдена','en'=>'Transaction not found','uz'=>'Transakciya topilmadi');
            //$this->debug('Ошибка -31003 Транзакция не найдена');
            //Log::channel('cards')->warning('Ошибка -31003 Транзакция не найдена');
            Log::channel('payme')->warning('Ошибка -31003 Транзакция не найдена');
            return $this->error(
                self::ERROR_INVALID_ACCOUNT,
                $arrError
            );
        }
    }

    private function CreateTransaction($request){
        Log::channel('payme')->info("CreateTransaction: \n Request: \n".print_r($request, 1));
        // Проверка отправлены все параметры
        if(isset($request['time'])){
            $arrError = array('ru'=>'Невозможно выполнить операцию','en'=>'Unable to complete the operation','uz'=>'Amaliyotni bajarib bo`lmadi');
            Log::channel('payme')->error('Error -31008 in request from payme');
            return $this->error(
                self::ERROR_COULD_NOT_PERFORM,
                $arrError
            );

        }
        $amount = $request['params']['amount']/100; // 27.05 переводим из тийинов в суммы

        // $user = User::where('id', $request['params']['account']['client_id'])->first();
        $user = User::where('phone', $request['params']['account']['Phone'])->where('status',USER::STATUS_ACTIVE)->where('role',User::ROLE_CLIENT)->first();
        if($user == null) {
            Log::channel('payme')->info('Ошибка -31050 Лицевой счет не найден');
            $arrError = array('ru'=>'Ввведеные данные не корректны','en'=>'Entered data is not correct','uz'=>'Kiritilgan ma`lumotlar noto`g`ri');
            return $this->error(
                self::ERROR_INVALID_ACCOUNT,
                $arrError
            );
        }

        //Проверка на лицевой счет

        if($amount<1000){
            $arrError = array('ru'=>'Ошибка, суммы недостаточно','en'=>'Error invalid amount','uz'=>'Transakciya topilmadi');
            return $this->error(
                self::ERROR_INVALID_AMOUNT,
                $arrError
            );
        }

        $payment = Payment::where(['transaction_id'=>$request['params']['id']])->where('payment_system', 'PAYME')->first();

        if($payment == null) {

            //Создаем транзакцию
            $payment = new Payment();
            $payment->tarif_id = null;
            $payment->user_id = $user->id; //$request['params']['account']['Phone']; //$request['params']['account']['client_id'];
            $payment->transaction_id = $request['params']['id'];
            $payment->amount = $amount;
            $payment->type = 'user';
            $payment->payment_system = 'PAYME';
            $payment->state = 1;
            $payment->reason = null;
            $payment->created_at =  time(); //intval($request['params']['time']);
            $payment->create_at = intval($request['params']['time']);
            $payment->perform_time = intval($request['params']['time']);
            $payment->cancel_at = null;
            $payment->perform_at = null;
            $payment->status = 1;

            $payment->save();

            $response = [];
            $response['jsonrpc'] = '2.0';
            $response['result']['create_time'] = isset($request['params']['time']) ? $request['params']['time'] : time()*1000;
            $response['result']['transaction'] = $request['params']['id'];

            $response['result']['state'] = 1;
            return $response;
        }else{

            // Вызов метода "CreateTransaction" с новой транзакцией. Состояние счета: "В ожидании оплаты"
            if($payment!=null && $payment->state!=1){
                $arrError = array('ru'=>'Невозможно выполнить операцию','en'=>'Unable to complete the operation','uz'=>'Amaliyotni bajarib bo`lmadi');
                Log::channel('payme')->error('Error -31008 in request from payme');
                return $this->error(
                    self::ERROR_COULD_NOT_PERFORM,
                    $arrError
                );
            }

            $response = [];
            $response['jsonrpc'] = '2.0';
            $response['result']['create_time'] = isset($request['params']['time']) ? $request['params']['time'] : time()*1000;
            $response['result']['transaction'] = $request['params']['id'];
            $response['result']['state'] = 1;
            return $response;

        }
    }

    private function PerformTransaction($request){
        // Проверка отправлены ли все параметры
        if(!isset($request['params']['id'])){
            Log::channel('payme')->error('Error in request from payme');
            $arrError = array('ru'=>'Невозможно выполнить операцию','en'=>'Unable to complete the operation','uz'=>'Amaliyotni bajarib bo`lmadi');
            return $this->error(
                self::ERROR_COULD_NOT_PERFORM,
                $arrError
            );
        }
        Log::channel('payme')->info("PerformTransaction: \n Request: \n".print_r($request, 1));
        //Поиск транзакции
        $payment = Payment::where('transaction_id', $request['params']['id'])->where('payment_system', 'PAYME')->first();

        // Log::channel('payme')->info("PerformTransaction: \n payment: \n".print_r($payment, 1));

        if($payment != null){
            //оплата клиента
            if($payment->state != 2) {
                if($client = User::where('id', $payment->user_id)->where('status',User::STATUS_ACTIVE)->first()) {
                    $payment->status = 1;

                    if ($payment->perform_at == 0 || $payment->perform_at == NULL) {
                        $payment->perform_at = date('Y-m-d H:i:s', time());
                    }

                    $payment->state = 2;

                    $payment->save();

                }else{
                    $arrError = array('ru'=>'Ввведеные данные не корректны','en'=>'Entered data is not correct','uz'=>'Kiritilgan ma`lumotlar noto`g`ri');
                    Log::channel('payme')->info('Ошибка -31050 Лицевой счет не найден');
                    return $this->error(
                        self::ERROR_INVALID_ACCOUNT,
                        $arrError
                    );
                }
            }else{ //
                Log::channel('payme')->info('ERROR. PAYME не записалось в ЛС user_id: ' . $payment->user_id . ' payment->id' . $payment->id . ' payment->state ' . $payment->state );
                Log::channel('payme')->info($request);
                Log::channel('payme')->error('Error in request from payme');
                TelegramHelper::sendByChatId('1726060082', 'ERROR. PAYME не записалось в ЛС user_id: ' . $payment->user_id . ' payment->id' . $payment->id . ' payment->state ' . $payment->state );

                $arrError = array('ru'=>'Невозможно выполнить операцию','en'=>'Unable to complete the operation','uz'=>'Amaliyotni bajarib bo`lmadi');
                return $this->error(
                    self::ERROR_COULD_NOT_PERFORM,
                    $arrError
                );



            }

        }else{
            $arrError = array('ru'=>'Транзакция не найдена','en'=>'Transaction not found','uz'=>'Transakciya topilmadi');
            Log::channel('payme')->warning('Ошибка -31003 Подготовленная транзакция не найдена');
            return $this->error(
                self::ERROR_TRANSACTION_NOT_FOUND,
                $arrError
            );
        }

        $response = [];
        $response['jsonrpc'] = '2.0';
        $response['result']['transaction'] = $payment->transaction_id;
        $response['result']['perform_time'] =  strtotime($payment->perform_at)*1000; //time()*1000;
        $response['result']['state'] = 2; // было 2
        return $response;
    }

    private function CancelTransaction($request){
        //Поиск транзакции и отмена ее
        Log::channel('payme')->info("CancelTransaction: \n Request: \n".print_r($request, 1));
        $paymeTransact = Payment::where('transaction_id', $request['params']['id'])->first();

        if($paymeTransact != null) { // not found = -31003

            Log::channel('payme')->info("CancelTransaction: \n Model: \n".print_r($paymeTransact, 1));
            if($paymeTransact->cancel_at == NULL || $paymeTransact->cancel_at == 0) {
                $paymeTransact->cancel_at = date('Y-m-d H:i:s', time());
                $paymeTransact->save();
            }

            //Поиск в истории биллинга и отмена неоплаченного платежа
            if($paymeTransact->state > 0){

                if($paymeTransact->state == 1) {
                    $paymeTransact->reason = $request['params']['reason'];
                    $paymeTransact->state = -1;
                    $paymeTransact->save();

                    $cancelTransact = $paymeTransact->replicate();
                    $cancelTransact->amount = (-1*$paymeTransact->amount);
                    $cancelTransact->save();

                    $response = [];
                    $response['state'] = '2.0';
                    $response['result']['transaction'] = $paymeTransact->transaction_id;
                    $response['result']['cancel_time'] = strtotime($paymeTransact->cancel_at)*1000 ;//  intval($paymeTransact->cancel_at);
                    $response['result']['state'] = -1;
                    return $response;

                }elseif($paymeTransact->state == 2){ // 1

                    $paymeTransact->reason = $request['params']['reason'];
                    $paymeTransact->state = -2;
                    $paymeTransact->save();

                    $cancelTransact = $paymeTransact->replicate();
                    $cancelTransact->save();
                    $cancelTransact->amount = (-1 * $paymeTransact->amount);
                    $cancelTransact->save();

                    $response = [];
                    $response['state'] = '2.0';
                    $response['result']['transaction'] = $paymeTransact->transaction_id;
                    $response['result']['cancel_time'] = strtotime($paymeTransact->cancel_at)*1000; //intval($paymeTransact->cancel_at);
                    $response['result']['state'] = -2;
                    return $response;
                    /*}else{
                        $arrError = array('ru'=>'Ошибка суммы для возврата недостаточно','en'=>'Error invalid amount','uz'=>'Transakciya topilmadi');
                        //$this->debug('Ошибка -31003 транзакция в методе CanselTransaction не найдена');
                        Log::channel('payme')->warning('Ошибка -31001 сумма для метода CanselTransaction не достаточна');
                        return $this->error(
                            self::ERROR_COULD_NOT_CANCEL, //self::ERROR_INVALID_AMOUNT,
                            $arrError
                        );
                    } */
                }

            }else { // повторный вызов CancelTransaction
                $response = [];
                $response['state'] = '2.0';
                $response['result']['transaction'] = $paymeTransact->transaction_id;
                $response['result']['cancel_time'] = strtotime($paymeTransact->cancel_at)*1000; //intval($paymeTransact->cancel_at);
                $response['result']['state'] = $paymeTransact->state; // -2;
                return $response;
            }

        }else{
            $arrError = array('ru'=>'Транзакция не найдена','en'=>'Transaction not found','uz'=>'Transakciya topilmadi');
            Log::channel('payme')->warning('Ошибка -31003 транзакция в методе CanselTransaction не найдена');
            return $this->error(
                self::ERROR_TRANSACTION_NOT_FOUND,
                $arrError
            );
        }

    }

    private function ChangePassword($request){
        //TODO
        return $this->error(
            self::ERROR_INTERNAL_SYSTEM
        );
    }

    public static function message($message){
        return ['ru' => $message['ru'], 'uz' => $message['uz'], 'en' => $message['en']];
    }

    public function error($code, $message = null, $data = null){
        $response = array();
        $response['jsonrpc'] = '2.0';
        $response['error']['code']   = $code;
        $response['error']['message']   = self::message($message);
        $response['id']      = ''; //$request['params']['id'];
        return $response;
    }

}

