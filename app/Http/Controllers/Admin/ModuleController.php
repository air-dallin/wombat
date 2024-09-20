<?php

namespace App\Http\Controllers\Admin;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::order()->paginate(15);
        return view('admin.module.index',compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.module.form');

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

        Module::create($params);

        return redirect()->route('admin.module.index',app()->getLocale())->with('success','Create success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        return view('admin.city.form',compact('module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        $params = $request->all();

        $module->update($params);
        return redirect()->route('admin.module.index',app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        $module->delete();
        // delete with user relations - user_modules
        return redirect()->route('admin.module.index',app()->getLocale());
    }

    public function getModules(Request $request){

        if($request->isMethod('post') && $request->ajax() ) {
            $title = 'title_'.app()->getLocale();
            if ($cities = Module::orderBy('title_ru','ASC')->pluck($title,'id')) {
                $_items = [];
               
                foreach ($cities as $id=>$city){
                    $_items[] = '<option value="'.$id.'">'.$city.'</option>';
                }

                return ['status' => true, 'data' => implode('\r',$_items)];
            }

        }

        return ['status' => false];


    }

}
