<?php

namespace App\Http\Controllers\Admin;

use App\Models\Status;
use App\Models\Nds;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class NdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ndses = Nds::where('status','<>',Status::STATUS_DELETED)->order()->paginate(15);

        return view('admin.nds.index', compact('ndses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.nds.form');

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
               // 'title_uz' => 'required',
                'title_ru' => 'required',
            ]
           /* [
                'text_en' => 'required',
                'text_uz' => 'required',
                'text_ru' => 'required',
            ]*/
        );

        $params = $request->all();

        $slug = Str::slug($params['title_uz']);

        unset($params['image']);

        if ( Nds::where(['slug' => $slug])->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }

        //if(is_null($params['parent_id'])) $params['parent_id'] = 0;

        $nds = Nds::create($params);
       /* if ($request->has('image')) {
            Image::add($request->file('image'), 'ndses', $nds->id);
        }*/

        return redirect()->route('admin.nds.index', app()->getLocale())->with('success', 'Operation successfull completed!');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Nds $nds
     * @return \Illuminate\Http\Response
     */
    public function show(Nds $nds)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Nds $nds
     * @return \Illuminate\Http\Response
     */
    public function edit(Nds $nds)
    {

        return view('admin.nds.form', compact('nds'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Nds     $nds
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nds $nds)
    {

        $params = $request->all();

        $slug = Str::slug($params['title_uz']);
        if ( Nds::where(['slug'=>$slug])->where('id','!=',$nds->id)->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }
        $nds->update($params);

        return redirect()->route('admin.nds.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Nds $nds
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nds $nds)
    {

        $nds->status = Status::STATUS_DELETED;
        $nds->save();
        return redirect()->route('admin.nds.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }
}
