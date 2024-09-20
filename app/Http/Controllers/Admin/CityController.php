<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::with('region')->order()->paginate(15);
        return view('admin.city.index',compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $regions = Region::all();
        return view('admin.city.form',compact('regions'));

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

        City::create($params);

        return redirect()->route('admin.city.index',app()->getLocale())->with('success','Create success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //$user->with('photos');
        $regions = Region::all();

        return view('admin.city.form',compact('city','regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $params = $request->all();

        $city->update($params);
        return redirect()->route('admin.city.index',app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.city.index',app()->getLocale());
    }

    public function getCities(Request $request){

        if($request->isMethod('post') && $request->ajax() ) {
            $title = 'title_'.app()->getLocale();
            if ($cities = City::where('region_id', $request->region_id)->orderBy('title_ru','ASC')->pluck($title,'id')) {
                $_cities = [];
               // dd($cities);
                foreach ($cities as $id=>$city){
                    $_cities[] = '<option value="'.$id.'">'.$city.'</option>';
                }

                return ['status' => true, 'data' => implode('\r',$_cities)];
            }

        }

        return ['status' => false];


    }

}
