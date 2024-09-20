<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Elog;
use App\Http\Controllers\Admin\Modules\ExpenseOrderController;
use App\Models\Act;
use App\Models\Company;
use App\Models\CompanyAccounting;
use App\Models\CompanyInvoice;
use App\Models\Contract;
use App\Models\Cron;
use App\Models\Doc;
use App\Models\Guarant;
use App\Models\Kapital;
use App\Models\Nomenklature;
use App\Models\PaymentOrder;
use App\Models\Product;
use App\Models\PurposeCode;
use App\Models\Queue;
use App\Models\QueuePull;
use App\Models\WayBill;
use App\Services\DidoxService;
use App\Services\KapitalService;
use Illuminate\Support\Facades\DB;

class CronController extends Controller
{

    private $tokens = [];

    /**
     * инициализация новых компаний
    */
    public function companiesInit()
    {

        set_time_limit(0);
        Elog::save('-----CRON START-------------------------------', 'cron');

        $starttime = time();
        if (!$cron = Cron::where(['name' => 'companies-init'])->first()) {
            $cron = Cron::create(['name' => 'companies-init', 'status' => Cron::STATUS_INACTIVE]);
        }
        if ($cron->status == Cron::STATUS_INACTIVE && $companies = Company::where(['init' => 0, 'status' => 1])->where('password', '!=', null)->get()) {

            $cron->update(['status' => Cron::STATUS_ACTIVE]);
            Elog::save('companies for init count: ' . count($companies), 'cron');

            Elog::save(session()->has('current_company_id'), 'cron');
            foreach ($companies as $company) {
                session()->put('current_company_id', $company->id);
                Elog::save('company->id: ' . $company->id, 'cron');
                $filter = [
                    'status' => '0,1,2,3,4,5,6,8,40,60',
                    'company_id' => $company->id,
                    'user_id' => $company->user_id,
                    'path' => 'cron',
                ];
                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_INCOMING,
                    'doctype' => DidoxService::DOC_TYPE_CONTRACT
                ]));
                Elog::save('contracts INCOMING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                    'doctype' => DidoxService::DOC_TYPE_CONTRACT
                ]));
                Elog::save('contracts OUTGOING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_INCOMING,
                    'doctype' => DidoxService::DOC_TYPE_FACTURA
                ]));
                Elog::save('factura INCOMING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                    'doctype' => DidoxService::DOC_TYPE_FACTURA
                ]));
                Elog::save('factura OUTGOING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_INCOMING,
                    'doctype' => DidoxService::DOC_TYPE_GUARANTS
                ]));
                Elog::save('guarant INCOMING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                    'doctype' => DidoxService::DOC_TYPE_GUARANTS
                ]));
                Elog::save('guarant OUTGOING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_INCOMING,
                    'doctype' => DidoxService::DOC_TYPE_ACT
                ]));
                Elog::save('act INCOMING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                    'doctype' => DidoxService::DOC_TYPE_ACT
                ]));
                Elog::save('act OUTGOING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_INCOMING,
                    'doctype' => DidoxService::DOC_TYPE_DOCUMENT
                ]));
                Elog::save('document INCOMING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                    'doctype' => DidoxService::DOC_TYPE_DOCUMENT
                ]));
                Elog::save('document OUTGOING count: ' . $result['total'], 'cron');
                sleep(1);

                /*
                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_INCOMING,
                    'doctype' => DidoxService::DOC_TYPE_WAYBILL
                ]));
                Elog::save('document INCOMING count: ' . $result['total'], 'cron');
                sleep(1);

                $result = DidoxService::getDocuments(array_merge($filter, [
                    'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                    'doctype' => DidoxService::DOC_TYPE_WAYBILL
                ]));
                Elog::save('document OUTGOING count: ' . $result['total'], 'cron');
                sleep(1);
                */

                $company->update(['init' => 1]);
                Elog::save('company->id: ' . $company->id, 'cron');
                Elog::save('company->init: ' . $company->init, 'cron');

            }

            $dtime = time() - $starttime;

            Elog::save('elapsed time ' . $dtime . ' sec', 'cron');
            Elog::save('-----CRON END-------------------------------', 'cron');
            $result = true;
        } else {
            //echo 'no companies';
            $result = false;
        }
        if ($cron->status == Cron::STATUS_ACTIVE) $cron->update(['status' => Cron::STATUS_INACTIVE]);

        return $result;
    }
    /**
     * создание документов didox
    */
    public function documentsCreate()
    {

        if(strpos($_SERVER['SERVER_NAME'],'m.loc')) return false;

        set_time_limit(0);
        Elog::save('-----CRON SEND QUEUE START -------------------------------', 'cron_queue');

        $starttime = time();
        if (!$cron = Cron::where(['name' => 'documents-create'])->first()) {
            $cron = Cron::create(['name' => 'documents-create', 'status' => Cron::STATUS_INACTIVE]);
        }
        if ($cron->status == Cron::STATUS_INACTIVE && $queues = Queue::with('company')->where(['status' => Queue::STATUS_WAIT])->get()) {
            $cron->update(['status' => Cron::STATUS_ACTIVE]);

            Elog::save('documents for send count: ' . count($queues), 'cron_queue');

            foreach ($queues as $queue) {
                Elog::save($queue, 'cron_queue');
                if (!empty($queue->company)) {
                    Elog::save('company id: ' . $queue->company->id, 'cron_queue');
                    $params = json_decode($queue->params, true);

                    $queue->update(['status' => Queue::STATUS_PROCESS]);

                    Elog::save('params:', 'cron_queue');
                    Elog::save($params, 'cron_queue');
                    Elog::save('before prepare', 'cron_queue');
                    try {
                        if(!$document = DidoxService::prepareDocument($params, $queue)) {
                            Elog::save( 'ERROR NO PREPARE DOCUMENT', 'cron_queue');
                            $queue->update(['status' => Queue::STATUS_ERROR]);
                            continue;
                        }
                    } catch (\Exception $e) {
                        Elog::save( 'ERROR: ' . $e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage(), 'cron_queue');
                        $queue->update(['status' => Queue::STATUS_ERROR]);
                        continue;
                    }
                    Elog::save('after prepare', 'cron_queue');
                    $filter = [
                        'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                        'status' => '0,1,2,3,4,5,6,8,40,60',
                        'doctype' => $queue->doctype,
                        'company_id' => $queue->company_id,
                        'path' => 'cron_queue'
                    ];
                    Elog::save('create document: ' . $queue->doctype, 'cron_queue');

                    try {
                        if(!$response = DidoxService::createDocument($filter, $document, $queue->company)) {
                            Elog::save('Error create document, response:','cron_queue');
                            Elog::save($response,'cron_queue');
                            continue;
                        }
                        if(!empty($response->error)) {
                            Elog::save($response->error,'cron_queue');
                            continue;
                        }
                        Elog::save('create document OK','cron_queue');
                    } catch (\Exception $e) {
                        Elog::save( 'ERROR create document : ' . $e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage(), 'cron_queue');
                    }

                    $queue->update(['status' => Queue::STATUS_COMPLETE]);

                    try {
                        DidoxService::getDocuments($filter);
                        Elog::save('get documents OK','cron_queue');
                    } catch (\Exception $e) {
                        Elog::save( 'ERROR get documents: ' . $e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage(), 'cron_queue');
                    }

                } else {
                    Elog::save('company not found', 'cron_queue');
                }
                sleep(1);
            }

            $dtime = time() - $starttime;

            Elog::save('elapsed time ' . $dtime . ' sec', 'cron_queue');
            Elog::save('-----CRON SEND QUEUE END -------------------------------', 'cron_queue');
            $result = true;
        } else {
            $result = false;
        }

        if ($cron->status == Cron::STATUS_ACTIVE) $cron->update(['status' => Cron::STATUS_INACTIVE]);

        return $result;

    }

    /**
     * проверка статусов документов didox
    */
    public function documentsCheck()
    {
        set_time_limit(0);
        Elog::save('-----CRON CHECK START -------------------------------', 'cron_check');

        $starttime = time();
        if (!$cron = Cron::where(['name' => 'documents-check'])->first()) {
            $cron = Cron::create(['name' => 'documents-check', 'status' => Cron::STATUS_INACTIVE]);
        }
        if ($cron->status == Cron::STATUS_INACTIVE) {
            $cron->update(['status' => Queue::STATUS_PROCESS]);

            Elog::save('select documents for check', 'cron_check');

            if($documents = Contract::with('company')
                ->select(['id','didox_id','status','doc_status','company_id','response_sign'])
                ->whereIn('status',[DidoxService::STATUS_CREATED,DidoxService::STATUS_WAIT_PARTNER_SIGNATURE])
                ->where(function($q){
                    $q->where('checked_at',null)->orWhere('checked_at','>',DB::Raw('NOW()+INTERVAL 10 day'));
                })
                ->get()){
                Elog::save('START CONTRACT. For send count: ' . count($documents), 'cron_check');

                $this->checkDocumentStatus($documents,'Contract');
                Elog::save('END CONTRACT', 'cron_check');

            }

            if($documents = Product::with('company')
                ->select(['id','didox_id','status','doc_status','company_id','response_sign'])
                ->whereIn('status',[DidoxService::STATUS_CREATED,DidoxService::STATUS_WAIT_PARTNER_SIGNATURE])
                ->where(function($q){
                    $q->where('checked_at',null)->orWhere('checked_at','>',DB::Raw('NOW()+INTERVAL 10 day'));
                })
                ->get()){
                Elog::save('START FACTURA. For send count: ' . count($documents), 'cron_check');

                $this->checkDocumentStatus($documents,'Product');
                Elog::save('END FACTURA', 'cron_check');

            }

            if($documents = Guarant::with('company')
                ->select(['id','didox_id','status','doc_status','company_id','response_sign'])
                ->whereIn('status',[DidoxService::STATUS_CREATED,DidoxService::STATUS_WAIT_PARTNER_SIGNATURE,DidoxService::STATUS_WAIT_FOR_AGENT_SIGNATURE,DidoxService::STATUS_PARTNER_WAIT_FOR_AGENT_SIGN])
                ->where(function($q){
                    $q->where('checked_at',null)->orWhere('checked_at','>',DB::Raw('NOW()+INTERVAL 10 day'));
                })
                ->get()){
                Elog::save('START GUARANT. For send count: ' . count($documents), 'cron_check');

                $this->checkDocumentStatus($documents,'Guarant');
                Elog::save('END GUARANT', 'cron_check');

            }

            if($documents = Act::with('company')
                ->select(['id','didox_id','status','doc_status','company_id','response_sign'])
                ->whereIn('status',[DidoxService::STATUS_CREATED,DidoxService::STATUS_WAIT_PARTNER_SIGNATURE])
                ->where(function($q){
                    $q->where('checked_at',null)->orWhere('checked_at','>',DB::Raw('NOW()+INTERVAL 10 day'));
                })
                ->get()){
                Elog::save('START ACT. For send count: ' . count($documents), 'cron_check');

                $this->checkDocumentStatus($documents,'Act');
                Elog::save('END ACT', 'cron_check');

            }

            if($documents = Doc::with('company')
                ->select(['id','didox_id','status','doc_status','company_id','response_sign'])
                ->whereIn('status',[DidoxService::STATUS_CREATED,DidoxService::STATUS_WAIT_PARTNER_SIGNATURE])
                ->where(function($q){
                    $q->where('checked_at',null)->orWhere('checked_at','>',DB::Raw('NOW()+INTERVAL 10 day'));
                })
                ->get()){
                Elog::save('START DOC. For send count: ' . count($documents), 'cron_check');

                $this->checkDocumentStatus($documents,'Doc');
                Elog::save('END DOC', 'cron_check');

            }

            /*
            if($documents = WayBill::with('company','cargo.items')
                ->select(['id','didox_id','status','doc_status','company_id','response_sign'])
                ->whereIn('status',[DidoxService::STATUS_CREATED,DidoxService::STATUS_WAIT_PARTNER_SIGNATURE])
                ->where(function($q){
                    $q->where('checked_at',null)->orWhere('checked_at','>',DB::Raw('NOW()+INTERVAL 10 day'));
                })
                ->get()){
                Elog::save('START WAYBILL. For send count: ' . count($documents), 'cron_check');

                $this->checkDocumentStatus($documents,'Waybill');
                Elog::save('END WAYBILL', 'cron_check');

            } */

            $cron->update(['status' => Queue::STATUS_COMPLETE]);

            $dtime = time() - $starttime;

            Elog::save('elapsed time ' . $dtime . ' sec', 'cron_check');
            Elog::save('-----CRON CHECK END -------------------------------', 'cron_check');
            $result = true;
        } else {
            $result = false;
            $dtime = time() - $starttime;
            Elog::save('elapsed time ' . $dtime . ' sec', 'cron_check');
            Elog::save('-----CRON CHECK ALREADY RUNNING ...-------------------------------', 'cron_check');
        }
        if ($cron->status == Queue::STATUS_COMPLETE) $cron->update(['status' => Cron::STATUS_INACTIVE]);


        return $result;

    }
    /**
     * проверка статусов документов didox
    */
    private function checkDocumentStatus(&$documents,$documentType)
    {
        sleep(1);
        $cnt = 0;
        $updateCount = 0;
        foreach ($documents as $document){
            if($company = $document->company) {
                 if(!isset($this->tokens[$document->company_id])){
                    $result = DidoxService::getTokenByCompany($company);
                    if(!$result || strlen($result)!=36) {
                        Elog::save('SKIP DOCUMENT NO TOKEN: companyid: ' . $company->id . ' docid: ' . $document->id . ' didoxid: ' . $document->didox_id, 'cron_check');
                        Elog::save('token:');
                        Elog::save($result);
                        continue;
                    }else{
                        $this->tokens[$document->company_id] = $result;
                    }
                }

                if ($response = DidoxService::getDocument($document, $company,$this->tokens[$document->company_id])) {

                    if(is_array($response)) $response = json_encode($response,JSON_UNESCAPED_UNICODE);
                    if(!empty($response->data)){ // $response->status) || (!empty($response->status) && $response->status!=false ) ) {

                        if ($response->data->document->status != $document->status || $response->data->document->doc_status != $document->doc_status) {
                            Elog::save('CRON CHECK response:','cron_check');
                            Elog::save($response,'cron_check');
                            Elog::save('document changed: ' . $document->id . ' ' . $document->didox_id, 'cron_check');
                            Elog::save('document old status: ' . $document->status . '-' . $document->doc_status . ' : new status' . $response->data->document->status . '-' . $response->data->document->doc_status, 'cron_check');

                            $update = ['status' => $response->data->document->status, 'doc_status' => $response->data->document->doc_status, 'checked_at' => date('Y-m-d H:i:s',time())];
                            if (empty($document->response_sign) && !empty($response->data->toSign)) {
                                $update['response_sign'] = $response->data->toSign;
                            }
                            $document->update($update);

                            Elog::save('OK document update','cron_check');

                            // Обновить pdf файл
                            if($documentType=='Doc') {
                                Elog::save('get file','cron_check');
                                DidoxService::getFile($company,$document->didox_id);
                                Elog::save('OK get file','cron_check');
                            }

                            $updateCount++;

                            // обновление номенклатур и колво только при создании
                        /*    if ($document->doc_status == DidoxService::STATUS_SIGNED) {
                                if($documentType=='Guarant' || $documentType=='Product') {
                                    // обновить колво товаров
                                    if (!empty($document->items)) {
                                        Nomenklature::recalculateQuantity($document);
                                    }
                                }elseif($documentType=='Waybill'){
                                    if (!empty($document->cargo->items)) {
                                        Nomenklature::recalculateQuantity($document,$document->cargo->items);
                                    }
                                }
                            }*/

                        }
                        $cnt++;
                        if ($cnt > 10) {
                            sleep(1);
                            $cnt = 0;
                        }

                    }
                } else {
                    Elog::save('DOCUMENT NOT CHANGED: ' . $document->id . ' ' . $document->didox_id, 'cron_check');
                }
            }else{
                Elog::save('COMPANY NOT SET: ' . $document->id . ' ' . $document->didox_id, 'cron_check');
            }
        }
        Elog::save('CRON CHECK update count: ' . $updateCount,'cron_check');
    }


    /**
     * получение документов didox
     */
    public function getDocumentsDidox()
    {
        $path = 'queue_didox';
        set_time_limit(0);
        Elog::save('-----CRON SEND QUEUE START -------------------------------', $path);

        $starttime = time();
        if (!$cron = Cron::where(['name' => 'get-documents-didox'])->first()) {
            $cron = Cron::create(['name' => 'get-documents-didox', 'status' => Cron::STATUS_INACTIVE]);
        }
        if ($cron->status == Cron::STATUS_INACTIVE && $queues = QueuePull::with('company')->where(['status' => QueuePull::STATUS_WAIT])->limit(5)->get()) {
            $cron->update(['status' => Cron::STATUS_ACTIVE]);


            foreach ( $queues as $queue) {
                $filter = json_decode($queue->params, true);
                $filter['company_id'] = $queue->company_id;
                $filter['owner'] = $queue->owner;
                $filter['path'] = $path;

                $queue->update(['status' => QueuePull::STATUS_PROCESS]);

                try {
                    $result = DidoxService::getDocuments($filter);

                    if ($result['status']) {
                        $queue->update(['status' => QueuePull::STATUS_COMPLETE]);
                        Elog::save('get documents OK', $path);

                    } else {
                        $queue->update(['status' => QueuePull::STATUS_ERROR]);
                    }


                } catch (\Exception $e) {
                    Elog::save('ERROR exception get documents: ' . $e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage(), $path);
                }

            }

            $dtime = time() - $starttime;

            Elog::save('elapsed time ' . $dtime . ' sec', $path);
            Elog::save('-----CRON DIDOX getDocuments END -------------------------------', $path);
            $result = true;
        } else {
            $result = false;
        }

        if ($cron->status == Cron::STATUS_ACTIVE) $cron->update(['status' => Cron::STATUS_INACTIVE]);

        return $result;

    }

    /**
     * проверка статусов документов Kapital
     */
    public function documentsCheckKapital()
    {

        // todo нужно узнать через сколько дней макс может измениться статус


        set_time_limit(0);
        Elog::save('-----CRON CHECK START -------------------------------', 'cron_check');

        $updateCount = 0;
        $documentsCount = 0;

        $starttime = time();
        if (!$cron = Cron::where(['name' => 'documents-check-kapital'])->first()) {
            $cron = Cron::create(['name' => 'documents-check-kapital', 'status' => Cron::STATUS_INACTIVE]);
        }
        if ($cron->status == Cron::STATUS_INACTIVE) {
            $cron->update(['status' => Cron::STATUS_PROCESS]);

            Elog::save('select documents for check', 'cron_check');

            if($documents = PaymentOrder::with('company.kapital')
                ->select(['id','uniq','general_id','status','state','company_id','response'])
                ->where(['status'=>1])
                ->whereIn('state',[52,54,55,56,1,2,16])  // api & abc
                ->where(function($q){
                    $q->where('checked_at',null)->orWhere('checked_at','<',DB::Raw('NOW()+INTERVAL 1 day')); // 1 раз в день
                })
                ->limit(30)
                ->get()){
                $documentsCount = count($documents);
                Elog::save('START PAYMENT-ORDER. For send count: ' . $documentsCount, 'cron_check');

                $data = [];
                $paymentOrders = [];
                foreach ($documents as &$document) {
                     $data[] = $document->uniq;
                     $paymentOrders[$document->uniq] = $document;
                }
                $result = KapitalService::checkPayment($document->company->kapital,$data);
                if(is_null($result['error'])){
                    foreach ($result->result as $item){
                        if($item->err==0) {
                            if (isset($paymentOrders[$item->uniq])) {
                                if ($paymentOrders[$item->uniq]->state != $item->state) {
                                    $paymentOrders[$item->uniq]->update(['state' => $item->state,'checked_at'=>date('Y-m-d H:i:s',time())]);
                                    $updateCount++;
                                }else{
                                    // обновить только время
                                    $paymentOrders[$item->uniq]->update(['checked_at'=>date('Y-m-d H:i:s',time())]);
                                }
                            }
                        }else{
                            $errors[] = $item->err_msg;
                        }
                    }
                }
                Elog::save('END PAYMENT-ORDER', 'cron_check');

            }

            $cron->update(['status' => Cron::STATUS_ACTIVE]);

            $dtime = time() - $starttime;

            Elog::save('elapsed time ' . $dtime . ' sec', 'cron_check');
            if(count($errors)){
                Elog::save('-----CRON HAS ERRORS -------------------------------', 'cron_check');
                Elog::save($errors, 'cron_check');
            }
            $result = true;
        } else {
            $result = false;
            $dtime = time() - $starttime;
            Elog::save('elapsed time ' . $dtime . ' sec', 'cron_check');
            Elog::save('update count ' . $updateCount . ' from ' . $documentsCount, 'cron_check');
            Elog::save('-----CRON CHECK ALREADY RUNNING ...-------------------------------', 'cron_check');
        }
        if ($cron->status == Cron::STATUS_ACTIVE) $cron->update(['status' => Cron::STATUS_INACTIVE]);
        Elog::save('-----CRON CHECK END -------------------------------', 'cron_check');

        return $result;

    }

    /**
     * получение документов - платежных поручений Kapital
     */
    public function getDocumentsKapital()
    {

        set_time_limit(0);
        Elog::save('**** CRON GET DOCUMENTS PAYMENT ORDERS KAPITAL START ----', 'cron_kapital');

        $updateCount = 0;
        $documentsCount = 0;
        $errors = [];

        $starttime = time();
        if (!$cron = Cron::where(['name' => 'get-documents-kapital'])->first()) {
            $cron = Cron::create(['name' => 'get-documents-kapital', 'status' => Cron::STATUS_INACTIVE]);
        }
        if ($cron->status == Cron::STATUS_INACTIVE) {
            $cron->update(['status' => Cron::STATUS_PROCESS]);

            Elog::save('select kapitals for get documents', 'cron_kapital');

            // по одной компании за раз
            if($kapitals = Kapital::with('company')->where(['status'=>1])/*whereNotNull('sid')*/->where(function($q){
                $q->where('request_at',null)->orWhere(DB::Raw('DATE(request_at)'),'<',DB::Raw('DATE(NOW())'));
                })->get()){

                //dd($kapitals);

                 Elog::save('START for company '.$kapitals[0]->company_id . ' ' . $kapitals[0]->company->inn . ' ' . $kapitals[0]->company->name . '. count: ' . count($kapitals), 'cron_kapital');
                // Elog::save('START for company '.$kapitals->company_id . ' ' . $kapitals->company->inn . ' ' . $kapitals->company->name , 'cron_kapital');

                $purposes = [];
                foreach ($kapitals as &$kapital){
                    // дата с которой начинаем загрузку
                    if(!empty($kapital->request_at)){
                        $date = date('d.m.Y',strtotime($kapital->request_at));
                    }elseif(!empty($kapital->date_from)){
                        $date = date('d.m.Y',strtotime($kapital->date_from));
                    }else{
                        Elog::save('skip for company: ' . $kapital->company_id,'cron_kapital');
                        continue;
                    }
                   // dd($kapital->request_at . ' ' . $date . ' ' . strtotime($date) . ' > ' . time());

                    if(strtotime($date)>time()) continue;

                    // всего дней для загрузки от начала до текущего дня
                    $days = floor((time()-strtotime($date))/86400);
                    $data = [
                        "sid" => $kapital->getSid(),
                        "branch" => $kapital->company->mfo,
                        "account" => $kapital->company->bank_code
                    ];
                    Elog::save('days: ' . $days,'cron_kapital');
                    if($days>100) $days = 60; // 2 мес
                    for($d=0;$d<=$days;$d++) { // по всем дням до текущего

                        if (KapitalService::isExpire($kapital)) {
                            $response = KapitalService::login($kapital);
                            if(empty($response->error)) {
                                $data['sid'] = $response->result->sid;
                            }else{
                                Elog::save('break. response error: ','cron_kapital');
                                break 2;
                            }
                        }

                        if($d>0) $date = date('d.m.Y',strtotime($date . ' +1 day'));
                        if(strtotime($date)>time()) {
                            Elog::save('break. days: ' . $days .'day: ' .$d . ' date: ' . $date,'cron_kapital');
                            break;
                        }

                        $data['date'] = $date;
                        Elog::save('date: ' . $date,'cron_kapital');

                        $documents = KapitalService::getDocuments($data);
                        if (!empty($documents['result']['content'])) {

                            $documentsCount += count($documents['result']['content']);
                            Elog::save('documents count: ' . count($documents['result']['content']),'cron_kapital');
                            Elog::save($documents,'cron_kapital');

                            foreach ($documents['result']['content'] as $document) {
                                if (!$po = PaymentOrder::select('id')->where(['general_id' => $document['general_id']])->first()) {
                                   try {
                                       $document['company_id'] = $kapital->company_id;
                                       $document['user_id'] = $kapital->company->user_id;
                                       $document['status'] = $document['state'];

                                       if (isset($document['o_date'])) $document['o_date'] = date('Y-m-d', strtotime($document['o_date']));
                                       if (isset($document['vdate'])) $document['vdate'] = date('Y-m-d', strtotime($document['vdate']));
                                       // проверить, если нет создать счет
                                       if (!$companyInvoice = CompanyInvoice::select('id')->where(['company_id' => $kapital->company_id, 'bank_invoice' => $document['acc_ct']])->first()) {
                                           CompanyInvoice::create(['company_id' => $kapital->company_id, 'bank_invoice' => $document['acc_ct']]);
                                       }
                                       // проверить, если нет создать назначение
                                       if (empty($purposes[$document['purp_code']])) {
                                           if (!$purpose = PurposeCode::select('id')->where(['code' => $document['purp_code']])->first()) {
                                               $purpose = PurposeCode::create(['code' => $document['purp_code']]);
                                           }
                                           $purposes[$document['purp_code']] = $purpose->id;
                                       }
                                       $document['purpose_id'] = $purposes[$document['purp_code']]; //$purpose->id;
                                       $paymentOrder = PaymentOrder::create($document);
                                       $updateCount++;

                                       Elog::save('PAYMENT ORDER ADD: ' . $document['general_id'] . ' ' . $date, 'cron_kapital');

                                   }catch (\Exception $e){
                                       Elog::save($e->getFile() . ' ' . $e->getLine() . ' ' .  $e->getMessage() ,'cron_kapital' );
                                   }

                                }else{
                                    Elog::save('PAYMENT ORDER EXIST: ' . $po->id . ' ' . $date,'cron_kapital');
                                }
                            }
                        }else{
                            if(!empty($documents['error'])) {
                                $errors[] = $documents['error']['code'] . ' - ' . $documents['error']['message'];
                            }
                            Elog::save('EMPTY FOR DATA: ' . $date,'cron_kapital');
                        }
                        $kapital->update(['request_at'=>date('Y-m-d H:i:s',strtotime($date))]);
                        sleep(1);
                    } // d++

                }

            }else { // kapitals
                Elog::save('NO KAPITAL INITIAL DATA','cron_kapital');
            }

            $cron->update(['status' => Cron::STATUS_ACTIVE]);

            $dtime = time() - $starttime;

            Elog::save('elapsed time ' . $dtime . ' sec', 'cron_kapital');
            if(count($errors)){
                Elog::save('-----CRON HAS ERRORS ****', 'cron_kapital');
                Elog::save($errors, 'cron_kapital');
            }

            $result = true;
        } else {
            $result = false;
            $dtime = time() - $starttime;
            Elog::save('elapsed time ' . $dtime . " sec \n
                update count " . $updateCount . ' from ' . $documentsCount ."\n
                -----CRON GET PAYMENT ORDERS KAPITAL ALREADY RUNNING ...", 'cron_kapital');
        }

        if ($cron->status == Cron::STATUS_ACTIVE) $cron->update(['status' => Cron::STATUS_INACTIVE]);

        Elog::save('-----CRON GET PAYMENT ORDERS KAPITAL END ***', 'cron_kapital');

        return $result;

    }

}
