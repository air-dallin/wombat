<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyIkpu;
use App\Models\Crop;
use App\Models\Ikpu;
use App\Models\Livestock;
use App\Models\Status;
use App\Models\UserCrop;
use App\Models\UserLivestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyIkpuController extends Controller
{
    public function index()
    {

        $company = Company::getCurrentCompany();
        $companies = Company::getCompany();
        if(!is_object($company)) {
            $companyIkpus = CompanyIkpu::byCompany($companies)->notDeletedOnly()->order()->paginate(15);
        }else{
            $companyIkpus = CompanyIkpu::where(['company_id'=>$company->id])->notDeletedOnly()->order()->paginate(15);
        }

        $current_company_id = !empty($company) ? $company->id : 0; // Company::getCurrentCompanyId();

        return view('frontend.profile.company_ikpu.index', compact('companyIkpus','current_company_id','companies','company'));
    }

    public function create()
    {
        $companies = Company::getCompany();
        $ikpus = Ikpu::all();
        $company_id = Company::getCurrentCompanyId();
        $ikpuList = CompanyIkpu::with('ikpu')->where(['company_id'=>$company_id])->get();
        return view('frontend.profile.company_ikpu.form',compact('companies','ikpus','ikpuList'));
    }

    public function edit(CompanyIkpu $companyIkpu)
    {
        $companies = Company::getCompany();
        $ikpus = Ikpu::all();
        $ikpuList = CompanyIkpu::with('ikpu')->where(['company_id'=>$companyIkpu->company_id])->get();
        return view('frontend.profile.company_ikpu.form', compact('companyIkpu','companies','ikpus','ikpuList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CompanyIkpu $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyIkpu $companyIkpu)
    {
        $params = $request->all();

        $companyIkpu->update($params);

        return redirect()->route('frontend.profile.company_ikpu.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'ikpu_id'  => 'required',
                'company_id' => 'required',
            ]
        );

        $params = $request->all();

        if ($validator->fails()) {
            return redirect()->route('frontend.profile.company_ikpu.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
            ;

        }

         CompanyIkpu::create($params);

        return redirect()->route('frontend.profile.company_ikpu.index', app()->getLocale());
    }

    public function destroy(CompanyIkpu $companyIkpu){

       //  $company->delete();

        // $company->status = Status::STATUS_DELETED;
        $companyIkpu->update(['status'=>Status::STATUS_DELETED]);

        return redirect()->route('frontend.profile.company_ikpu.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    public function getIkpu(Request $request){
        $res = '';
        $status = false;
        $ids = [];
        if($request->has('company_id')){
            if($ikpuList = CompanyIkpu::with('ikpu')->where(['company_id'=>$request->company_id])->get()){
                $res = '<tr><th></th><th>'.__('main.code') .'</th><th>'.__('main.title').'</th></tr>';
                $n=0;
                foreach ($ikpuList as $item){
                    $n++;
                    $res .= '<tr id="'.$item->ikpu->id.'"><td>'.$n.'</td><td>' .$item->ikpu->code . '</td><td>' . $item->ikpu->title_ru .'</td></tr>';
                    $ids[] = $item->ikpu->id;
                }
                $status = true;
            }
        }
        return ['status'=>$status,'data'=>$res,'ids'=>$ids];
    }


}
