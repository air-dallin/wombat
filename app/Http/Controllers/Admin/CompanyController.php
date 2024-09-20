<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Models\City;
use App\Models\Company;
use App\Models\Image;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::with(['user'])->active()->order()->paginate(100);
        return view('admin.companies.index',compact('companies'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     /*   $regions = Region::get();
        $cities = City::get();
        $roles = User::getRoles();
        return view('admin.companies.form',compact('regions','cities','roles'));*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       /* $params = $request->all();
        unset($params['image']);
        $user = User::create($params);
        $params['user_id']=$user->id;
        UserInfo::create($params);
        return redirect()->route('admin.companies.index',app()->getLocale())->with('success','UserController create success');*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $company->with(['user']);
        $regions = Region::get();
        $cities = City::get();

        return view('admin.companies.form',compact('company','regions','cities'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $params = $request->all();

        $company->update($params);

        return redirect()->route('admin.company.index',app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //$company->delete();
        $company->update(['status'=>Status::STATUS_DELETED]);
        return redirect()->route('admin.company.index',app()->getLocale());

    }

    public function export(Request $request,$role){

        $export = new UsersExport();
        $export->role = $role;

        $title = User::getRoleLabel($role);
        return Excel::download($export,'users_'.$title.'.xlsx');
    }

}
