<?php

namespace App\Http\Controllers\Admin;

use App\Models\Status;
use App\Models\Ikpu;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class IkpuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ikpus = Ikpu::active()/*where('status','<>',Status::STATUS_DELETED)*/->order()->paginate(15);

        return view('admin.ikpu.index', compact('ikpus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ikpu.form');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /* $request->validate(
            [
                'title_en' => 'required',
                'title_uz' => 'required',
                'title_ru' => 'required',
            ]

        ); */

        $params = $request->all();

        Ikpu::create($params);

        return redirect()->route('admin.ikpu.index', app()->getLocale())->with('success', 'Operation successfull completed!');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Ikpu $ikpu
     * @return \Illuminate\Http\Response
     */
    public function show(Ikpu $ikpu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Ikpu $ikpu
     * @return \Illuminate\Http\Response
     */
    public function edit(Ikpu $ikpu)
    {
        return view('admin.ikpu.form', compact('ikpu'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Ikpu     $ikpu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ikpu $ikpu)
    {

        $params = $request->all();

        $ikpu->update($params);

        return redirect()->route('admin.ikpu.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Ikpu $ikpu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ikpu $ikpu)
    {

        $ikpu->status = Status::STATUS_DELETED;
        $ikpu->save();
        return redirect()->route('admin.ikpu.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }
}
