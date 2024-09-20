<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\CryptHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\DidoxService;
use Illuminate\Http\Request;

class DidoxOptionController extends Controller
{

    public function edit(Company $company)
    {
          return view('frontend.profile.didox_options.form', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $params = $request->all();
        $params['password'] = CryptHelper::encrypt($params['password']);
        $company->update($params);

        return redirect()->route('frontend.profile.companies.services', ['company'=>$company,app()->getLocale()]);
    }

    public function checkDidox(Request $request){
        if($request->has('inn') && $request->has('pw')) {

            if($company = Company::where(['inn'=>$request->inn])->first()) {
                $result = DidoxService::getTokenByCompany($company);
                if (!empty($result)) return ['status' => true/*,'data'=>$result*/];
            }

        }
        return ['status'=>false];
    }


}
