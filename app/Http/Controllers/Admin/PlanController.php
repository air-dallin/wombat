<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\QueryHelper;
use App\Models\Status;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $planes = Plan::order()->filter()->paginate(15);

        if(QueryHelper::fixSearchQuery($request)){
            return response()->json(['status'=>true,'data'=>view('admin.plan.table', compact('planes'))->render()]);
        }
        return view('admin.plan.index', compact('planes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.plan.form');

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

        $plan = Plan::create($params);


        return redirect()->route('admin.plan.index', app()->getLocale())->with('success', 'Operation successfull completed!');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Plan $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Plan $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {

        return view('admin.plan.form', compact('plan'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Plan     $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {

        $params = $request->all();


       // dd($params);

        $plan->update($params);

        return redirect()->route('admin.plan.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Plan $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {

        $plan->status = Status::STATUS_DELETED;
        $plan->save();
        return redirect()->route('admin.plan.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }
}
