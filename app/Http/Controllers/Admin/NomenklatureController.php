<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\CompanyIkpu;
use App\Models\Ikpu;
use App\Models\Nomenklature;
use App\Models\Unit;
use Illuminate\Http\Request;

class NomenklatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nomenklatures = Nomenklature::with('ikpu','company')->order()->paginate(15);
        return view('admin.nomenklature.index',compact('nomenklatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $ikpu = Ikpu::all();
        $units = Unit::all();

        return view('admin.nomenklature.form',compact('ikpu','units'));

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
        Nomenklature::create($params);

        return redirect()->route('admin.nomenklature.index',app()->getLocale())->with('success','Create success');
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
        $ikpu = Ikpu::all();
        $units = Unit::all();

        return view('admin.nomenklature.form', compact('nomenklature', 'ikpu','units'));
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

        return redirect()->route('admin.nomenklature.index',app()->getLocale());
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
        return redirect()->route('admin.nomenklature.index',app()->getLocale());
    }

    /*public function getGroups(Request $request){

        if($request->isMethod('post') && $request->ajax() ) {
            $title = 'title_'.app()->getLocale();
            if ($cities = Nomenklature::where('region_id', $request->region_id)->orderBy('title_ru','ASC')->pluck($title,'id')) {
                $_cities = [];
               // dd($cities);
                foreach ($cities as $id=>$nomenklature){
                    $_cities[] = '<option value="'.$id.'">'.$nomenklature.'</option>';
                }

                return ['status' => true, 'data' => implode('\r',$_cities)];
            }

        }

        return ['status' => false];


    }*/

}
