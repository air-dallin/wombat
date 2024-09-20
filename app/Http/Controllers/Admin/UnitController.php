<?php

namespace App\Http\Controllers\Admin;

use App\Models\Status;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::order()->paginate(15);

        return view('admin.unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.unit.form');

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

        if ( Unit::where(['slug' => $slug])->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }*/

        //if(is_null($params['parent_id'])) $params['parent_id'] = 0;


        // TODO: add measures from didox

        Unit::create($params);


        return redirect()->route('admin.unit.index', app()->getLocale())->with('success', 'Operation successfull completed!');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Unit $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Unit $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {

        return view('admin.unit.form', compact('unit'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Unit     $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {

        $params = $request->all();

        /*$slug = Str::slug($params['title_uz']);
        if ( Unit::where(['slug'=>$slug])->where('id','!=',$unit->id)->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }*/
        $unit->update($params);

        return redirect()->route('admin.unit.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Unit $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {

       /* $unit->status = Status::STATUS_DELETED;
        $unit->save();*/
        return redirect()->route('admin.unit.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }
}
