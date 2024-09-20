<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\DocumentHelper;
use App\Helpers\Pdf;
use App\Helpers\QueryHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Queue;
use App\Models\QueuePull;
use App\Services\DidoxService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class DocumentController extends Controller
{

    private $pageLimit = 15;

    public function index(Request $request)
    {

        $owner = DidoxService::owner($request->owner);

        $update = $request->has('update') ? $request->update : false;

        $company_id = Company::getCurrentCompanyId();
        $myCompaniesInn = Company::getMyCompaniesInn();


        if($update /*&& $owner == DidoxService::OWNER_TYPE_INCOMING */ && $company_id!=0) {
            set_time_limit(200);
            $company = Company::getCurrentCompany();
            $token = DidoxService::getTokenByCompany($company);
            if(empty($token) || strlen($token)!=36) {
                return redirect()->route('frontend.profile.modules.document.index', ['owner'=>$request->owner, app()->getLocale()])->with('error', self::getErrors());
            }

            if(!$queue = QueuePull::where(['company_id'=>$company_id,'owner'=>$owner])->whereNotIn('status',[QueuePull::STATUS_COMPLETE,QueuePull::STATUS_ERROR])->first()) {
                $data = [
                    //'owner'=> $owner, // DidoxService::OWNER_TYPE_INCOMING,
                    'status' => '0,1,2,3,4,5,6,8,40,60',
                    'doctype' => implode(',', [DidoxService::DOC_TYPE_CONTRACT, DidoxService::DOC_TYPE_FACTURA, DidoxService::DOC_TYPE_GUARANTS, DidoxService::DOC_TYPE_ACT,DidoxService::DOC_TYPE_DOCUMENT])
                    //'company_id' => $company_id
                ];
                QueuePull::create(['service' => 'didox', 'owner' => $owner, 'company_id' => $company_id, 'params' => json_encode($data, JSON_UNESCAPED_UNICODE), 'status' => QueuePull::STATUS_WAIT]);
                return redirect()->route('frontend.profile.modules.document.index', ['owner'=>$request->owner, app()->getLocale()])->with('success', __('main.download_prepare'));

            }else{
                return redirect()->route('frontend.profile.modules.document.index', ['owner'=>$request->owner, app()->getLocale()])->with('success', __('main.already_prepare'));

            }

        }elseif( $update && $company_id==0){
            Session::flash('error',__('main.company_not_set'));
        }


        $options = DocumentHelper::getOptions($request);

        $documents = DocumentHelper::getData($request,$options);

        $ajax_search = QueryHelper::fixSearchQuery($request);

        $documents = $this->arrayPaginator($documents, $request,$options);

        $owner = $request->owner;

        $company_ids = Company::getMyCompanyIds();

        $documentsQueues = [];

        if($ajax_search){
            return response()->json(['status'=>true,'data'=>view('frontend.profile.modules.document.table', compact('documents','company_id','owner'/*,'documentsQueues'*/,'myCompaniesInn'))->render()]);
        }elseif(!$request->has('q')){
            // при поиске не отображать список из очереди
            if ($_documentsQueues = Queue::with('company')->whereIn('company_id', $company_ids)->where('status', '!=', 1)->orderBy('created_at','DESC')->get()) {
                $documentsQueues = DocumentHelper::getQueueDocuments($_documentsQueues);
            }
        }

/*        dd($myCompaniesInn);*/

        return view('frontend.profile.modules.document.index', compact('documents','company_id','owner','documentsQueues','myCompaniesInn'));
    }

    public function draft(Request $request)
    {

        $company_id = Company::getCurrentCompanyId();

        $options = DocumentHelper::getOptions($request,true);

        $documents = DocumentHelper::getData($request,$options);

        $ajax_search = QueryHelper::fixSearchQuery($request);

        $documents = $this->arrayPaginator($documents, $request,$options);

        $owner = $request->owner;

        $company_ids = Company::getMyCompanyIds();

        $myCompaniesInn = Company::getMyCompaniesInn();
        $documentsQueues = [];


        if($ajax_search){
            return response()->json(['status'=>true,'data'=>view('frontend.profile.modules.document.table', compact('documents','company_id','owner','myCompaniesInn' /*,'documentsQueues'*/))->render()]);
        }elseif(!$request->has('q')){
            // при поиске не отображать список из очереди
            if ($_documentsQueues = Queue::with('company')->whereIn('company_id', $company_ids)->where('status', '!=', 1)->orderBy('created_at','DESC')->get()) {
                $documentsQueues = DocumentHelper::getQueueDocuments($_documentsQueues);
            }
        }
        return view('frontend.profile.modules.document.index', compact('documents','company_id','owner','documentsQueues','myCompaniesInn'));

    }

    public function arrayPaginator($array, $request,$options) {
        $page = request()->get('page', 1);
        $rowsCount = DocumentHelper::getCount($options);
        return new LengthAwarePaginator(
            $array,
            $rowsCount,
            $this->pageLimit,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }



}
