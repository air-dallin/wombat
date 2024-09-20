<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\CompanyWarehouse;
use App\Models\Contract;
use App\Models\Ikpu;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Movements;
use App\Models\Nds;
use App\Models\Nomenklature;
use App\Models\Product;
use App\Models\Region;
use App\Models\Status;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {

        $products = Product::with(['company'])->whereIn('status',[Module::STATUS_INACTIVE,Module::STATUS_ACTIVE])->paginate(15);

        return view('admin.modules.product.index', compact('products'));
    }

    public function draft()
    {

        $products = Product::with(['company'])->where(['status'=>Module::STATUS_DRAFT])->paginate(15);

        return view('admin.modules.product.index', compact('products'));
    }

    public function remains()
    {

        $products = Product::with(['company'])->where(['status'=>Module::STATUS_DRAFT])->paginate(15);

        return view('admin.modules.product.index', compact('products'));
    }

    public function receipts()
    {

        $products = Product::with(['company'])->where(['status'=>Module::STATUS_DRAFT])->paginate(15);

        return view('admin.modules.product.index', compact('products'));
    }

    public function sales()
    {

        $products = Product::with(['company'])->where(['status'=>Module::STATUS_DRAFT])->paginate(15);

        return view('admin.modules.product.index', compact('products'));
    }



    public function create()
    {
        dd('no create from admin');

        $companies   = Company::getCompany();// get();

        $nomenklatures = Nomenklature::all();

        return view('admin.modules.product.form', compact('companies','nomenklatures'));
    }

    public function edit(Product $product)
    {
        $companies   = Company::where(['id'=>$product->company_id])->get();

        $product->with(['items','company','contract']);

        $nomenklatures = Nomenklature::all();
        $units = Unit::all();
        $contracts = Contract::where(['company_id'=>$product->company_id])->get(); //->orderBy('contract_number','DESC')->get();
        $ikpu = Ikpu::where(['status'=>Status::STATUS_ACTIVE])->get();
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        $warehouse = CompanyWarehouse::where(['company_id'=>$product->company_id])->get();
        $product_origin = Product::getOriginList();

        return view('admin.modules.product.form', compact('product', 'companies','nomenklatures','units','contracts','nds','warehouse','ikpu','product_origin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $params = $request->all();

        $product->update($params);

        return redirect()->route('admin.modules.product.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'type'  => 'required',
                'nomenklature_id' => 'required',
                'company_id' => 'required',
                'amount' => 'required',
                'quantity' => 'required',
            ]
        );

        $params = $request->all();
        $params['user_id'] = Auth::id();

        if ($validator->fails()) {
            return redirect()->route('admin.modules.product.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }

        if(!$product = Product::create($params)){
            dd($product->getErrrors());
        }

        return redirect()->route('admin.modules.product.index', app()->getLocale())->with('success', 'Update success');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        $product->delete();
        return redirect()->route('admin.modules.product.index', app()->getLocale());
    }

    public function getNomenklatures(Request $request){

        if(!empty($request->nomenklature_id)){

            //dd($request->all());
            $nomenklature = Nomenklature::with(['category','unit','nds'])->where(['id'=>$request->nomenklature_id])->first();

            $nomenklatures  = [
                'category' => !empty($nomenklature->category) ? $nomenklature->category->getTitle() : __('main.not_set'),
                 'unit' => !empty($nomenklature->unit)?$nomenklature->unit->getTitle(): __('main.not_set'),
                'nds' => !empty($nomenklature->nds)?$nomenklature->nds->getTitle(): __('main.not_set'),
               // 'nomenklature' => $nomenklature->getTitle(),
                'article' => $nomenklature->article
            ];

            return ['status'=>true,'data'=>$nomenklatures];
        }
        return ['status'=>false];
    }


}
