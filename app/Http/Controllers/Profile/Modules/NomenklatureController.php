<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\ObjectHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyIkpu;
use App\Models\Ikpu;
use App\Models\Nomenklature;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NomenklatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nomenklatures = Nomenklature::with(['company','ikpu','unit'])->myCompany()->active()->order()->paginate(15);
        return view('frontend.profile.modules.nomenklature.index',compact('nomenklatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies     = Company::getCompany();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $units     = Unit::all();
        return view('frontend.profile.modules.nomenklature.form',compact('ikpu','companies','units'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $params['user_id'] = Auth::id();

        Nomenklature::create($params);

        return redirect()->route('frontend.profile.modules.nomenklature.index',app()->getLocale())->with('success','Create success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Nomenklature  $nomenklature
     * @return \Illuminate\Http\Response
     */
    public function show(Nomenklature $nomenklature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Nomenklature  $nomenklature
     * @return \Illuminate\Http\Response
     */
    public function edit(Nomenklature $nomenklature)
    {

        $companies     = Company::getCompany();
        $units     = Unit::all();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;

        return view('frontend.profile.modules.nomenklature.form',compact('nomenklature','ikpu','companies','units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nomenklature  $nomenklature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nomenklature $nomenklature)
    {
        $params = $request->all();
        $nomenklature->update($params);

        return redirect()->route('frontend.profile.modules.nomenklature.index',app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nomenklature  $nomenklature
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nomenklature $nomenklature)
    {
        /* $nomenklature->status = Status::STATUS_DELETED;
        $nomenklature->save(); */
        $nomenklature->delete();
        return redirect()->route('frontend.profile.modules.nomenklature.index',app()->getLocale());
    }


}
