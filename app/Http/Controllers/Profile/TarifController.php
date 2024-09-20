<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\Livestock;
use App\Models\Module;
use App\Models\Payment;
use App\Models\PaymentSystem;
use App\Models\Tarif;
use App\Models\UserCrop;
use App\Models\UserLivestock;
use Illuminate\Support\Facades\Auth;

class TarifController extends Controller
{
    public function index()
    {
        if(!$tarifs = Tarif::with('modules')->where(['status'=>Module::STATUS_ACTIVE])->orderBy('price','ASC')->get()){
            $tarifs = [];
        }

        $modules = [];
        foreach ($tarifs as $tarif){
            foreach($tarif->modules as $module){
                $modules[$module->id][] = $tarif->id;
            }
        }

        $paymentSystems = PaymentSystem::where(['status'=>PaymentSystem::STATUS_ACTIVE])->get();


        return view('frontend.profile.tarifs.tarifs', compact('tarifs','modules','paymentSystems'));
    }

    public function payments(){

        $payments = Payment::with('tarif')
            ->where(['user_id' =>  Auth::id()])->order(/*['exclude' => ['users_from']]*/)->orderBy('created_at','DESC')->paginate(15);

        return view('frontend.profile.tarifs.payment', compact('payments'));
    }

/*    public function create()
    {

        return view('frontend.profile.tarifs.create'));
    }

    public function edit(Tarif $tarif)
    {
        return view('frontend.profile.tarifs.form', compact('tarif'));
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tarif $tarif
     * @return \Illuminate\Http\Response
     */
   /* public function update(Request $request, Tarif $tarif)
    {
        $params = $request->all();

        $tarif->update($params);

        return redirect()->route('frontend.profile.tarifs.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        // dd($request->all()); //,Auth::user()->info());
        $validator = Validator::make($request->all(),
            [

            ]
        );

        $params = $request->all();
        $params['user_id'] = Auth::id();

        if ($validator->fails()) {
            return redirect()->route('frontend.profile.tarifs.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
            ;

        }

        $tarif = Tarif::create($params);

        if ($request->has('image')) {
            Image::add($request->file('image'), 'tarif', $tarif->id);
        }

        return redirect()->route('frontend.profile.tarifs.index', app()->getLocale());
    }*/


}
