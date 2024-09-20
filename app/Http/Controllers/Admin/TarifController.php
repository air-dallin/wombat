<?php

namespace App\Http\Controllers\Admin;

use App\Models\Module;
use App\Models\Region;
use App\Models\Tarif;
use App\Models\TarifModule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TarifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tarifs = Tarif::with('modules')->order()->paginate(15);
        return view('admin.tarif.index',compact('tarifs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $modules = Module::all();
        return view('admin.tarif.form',compact('modules'));

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

        $module_ids = $params['module_ids'];
        unset($params['module_ids']);
        $params['slug'] = Str::slug($params['title_uz']);

        $tarif = Tarif::create($params);

        $tarif->modules()->sync($module_ids);

        return redirect()->route('admin.tarif.index',app()->getLocale())->with('success','Create success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tarif  $tarif
     * @return \Illuminate\Http\Response
     */
    public function show(tarif $tarif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tarif  $tarif
     * @return \Illuminate\Http\Response
     */
    public function edit(tarif $tarif)
    {
        $tarif->with('modules');
        $modules = Module::all();
        $tarif_modules = $tarif->modules->pluck('id')->toArray();
        return view('admin.tarif.form',compact('tarif','modules' ,'tarif_modules' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tarif  $tarif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tarif $tarif)
    {
        $params = $request->all();
        $tarif->modules()->sync($params['module_ids']);
        unset($params['module_ids']);
        $tarif->update($params);
        return redirect()->route('admin.tarif.index',app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tarif  $tarif
     * @return \Illuminate\Http\Response
     */
    public function destroy(tarif $tarif)
    {
        //$tarifModules = TarifModules::
        $tarif->delete();
        return redirect()->route('admin.tarif.index',app()->getLocale());
    }

    public function getCities(Request $request){

        if($request->isMethod('post') && $request->ajax() ) {
            $title = 'title_'.app()->getLocale();
            if ($cities = tarif::where('region_id', $request->region_id)->orderBy('title_ru','ASC')->pluck($title,'id')) {
                $_cities = [];
               // dd($cities);
                foreach ($cities as $id=>$tarif){
                    $_cities[] = '<option value="'.$id.'">'.$tarif.'</option>';
                }

                return ['status' => true, 'data' => implode('\r',$_cities)];
            }

        }

        return ['status' => false];


    }

}
