<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $districts = District::with('city')->order()->paginate(15);
        return view('admin.district.index',compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::orderBy('title_ru','ASC')->get();
        return view('admin.district.form',compact('cities'));
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

        District::create($params);

        return redirect()->route('admin.district.index',app()->getLocale())->with('success','Create success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\District $district
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        //$user->with('photos');
        $cities = City::all();

        return view('admin.district.form',compact('district','cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\District $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, District $district)
    {
        $params = $request->all();

        $district->update($params);
        return redirect()->route('admin.district.index',app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\District $district
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district)
    {
        $district->delete();
        return redirect()->route('admin.district.index',app()->getLocale());
    }

    public function getDistricts(Request $request){

        if($request->isMethod('post') && $request->ajax() ) {
            $title = 'title_'.app()->getLocale();
            if ($districts = District::where('city_id', $request->city_id)->orderBy('title_ru','ASC')->pluck($title,'id')) {
                $_districts = [];
               // dd($districts);
                foreach ($districts as $id=>$district){
                    $_districts[] = '<option value="'.$id.'">'.$district.'</option>';
                }

                return ['status' => true, 'data' => implode('\r',$_districts)];
            }

        }

        return ['status' => false];


    }

}
