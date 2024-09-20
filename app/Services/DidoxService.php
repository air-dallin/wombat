<?php

namespace App\Services;

use App\Helpers\ActDocument;
use App\Helpers\ContractDocument;
use App\Helpers\CryptHelper;
use App\Helpers\DocDocument;
use App\Helpers\Elog;
use App\Helpers\FileHelper;
use App\Helpers\GuarantDocument;
use App\Helpers\ProductDocument;
use App\Helpers\WayBillDocument;
use App\Models\Act;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Doc;
use App\Models\Guarant;
use App\Models\ModuleRequest;
use App\Models\Product;

use App\Models\WayBill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class DidoxService
{

    public static $test = false;
    public static $is_prod = true;

    public static $url_test = 'https://stage.goodsign.biz/';
    public static $url_prod = 'https://api-partners.didox.uz/';


    const STATUS_CREATED                     =	0; //	Черновик
    const STATUS_WAIT_PARTNER_SIGNATURE         =	1; //	Ожидает подписи партнера
    const STATUS_WAIT_YOUR_SIGNATURE            =	2; //	Ожидает вашей подписи
    const STATUS_SIGNED                      =	3; //	Подписан
    const STATUS_REJECTED                    =	4; //	Отказ от подписи
    const STATUS_DELETED                     =	5; //	Удален
    const STATUS_WAIT_FOR_AGENT_SIGNATURE  =	6; //	Ожидает подписи агента
    const STATUS_SIGNED_BY_AGENT             =	8; //	Подписан доверенным лицом
    const STATUS_NOT_VALID                   =	40; //	Не действительный
    const STATUS_PARTNER_WAIT_FOR_AGENT_SIGN =	60; //	Ожидает подписи агента


    const DOC_TYPE_DOCUMENT = '000';
    const DOC_TYPE_FACTURA_ACT = '001';
    const DOC_TYPE_FACTURA = '002';
    const DOC_TYPE_WAYBILL = '041';
    const DOC_TYPE_ACT = '005';
    const DOC_TYPE_GUARANTS = '006';
    const DOC_TYPE_CONTRACT = '007';
    const DOC_TYPE_FACTURA_FARM = '008';

    const OWNER_TYPE_INCOMING = 0; // входящие
    const OWNER_TYPE_OUTGOING = 1; // исходящие
    const OWNER_TYPE_NOT_USE = 9;

    const DOCUMENTS_LIMIT = 10; // колво документов для получения

    public static function getTokenByCompany(&$company){
        if(!$token = self::checkTokenExpire($company)){
            ELog::save('Not Expire token');
            $token = $company->token;
        }
        return !empty($token)?$token:false;
    }
    public static function getTokenByInn($userInn){
        $company = Company::where(['inn'=>$userInn])->first();
        if(!$token = self::checkTokenExpire($company)){
            ELog::save('Not Expire token');
            $token = $company->token;
        }
        return !empty($token)?$token:false;
    }
    public static function getTokenByInnPassword($userInn,$userPassword){
        $token = self::getToken($userInn,$userPassword);
        return !empty($token)?$token:false;
    }

    public static function getUrl(){
        $is_local = strpos($_SERVER['SERVER_NAME'],'om.loc')>0;

        return self::$is_prod && !$is_local ? self::$url_prod : self::$url_test;
    }

    /**
     * @var $userInn
     * @var $userPassword
     * @return $token
     *
    */
    public static function getToken($userInn,$userPassword){

        $userPassword = CryptHelper::decrypt($userPassword);
        ELog::save('DidoxService::getToken inn: ' . $userInn);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::getUrl() . 'v1/auth/'.$userInn.'/password/ru',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "password":"'.$userPassword.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        ELog::save('response');
        ELog::save($response);

        curl_close($curl);

        $result = json_decode($response,true);


        if(!empty($result['token'])) {
            self::refreshToken($userInn, $result['token']);
            return $result['token'];
        }
        Session::put('didox_error',$response);

        return false;

    }
    /**
     * @var $userInn
     * @var $userPassword
     * @return $token
     *
    */
    public static function getTokenSignature($userInn,$signature){

        ELog::save('DidoxService::getToken inn: ' . $userInn);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::getUrl() . 'v1/auth/'.$userInn.'/token/ru',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "signature":"'.$signature.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        ELog::save('response');
        ELog::save($response);

        curl_close($curl);

        $result = json_decode($response,true);

        if(!empty($result['token'])) {
            self::refreshToken($userInn, $result['token']);

            return $result['token'];
        }
        //Session::put('didox_error',$response );
        return false;

    }

    public static function refreshToken($userInn,$token){
        ELog::save('refresh token for inn: ' .$userInn );
        if(!empty($token)) {
            if($company = Company::where(['inn'=>$userInn])->first()) {
                $company->update(['token' => $token,'token_expire'=>time()+21600]);
                ELog::save('update company token ' . $company->id);
            }else{
                ELog::save('refresh token: company not found');
                //Session::put('didox_error',__('main.company_not_found'));
            }
        }else{
            ELog::save('refresh token: token not set');
            //Session::put('didox_error',__('main.token_not_set'));
        }
        return true;

    }

    public static function getDocuments($filter=null,$token=null)
    {

        if (empty($filter)) {
            return ['status' => false, 'error' => __('main.filter_not_set')];
        }

        $path = !empty($filter['path']) ? $filter['path'] : 'info';

        unset($filter['path']);

        $limit = DidoxService::DOCUMENTS_LIMIT;

        ELog::save('****************************************************************************',$path);
        ELog::save('DidoxService::getDocuments',$path);

        $company_id = isset($filter['company_id']) ? $filter['company_id'] : Company::getCurrentCompanyId();
        $company = Company::where(['id'=>$company_id])->first(); //isset($filter['company_id']) ? Company::getCurrentCompany($filter['company_id']) : Company::getCurrentCompany();

        if (!$company) {
            Elog::save(__('main.company_not_selected'),$path);
            return ['status' => false, 'error' => __('main.company_not_selected')];
        }
        if ($moduleRequest = ModuleRequest::where([/*'user_id' => isset($filter['user_id']) ? $filter['user_id'] : Auth::id(),*/ 'company_id' => $filter['company_id'], 'owner' => $filter['owner'],'doctype'=>$filter['doctype']])->first()) {
            $step = 1;
            $date_from = $moduleRequest->request_at;
        } else {
            $step = 2;
            $date_from = $company->date_from; // '2000-01-01 00:00:01';
        }

        Elog::save('date from: ' . $date_from);
        Elog::save('step: ' . $step);

        unset($filter['company_id']);
        $filter['dateFrom'] = $date_from;
        $filter['limit'] = $limit;

        if (empty($token)) {
            Elog::save('getTokenByCompany:' . $company->id);

            $token = self::getTokenByCompany($company);
        }

        if(empty($token) || strlen($token)!=36) {
            return ['status'=>false,'total'=>0,'error'=> self::getErrors()];
        }

        $filter_query = http_build_query($filter);
        ELog::save('DidoxService::getDocuments filter:',$path);
        ELog::save($filter_query,$path);

        $params = ['token'=>$token,'partner_key'=>$company->partner_key];

        $response = self::execute(self::getUrl() . 'v1/documents?' . $filter_query,$params);
        if(empty($response)){
            $error = self::getErrors() ?? __('main.no_data');
            ELog::save('Empty response',$path);
            ELog::save($response,$path);
            return ['status'=>false,'total'=>0,'error'=>'&nbsp;DIDOX SERVICE: ' . $error ];
        }
        if(!empty($response->error)){
            ELog::save('ERROR response',$path);
            ELog::save($response,$path);
            return ['status'=>false,'total'=>0,'error'=> $response->error->message];
        }

        $total = !empty($response->total)?$response->total : 0;

        $pages = ceil($total / $limit);
        Elog::save('response total: ' . $total . ' limit: ' . $limit . ' pages: ' . $pages ,$path);
        ELog::save('page 1 response',$path);
        ELog::save($response,$path);
        self::createFromDidoxDocument($response,$company);

        $params = ['token'=>$token,'partner_key'=>$company->partner_key];
        if($total>$limit){
            for($page=2;$page<=$pages;$page++){
                $response = self::execute($response->next_page_url .'&page=' . $page,$params);
                Elog::save('page '.$page.' response',$path);
                Elog::save('page_url '.$response->next_page_url.' response',$path);
                ELog::save($response,$path);
                self::createFromDidoxDocument($response,$company);
            }
        }

        if($step==1) {
            $moduleRequest->update(['request_at' => date('Y-m-d H:i:s')]);
        }elseif($step==2){
            ModuleRequest::create([
                'company_id' => $company_id,
                'owner' => $filter['owner'],
                'doctype' => $filter['doctype'],
                'request_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return ['status'=>true,'total'=>$total ];
    }

    public static function createFromDidoxDocument($documents,&$company)
    {
        if(empty($documents) || empty($documents->data)) {
            Elog::save('ERROR: no documents set');
            return false;
        }
        if(empty($company)) {
            Elog::save('ERROR: no company set');
            return false;
        }
        $error = [];
        $result['status']= true;
        foreach($documents->data as $document){
            sleep(1);
            Elog::save('DOCUMENT doctype: ' . $document->doctype);
            switch($document->doctype){
                case DidoxService::DOC_TYPE_CONTRACT:
                    if(!$doc = Contract::where(['didox_id'=>$document->doc_id])->select('id')->first()) {
                        $result = Contract::createDocumentFromDidox($document, $company);
                    }else{
                        Elog::save('EXIST contract: ' . $document->doc_id);
                        $result['status'] = true;
                    }
                    break;
                case DidoxService::DOC_TYPE_FACTURA:
                    if(!$doc = Product::where(['didox_id'=>$document->doc_id])->select('id')->first()) {
                        $result = Product::createDocumentFromDidox($document, $company);
                    }else{
                        Elog::save('EXIST factura: ' . $document->doc_id);
                    }
                    break;
                case DidoxService::DOC_TYPE_GUARANTS:
                    if(!$doc = Guarant::where(['didox_id'=>$document->doc_id])->select('id')->first()) {
                        $result = Guarant::createDocumentFromDidox($document, $company);
                    }else{
                        Elog::save('EXIST guarant: ' . $document->doc_id);
                    }
                    break;
                case DidoxService::DOC_TYPE_ACT:
                    if(!$doc = Act::where(['didox_id'=>$document->doc_id])->select('id')->first()) {
                        $result = Act::createDocumentFromDidox($document, $company);
                    }else{
                        Elog::save('EXIST act: ' . $document->doc_id);
                    }
                    break;
                case DidoxService::DOC_TYPE_DOCUMENT:
                    if(!$doc = Doc::where(['didox_id'=>$document->doc_id])->select('id')->first()) {
                        $result = Doc::createDocumentFromDidox($document, $company);
                    }else{
                        Elog::save('EXIST document: ' . $document->doc_id);
                    }
                    break;
                case DidoxService::DOC_TYPE_WAYBILL:
                    if(!$doc = WayBill::where(['didox_id'=>$document->doc_id])->select('id')->first()) {
                        $result = WayBill::createDocumentFromDidox($document, $company);
                    }else{
                        Elog::save('EXIST document: ' . $document->doc_id);
                    }
                    break;
                default:
            }

            if($result['status']==false) $error[] = $result['error'];
        }
        if($error) Session::flash('error',implode('<br>',$error));

    }

    public static function prepareDocument($params,&$queue){

        switch($queue->doctype) {
            case DidoxService::DOC_TYPE_CONTRACT:
                $contract = new Contract($params);
                $partner = FacturaService::getCompanyInfo($contract->partner_inn);
                $document = !empty($partner) ? ContractDocument::getTemplate($contract,$params['product_items'],$queue->company, $partner) : false;
                break;
            case DidoxService::DOC_TYPE_FACTURA:
                $product = new Product($params);
                $partner = FacturaService::getCompanyInfo($product->partner_inn); // ->partner_inn
                $document = !empty($partner) ? ProductDocument::getTemplate($product,$params['product_items'],$queue->company,$partner) : false;
                break;
            case DidoxService::DOC_TYPE_GUARANTS:
                $guarant = new Guarant($params);
                $document = GuarantDocument::getTemplate($guarant,$params['product_items'],$queue->company);
                break;
            case DidoxService::DOC_TYPE_ACT:
                $act = new Act($params);
                $document = ActDocument::getTemplate($act,$params['product_items'],$queue->company);
                break;
            case DidoxService::DOC_TYPE_DOCUMENT:
                $doc = new Doc($params);
                $document = DocDocument::getTemplate($doc,null,$queue->company);
                break;
            case DidoxService::DOC_TYPE_WAYBILL:
                $wayBill = new WayBill($params);
                $document = WayBillDocument::getTemplate($wayBill,null,$queue->company);
                break;
        }

        return $document;
    }

    /**
     * @var Company $company
     * @return $token
    */
    public static function checkTokenExpire(&$company){
        Elog::save('com_id: ' .$company->id . ' token_expire: ' . $company->token_expire);
        if((int)$company->token_expire<time()) {
            Elog::save('update token com_id: ' .$company->id);
            $result = self::getToken($company->inn, $company->password);
        }else{
            $result = false;
        }
        return $result;
    }

    public static function deleteDocument(&$document,&$company,$token=null){

        $error = [];

        if(empty($company)) $error[] = __('main.company_not_set');

        if(!empty($company) && empty($token)){
            $token = self::getTokenByCompany($company);
        }
        if(empty($token) || strlen($token)!=36) {
            $error[] = self::getErrors();
        }

        if($error) return ['status'=>false,'error'=>implode(', ',$error)];

        if($document->status==DidoxService::STATUS_CREATED) {
            $url = self::getUrl() . 'v1/documents/'.$document->didox_id.'/delete/draft';
            $data = null;
        }else{
            $url = self::getUrl() . 'v1/documents/'.$document->didox_id.'/delete';
            $response = json_decode($document->response_sign);
            $data = json_encode(['signature'=>$response->data->toSign],JSON_UNESCAPED_UNICODE);
        }
        $params = ['token'=>$token,'partner_key'=>$company->partner_key];
        $response = self::execute($url,$params,'POST',$data);
        Elog::save('response:');
        Elog::save($response);
        return $response;
    }
    public static function createDocument($filter,&$document,&$company,$token=null){
        $path = !empty($filter['path'])?$filter['path'] : 'info';
        Elog::save('CreateDocument',$path);
        Elog::save('filter:',$path);
        Elog::save($filter,$path);
        Elog::save('document:',$path);
        Elog::save($document,$path);

        $error = [];
        if(empty($filter)) $error[] = __('main.filter_not_set');
        if(empty($filter['doctype'])) $error[] = __('main.doctype_not_set');
        if(empty($filter['owner'])) $error[] = __('main.owner_not_set');
        if(empty($company)) $error[] = __('main.company_not_set');

        if(!empty($company) && empty($token)){
            $token = self::getTokenByCompany($company);
        }
        if(empty($token) || strlen($token)!=36) {
            return ['status'=>false,'total'=>0,'error'=> self::getErrors()];
        }
        Elog::save('company: '.$company->id, $path);
        Elog::save('TOKEN: ' . $token, $path);

        if($error) {
             Elog::save('ERROR',$path);
             Elog::save($error,$path);
            return ['status' => false, 'error' => implode(', ', $error)];
        }

        $url = self::getUrl() . 'v1/documents/'.$filter['doctype'].'/create/ru?owner='.$filter['owner'];
        $params = ['token'=>$token,'partner_key'=>$company->partner_key];
        $response = self::execute($url,$params,'POST',$document);
        Elog::save('response',$path);
        Elog::save($response,$path);
        return $response;
    }

    public static function updateDocument($filter,&$document,&$company,$token=null){
        $path = !empty($filter['path'])?$filter['path'] : 'info';
        Elog::save('updateDocument',$path);
        Elog::save('filter',$path);
        Elog::save($filter,$path);
        $error = [];
        if(empty($filter)) $error[] = __('main.filter_not_set');
        if(empty($filter['doctype'])) $error[] = __('main.doctype_not_set');
        if(empty($filter['owner'])) $error[] = __('main.owner_not_set');
        if(empty($company)) $error[] = __('main.company_not_set');

        if(!empty($company) && empty($token)){
            $token = self::getTokenByCompany($company);
        }
        if(empty($token) || strlen($token)!=36)   $error[] = self::getErrors();

        if($error) {
            Elog::save('ERROR',$path);
            Elog::save($error,$path);
            return ['status' => false, 'error' => implode(', ', $error)];
        }

        $url = self::getUrl() . 'v1/documents/'.$filter['didox_id'].'/update/'.$filter['doctype'].'/ru?owner='.$filter['owner'];

        unset($filter['didox_id']);

        Elog::save('URL: ' . $url,$path);
        $params = ['token'=>$token,'partner_key'=>$company->partner_key];
        $response = self::execute($url,$params,'POST',$document);
        Elog::save('response',$path);
        Elog::save($response,$path);
        return $response;
    }

    public static function signDocument($filter,&$company,$token=null){

        $path = !empty($filter['path'])?$filter['path'] : 'info';
        Elog::save('signDocument',$path);
        Elog::save('filter',$path);
        Elog::save($filter,$path);
        $error = [];
        if(empty($filter)) $error[] = __('main.filter_not_set');
        if(empty($filter['didox_id'])) $error[] = __('main.didoxid_not_set');
        if(empty($filter['signature'])) $error[] = __('main.signature_not_set');
        if(empty($company)) $error[] = __('main.company_not_set');

        if(!empty($company) && empty($token)){
            $token = self::getTokenByCompany($company);
        }
        if(empty($token) || strlen($token)!=36) $error[] = self::getErrors();

        if($error) {
            Elog::save('ERROR',$path);
            Elog::save($error,$path);
            return ['status' => false, 'error' => implode(', ', $error)];
        }

        $url = self::getUrl() . 'v1/documents/'.$filter['didox_id'].'/sign';

        $data = json_encode(['signature' =>$filter['signature']],JSON_UNESCAPED_UNICODE);
        Elog::save('URL: ' . $url,$path);
        $params = ['token'=>$token,'partner_key'=>$company->partner_key];
        $response = self::execute($url,$params,'POST',$data);
        Elog::save('response',$path);
        Elog::save($response,$path);
        return $response;

    }
    public static function getDocument(&$document,&$company,$token=null){
        Elog::save('getDocument');
        $error = [];
        if(empty($company)) {
            $error[] = __('main.company_not_set');
        }

        if(!empty($company) && empty($token)){
            $token = self::getTokenByCompany($company);
        }
        if(empty($token) || strlen($token)!=36) $error[] = self::getErrors();

        if($error) return ['status'=>false,'error'=>implode(', ',$error)];

        $url = self::getUrl() . 'v1/documents/'.$document->didox_id;
        $params = ['token'=>$token,'partner_key'=>$company->partner_key];
        $response = self::execute($url,$params,'GET');

        Elog::save('response:');
        Elog::save($response);
        return $response;
    }

    public static function getTimestamp($filter/*,&$company,$token=null*/){

        $path = !empty($filter['path'])?$filter['path'] : 'info';
        Elog::save('getTimestamp',$path);
        Elog::save('filter',$path);
        Elog::save($filter,$path);
        $error = [];
        if(empty($filter)) $error[] = __('main.filter_not_set');
        if(empty($filter['signatureHex'])) $error[] = __('main.signature_not_set');
        if(empty($filter['pkcs7'])) $error[] = __('main.pkcs7_not_set');

        if($error) {
            Elog::save('ERROR',$path);
            Elog::save($error,$path);
            return ['status' => false, 'error' => implode(', ', $error)];
        }

        $url = self::getUrl() . 'v1/dsvs/gettimestamp';

        $data = json_encode(['signatureHex' =>$filter['signatureHex'],'pkcs7'=>$filter['pkcs7']],JSON_UNESCAPED_UNICODE);
        Elog::save('URL: ' . $url,$path);
        $response = self::execute($url,null,'POST',$data);
        Elog::save('response',$path);
        Elog::save($response,$path);
        return $response;

    }
    /**
     * отклонение документа
    */
    public static function rejectDocument($filter,&$company,$token=null){

        $path = !empty($filter['path'])?$filter['path'] : 'info';
        Elog::save('signDocument',$path);
        Elog::save('filter',$path);
        Elog::save($filter,$path);
        $error = [];
        if(empty($filter)) $error[] = __('main.filter_not_set');
        if(empty($filter['didox_id'])) $error[] = __('main.didoxid_not_set');
        if(empty($filter['signature'])) $error[] = __('main.signature_not_set');
        if(empty($company)) $error[] = __('main.company_not_set');

        if(!empty($company) && empty($token)){
            $token = self::getTokenByCompany($company);
        }
        if(empty($token) || strlen($token)!=36) $error[] = self::getErrors();

        if($error) {
            Elog::save('ERROR',$path);
            Elog::save($error,$path);
            return ['status' => false, 'error' => implode(', ', $error)];
        }
        $url = self::getUrl() . 'v1/documents/'.$filter['didox_id'].'/reject';
        $data['signature'] = json_encode($filter['signature'],JSON_UNESCAPED_UNICODE);
        Elog::save('URL: ' . $url,$path);
        $params = ['token'=>$token,'partner_key'=>$company->partner_key];
        $response = self::execute($url,$params,'POST',$data);
        Elog::save('response',$path);
        Elog::save($response,$path);
        return $response;

    }

    public static function getFile(&$company,$id,$type='attachment'){
        $path = 'info';
        Elog::save('getFile',$path);
        $error = [];
        if(empty($id)) $error[] = __('main.id_not_set');
        if(empty($company)) $error[] = __('main.company_not_set');

        if(!empty($company) && empty($token)){
            $token = self::getTokenByCompany($company);
        }
        if(empty($token) || strlen($token)!=36) $error[] = self::getErrors();

        if($error) {
            Elog::save('ERROR',$path);
            Elog::save($error,$path);
            return ['status' => false, 'error' => implode(', ', $error)];
        }
        $filename = 'document_' .$id .'.pdf';
        $url = self::getUrl() . 'v1/documents/'.$id.'/file/false';
        Elog::save('URL: ' . $url,$path);
        $filepath = FileHelper::createDir('documents/'. $id);
        $params = ['token'=>$token,'partner_key'=>$company->partner_key,'is_file'=>true,'filepath'=>$filepath.'/'.$filename];
        $header[] = 'user-key: ' . $params['token'];
        if (self::$is_prod) $header[] = 'Partner-Authorization:'.  $params['partner_key'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.didox.uz/v1/documents/'.$id.'/file/false',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => !empty($data)?$data:null,
            CURLOPT_HTTPHEADER => $header
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if(empty($response)){
            Elog::save('empty response', $path);
            return '';
        }

        Elog::save('response',$path);
        if(file_put_contents($params['filepath'],$response)){
            Elog::save('SAVE PDF OK',$path);
        }

        return 'documents/' . $id .'/' .$filename;
    }

    public static function execute($url,$params,$method='GET',$data=null){

        Elog::save('DIDOX-SERVICE execute :');
        Elog::save('url: ' . $url);

        $curl = curl_init();
        $header = ['Content-Type: application/json'];
        if(!empty($params)){
            $header[] = 'user-key: ' . $params['token'];
            if (self::$is_prod) $header[] = 'Partner-Authorization:'.  $params['partner_key'];
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => !empty($data)?$data:null,
            CURLOPT_HTTPHEADER => $header,
        ));
        $response = curl_exec($curl);
        Elog::save(curl_getinfo($curl));
        curl_close($curl);

        return json_decode($response);

    }
    /** получить наименование owner */
    public static function getOwnerLabel($owner){
        switch($owner){
            case '0': // входящие
            case '9': // черновик
                $owner = 'incoming';
                break;
            case '1': // исходящие
                $owner = 'outgoing';
                break;

        }
        return $owner;
    }

    /** получить owner id */
    public static function getOwner($request){
        $owner = $request->has('owner') ? self::owner($request->owner) : self::OWNER_TYPE_OUTGOING;
        return $owner;
    }

    /** получить owner id */
    public static function owner($owner){

        switch($owner){
            case '0':
            case 'incoming': // входящие
                $owner = DidoxService::OWNER_TYPE_INCOMING;
                break;
            case '1':
            case 'outgoing': // исходящие
                $owner = DidoxService::OWNER_TYPE_OUTGOING;
                break;
            default:
                $owner = DidoxService::OWNER_TYPE_INCOMING;
        }

        return $owner;

    }
    public static function getStatusLabel($status){
        switch($status){
            case 0:
                return '<label class="block" style="background: #0a53be; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 1:
                return '<label class="block" style="background: orange; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 2:
                return '<label class="block"  style="background: orange; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 3:
                return '<label class="block" style="background: #22c55e; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 4:
                return '<label class="block" style="background: red; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 5:
                return '<label class="block" style="background: red; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 6:
                return '<label class="block" style="background: orange; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 8:
                return '<label class="block" style="background: #22c55e; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 40:
                return '<label class="block" style="background: red; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 60:
                return '<label class="block" style="background: orange; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
            case 100:
            default:
                return '<label class="block" style="background: lightslategray; width: 12px; height: 12px; border-radius: 50%; margin-right: 10px"></label>';
        }
    }
    public static function getListStatuses(){

        $status = [];
        $status[] = '<option value="0">'.__('main.draft').'</option>';
        $status[] = '<option value="1">'.__('main.wait_partner_signature').'</option>';
        $status[] = '<option value="2">'.__('main.wait_your_signature').'</option>';
        $status[] = '<option value="3">'.__('main.signed').'</option>';
        $status[] = '<option value="4">'.__('main.refusal_signature').'</option>';
        $status[] = '<option value="5">'.__('main.deleted').'</option>';
        $status[] = '<option value="6">'.__('main.wait_agent_signature').'</option>';
        $status[] = '<option value="8">'.__('main.signed_trusted_person').'</option>';
        $status[] = '<option value="40">'.__('main.invalid').'</option>';
        $status[] = '<option value="60">'.__('main.wait_agent_signature').'</option>';

       return implode('',$status);

    }

    public static function getStatus($status){

        switch ($status) {
            case 0:
                return __('main.draft');
            case 1:
                return __('main.wait_partner_signature');
            case 2:
                return __('main.wait_your_signature');
            case 3:
                return __('main.signed');
            case 4:
                return __('main.refusal_signature');
            case 5:
                return __('main.deleted');
            case 6:
                return __('main.wait_agent_signature');
            case 8:
                return __('main.signed_trusted_person');
            case 40:
                return __('main.invalid');
            case 60:
                return __('main.wait_agent_signature');
        }
        return __('main.unknown');


    }

    public static function canDestroy($object){
        if($object->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING &&
            $object->doc_status==\App\Services\DidoxService::STATUS_CREATED
            /* && $object->doc_status!=\App\Services\DidoxService::STATUS_DELETED*/ ){
            return (time()-strtotime($object->created_at))/86400 < 10;  // менее 10 дней со дня создания
        }
        return false;
    }

    public static function getErrors(){
        if(Session::has('didox_error')){
            return Session::get('didox_error');
        }
        return null;
    }
}
