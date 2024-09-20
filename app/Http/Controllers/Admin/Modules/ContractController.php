<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Module;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{

    public function index()
    {

        $contracts = Contract::with(['company'])->where(['user_id'=>Auth::id()])
        ->whereIn('status',[Module::STATUS_INACTIVE,Module::STATUS_ACTIVE])
        ->paginate(15);

        return view('admin.modules.contract.index', compact('contracts'));
    }

    public function draft()
    {

        $contracts = Contract::with(['company'])->where(['user_id'=>Auth::id(),'status'=>Module::STATUS_DRAFT])->paginate(15);

        return view('admin.modules.contract.index', compact('contracts'));
    }



    public function create()
    {
        $regions       = Region::all();
        $cities        = City::all();
        $companies     = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();

        return view('admin.modules.contract.form', compact('regions', 'cities', 'companies'));
    }

    public function edit(Contract $contract)
    {
        $regions       = Region::all();
        $cities        = City::all();
        $companies     = Company::where(['user_id'=>Auth::id()])->get();

        return view('admin.modules.contract.form', compact('contract','regions', 'cities', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        $params = $request->all();

        $contract->update($params);

        return redirect()->route('admin.modules.contract.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'contract_number'  => 'required|string',
                'contract_date' => 'required',
                'contragent'   => 'required|string',
                'contragent_inn' => 'required',
                'company_id' => 'required',
                'amount' => 'required',
                'contract_text' => 'required|string',
            ]
        );

        $params = $request->all();
        $params['user_id'] = Auth::id();

        if ($validator->fails()) {
            return redirect()->route('admin.modules.contract.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }

        if(!$contract = Contract::create($params)){
            dd($contract->getErrrors());
        }

        return redirect()->route('admin.modules.contract.index', app()->getLocale())->with('success', 'Update success');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {

        $contract->delete();
        return redirect()->route('admin.modules.contract.index', app()->getLocale());
    }


}
