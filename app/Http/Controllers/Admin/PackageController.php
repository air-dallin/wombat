<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\Http\Request;


class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::order()->paginate(15);

        return view('admin.package.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.package.form');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                //'title_en' => 'required',
                //'title_uz' => 'required',
                'title_ru' => 'required',
            ]
           /* [
                'text_en' => 'required',
                'text_uz' => 'required',
                'text_ru' => 'required',
            ]*/
        );

        $params = $request->all();


      /*  unset($params['image']);

        if ( Package::where(['slug' => $slug])->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }*/

        //if(is_null($params['parent_id'])) $params['parent_id'] = 0;


        // TODO: add measures from didox

        Package::create($params);


        return redirect()->route('admin.package.index', app()->getLocale())->with('success', 'Operation successfull completed!');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Package $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Package $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {

        return view('admin.package.form', compact('package'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Package     $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {

        $params = $request->all();

        /*$slug = Str::slug($params['title_uz']);
        if ( Package::where(['slug'=>$slug])->where('id','!=',$package->id)->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }*/
        $package->update($params);

        return redirect()->route('admin.package.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Package $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {

       /* $package->status = Status::STATUS_DELETED;
        $package->save();*/
        return redirect()->route('admin.package.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }
}
