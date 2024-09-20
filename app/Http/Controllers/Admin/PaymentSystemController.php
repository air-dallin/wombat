<?php

namespace App\Http\Controllers\Admin;

use App\Models\Status;
use App\Models\PaymentSystem;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_systems = PaymentSystem::with(['image'=>function($q){$q->where(['type'=>'payment_system']);}])->order()->paginate(15);

        return view('admin.payment_system.index', compact('payment_systems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.payment_system.form');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                //'title_en' => 'required',
                //'title_uz' => 'required',
                'title_ru' => 'required',
                 //  'slug' => 'required|unique'
            ]
        );

        if ($validator->fails()) {
            return redirect()->route('admin.payment_system.index', app()->getLocale())
                //->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors->all());
                ;
        }

        $params = $request->all();
        unset($params['image']);

        $slug = Str::slug($params['title_uz']);

        if ( PaymentSystem::where(['slug' => $slug])->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }


        if($paymentSystem = PaymentSystem::create($params)) {

            if ($request->has('image')) {
                Image::add($request->file('image'), 'payment_system', $paymentSystem->id);
            }

            return redirect()->route('admin.payment_system.index', app()->getLocale())->with('success', 'Create success');
        }

        return redirect()->route('admin.payment_system.index', app()->getLocale())
            //->withErrors($validator)
            ->withInput()
            ->with('error', $paymentSystem->errors->all());
        ;


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PaymentSystem $paymentSystem
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentSystem $paymentSystem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PaymentSystem $paymentSystem
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentSystem $paymentSystem)
    {
        $paymentSystem->with(['image'=>function($q){$q->where(['type'=>'payment_system']);}]);
        $payment_system = $paymentSystem;
       // dump($payment_system);
        return view('admin.payment_system.form', compact('payment_system'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PaymentSystem $paymentSystem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentSystem $paymentSystem)
    {

        $errors = [];
        $validator = Validator::make($request->all(), // $request->validate(
            [
                //'title_en' => 'required',
                //'title_uz' => 'required',
                'title_ru' => 'required',

            ]
        );

        if ($validator->fails()) {
            return redirect()->route('admin.payment_system.index', app()->getLocale())
                //->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors->all());
            ;
        }

        $params = $request->all();
        unset($params['image']);

        $slug = Str::slug($params['title_uz']);

        if ( PaymentSystem::where(['slug' => $slug])->where('id','<>',$paymentSystem->id)->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }

        $paymentSystem->update($params);

        if ($request->has('image')) {
            Image::add($request->file('image'), 'payment_system', $paymentSystem->id);
        }

        return redirect()->route('admin.payment_system.index', app()->getLocale())->with('success', 'Update success');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PaymentSystem $paymentSystem
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentSystem $paymentSystem)
    {

        $paymentSystem->status = Status::STATUS_DELETED;
        $paymentSystem->save();
        // $paymentSystem->delete();
        return redirect()->route('admin.payment_system.index', app()->getLocale());
    }
}
