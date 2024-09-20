<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Elog;
use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Guarant;
use App\Models\Nomenklature;
use App\Models\Product;
use App\Services\DidoxService;
use Illuminate\Http\Request;

class DidoxController extends Controller
{

    /**
     * Получить метку времени
    */
    public function getTimestamp(Request $request)
    {
        $error = [];

        if($request->has('signatureHex')) {
            $company = Company::where(['id'=>$request->company_id])->first();
            $filter = ['signatureHex'=>$request->signatureHex,'pkcs7'=>base64_encode($company->inn)];
            $response = DidoxService::getTimestamp($filter/*,$company*/);
            Elog::save('getDocument:');
            Elog::save($response);
            if (!empty($response) && !empty($response->success) && $response->success) {
                return response()->json(['status' => true, 'timestamp' => $response->timeStampTokenB64]);
            }else{
                $error[] = !empty($response->error) ? $response->error : 'error to get timestamp';
            }
        }else{
            $error[] = 'signatureHex not set';
        }
        return ['status'=>false,'error'=>implode(', ',$error)];

    }

    /**
     * Подписать
    */
    public function sign(Request $request){
        $object = $this->getObject($request);

        Elog::save('SIGN REQUEST:');
        Elog::save($request->all());

        $filter = [
            'didox_id' => $object->didox_id,
            'signature' => $request->signature
        ];

        $company = $object->company;
        $error = [];

        $response = DidoxService::signDocument($filter, $company);
        Elog::save('SIGN RESPONSE:');
        Elog::save($response);
        if (!empty($response) && !empty($response->data)) {

            if(!empty($response->data->status) && $response->data->status=='error') {
                return ['status' => false,'error'=>$response->data->message];
            }else{
                // TODO : recalculate

                $response = DidoxService::getDocument($object, $company);
                Elog::save($response);
                if (!empty($response->data)) {
                    // TODO : нужно установить статус подписи

                    if(empty($request->data->toSign)){
                        $request->data->toSign = $filter['signature'];
                    }

                    $object->update(['status' => $response->data->document->status, 'doc_status' => $response->data->document->doc_status, 'response_sign' => json_encode($response, JSON_UNESCAPED_UNICODE)]);
                }

                return ['status' => true];
            }
        }else{
            if(!empty($response->error)){
                $error[] = $response->error->message;
            }elseif(!empty($response->data)){
                $error[] = !empty($response->data->message)?$response->data->message:'service error';
            }

        }

        return ['status'=>false,'error'=>implode(', ',$error)];
    }

    /**
     * Отказ от подписи
    */
    public function reject(Request $request){
        $object = $this->getObject($request);
//        dd($data);

        Elog::save('REJECT REQUEST:');
        Elog::save($request->all());

        $filter = [
            'didox_id' => $object->didox_id,
            'signature' => $request->signature,
            'comment' =>$request->comment
        ];

        $company = $object->company;

        $error = [];
        $response = DidoxService::rejectDocument($filter, $company);
        Elog::save('REJECT RESPONSE:');
        Elog::save($response);
        if (!empty($response) && !empty($response->data) && $response->data->status!='error') {
            // TODO : recalculate

            $response = DidoxService::getDocument($object,$company);
            Elog::save($response);
            if(!empty($response->data)) {
                // TODO : нужно установить статус подписи
                $object->update(['status' => $response->data->document->status, 'doc_status' =>  $response->data->document->doc_status]);
            }

            return ['status' => true];
        }else{
            if(!empty($response->error)){
                $error[] = $response->error->message;
            }elseif(!empty($response->data)){
                $error[] = !empty($response->data->message)?$response->data->message:'service error';
            }else{
                $error[] = json_encode($response->data,JSON_UNESCAPED_UNICODE);
            }

        }

        return ['status'=>false,'error'=>implode(', ',$error)];
    }

    /** обновить токен didox
    */
    public function updateToken(Request $request){
        $error = [];
        if($request->has('company_id')) {
            $company = Company::where(['id'=>$request->company_id])->first();
            //$response = DidoxService::getToken($company->inn,$request->signature); // ?? signature || pw
            $token = DidoxService::getTokenByCompany($company);
            Elog::save('UpdateToken response:');
            //Elog::save($response);
            if (!empty($token) && strlen($token)==36) {
                $key = !empty($request->key)? $request->key : '';
                Elog::save('UpdateToken company_id: ' . $company->id);
                $company->update(['token'=>$token,'key'=>$key]);
                return response()->json(['status' => true]);
            }else{
                $error[] = DidoxService::getErrors();
            }
        }else{
            $error[] = 'company_id not set';
        }
        return ['status'=>false,'error'=>implode(', ',$error)];
    }

    public function getStatus(Request $request){

        /*
        $response = DidoxService::getDocument($object,$company);
        Elog::save($response);
        if(!empty($response->data)) {
            // TODO : нужно установить статус подписи
            $object->update(['status' => $response->data->document->status, 'doc_status' =>  $response->data->document->doc_status]);
        }*/

        return ['status' => true];

    }



    private function getObject(Request $request){
        $object = $request->object;

        switch($object){
            case 'Contract':
                $object = Contract::with('company')->where(['id'=>$request->document_id])->first();
                break;
            case 'Factura':
            case 'Product':
                $object = Product::with('company')->where(['id'=>$request->document_id])->first();
                break;
            case 'Guarant':
                $object = Guarant::with('company')->where(['id'=>$request->document_id])->first();
                break;
            case 'Act':
                $object = Act::with('company')->where(['id'=>$request->document_id])->first();
                break;
            case 'Doc':
                $object = Doc::with('company')->where(['id'=>$request->document_id])->first();
                break;
            default:
                $object = false;
        }

        return $object;
    }

}
