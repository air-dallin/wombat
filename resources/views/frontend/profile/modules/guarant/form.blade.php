@extends('layouts.profile')
@section('title')

  @include('frontend.profile.sections.document_create_menu',['currentMenuType'=>'guarant'])

@endsection
@section('content')
  <style>
    .remove_product {
      cursor: pointer;
    }
  </style>
  @include('alert-profile')
  <div class="container-fluid">
    <div class="">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form method="POST" id="form_guarant"
                  @isset($guarant)
                    action="{{localeRoute('frontend.profile.modules.guarant.update',$guarant)}}"
                  @else
                    action="{{localeRoute('frontend.profile.modules.guarant.store')}}"
                @endisset
            >
              @csrf
              <input type="hidden" name="update_products" id="update_products" value="0">
              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.plan')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('plan_id') is-invalid @enderror" name="plan_id" required>
                    <option value="0">{{__('main.choice_invoice')}}</option>
                    @isset($plans)
                      @foreach($plans as $plan)
                        <option value="{{$plan->id}}"
                            @if((isset($guarant) && $guarant->plan_id==$plan->id) || (!empty($selected_plan) && $selected_plan->plan_id==$plan->id)) selected @endif >{{$plan->code . ' - ' . $plan->title_ru }}
                        </option>
                      @endforeach
                    @endisset
                  </select>
                  @error('plan_id')
                  <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                        <input type="checkbox" name="save_plan"  @if(!empty($selected_plan)) checked @endif> {{__('main.save_plan')}}
                    </label>
                </div>


                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-4">
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.guarant_number')}}</label>
                    <input type="text" name="guarant_number"
                           class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('guarant_number') is-invalid @enderror @error('guarant_number') is-invalid @enderror"
                           placeholder=""
                           value="{{old('guarant_number',isset($guarant) ? $guarant->guarant_number :$number)}}" required>
                    @error('guarant_number')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.guarant_date')}}</label>
                    <input type="date" name="guarant_date"
                           class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('guarant_date') is-invalid @enderror"
                           placeholder=""
                           value="{{old('guarant_date',isset($guarant) ? date('Y-m-d',strtotime($guarant->guarant_date)) :'')}}" required>
                    @error('guarant_date')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.guarant_date_expire')}}</label>
                    <input type="date" name="guarant_date_expire"
                           class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('guarant_date_expire') is-invalid @enderror"
                           placeholder=""
                           value="{{old('guarant_date_expire',isset($guarant) ? date('Y-m-d',strtotime($guarant->guarant_date_expire)) :'')}}" required>
                    @error('guarant_date_expire')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                  <div class="flex flex-col gap-2">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract')}}</label>
                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_id') is-invalid @enderror" name="contract_id" id="contract_id" required>
                      <option value="">{{__('main.choice_contract')}}</option>
                      @foreach($contracts as $contract)
                        <option value="{{$contract->id}}" data-date="{{ $contract->contract_date }}" @if(isset($guarant) && $guarant->contract_id==$contract->id) selected @endif >{{ $contract->contract_number . ' - ' . $contract->contract_date }}</option>
                      @endforeach
                    </select>
                    @error('contract_id')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                  <div>
                    <h3 class="border-b border-bgray-200 pb-5 text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">{{__('main.your_info')}}</h3>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mt-3 mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company_inn')}}</label>
                        <input type="text" name="company_inn"
                               class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('company_inn') is-invalid @enderror @error('company_inn') is-invalid @enderror"
                               placeholder=""
                               value="{{old('company_inn',isset($guarant) ? $guarant->company_inn :'')}}">
                        @error('company_inn')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                      </div>
                      <div class="flex flex-col mt-3 mb-3 gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
                        <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="company_id" id="company_id" required>
                          <option value="">{{__('main.choice_company')}}</option>
                          @php
                            $current_company = App\Models\Company::getCurrentCompanyId();
                          @endphp
                          @foreach($companies as $_company)
                            <option value="{{$_company->id}}" @if( (isset($guarant) && $guarant->company_id==$_company->id) || $_company->id==$current_company) selected @endif >{{$_company->name}}</option>
                          @endforeach
                        </select>
                        @error('company_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mb-3 gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company_invoice')}}</label>
                        <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('company_invoice_id') is-invalid @enderror"
                                name="company_invoice_id" required>
                          <option value="">{{__('main.choice_invoice')}}</option>
                          @isset($guarant->company->invoices)
                            @foreach($guarant->company->invoices as $invoice)
                              <option value="{{$invoice->id}}"
                                      @if(isset($guarant) && $guarant->company_account==$invoice->bank_invoice) selected @endif >{{$invoice->bank_invoice}}</option>
                                      {{--@if(isset($guarant) && $guarant->company_invoice_id==$invoice->id) selected @endif >{{$invoice->bank_invoice}}</option>--}}
                            @endforeach
                          @endisset
                        </select>
                        @error('company_invoice_id')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                      </div>
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.mfo')}}</label>
                        <input type="text" name="company_mfo" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('company_mfo',isset($guarant) ? $guarant->company_mfo :'')}}" required>
                        @error('company_mfo')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                    </div>
                    <div class="flex flex-col mb-3 gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.address')}}</label>
                      <input type="text" name="company_address" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                             value="{{old('company_address',isset($guarant) ? $guarant->company_address :'')}}"
                             required>
                      @error('company_address')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.director')}}</label>
                        <input type="text" name="company_director" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('company_director',isset($guarant) ? $guarant->company_director :'')}}"
                               required>
                        @error('company_director')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.accountant')}}</label>
                        <input type="text" name="company_accountant" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('company_accountant',isset($guarant) ? $guarant->company_accountant :'')}}"
                               required>
                        @error('company_accountant')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div>
                    <h3 class="border-b border-bgray-200 pb-5 text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">{{__('main.partner_info')}}</h3>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mt-3 mb-3 gap-2 input-group mb-3">
                        <label for="partner_inn" class="text-base font-medium text-bgray-600 dark:text-bgray-50 control-label">{{__('main.enter_inn')}}</label>
                        <div class="flex h-[56px] w-full">
                          <input type="text" name="partner_inn" id="partner_inn" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('partner_inn') placeholder="{{__('main.enter_inn')}}" is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('partner_inn',isset($guarant) ? $guarant->partner_inn :'')}}" style="width: 70%;" required>
                          @error('partner_inn')
                          <small class="invalid-feedback"> {{ $message }} </small>
                          @enderror
                          <button type="button" class="bg-gray-300 get_company p-3 get_company" style="width: 30%;"><i class="fa fa-search send "></i></button>
                        </div>
                      </div>
                      <div class="flex flex-col mt-3 mb-3 gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.partner_company_name')}}</label>
                        <input type="text" name="partner_company_name" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_company_name',isset($guarant) ? $guarant->partner_company_name :'')}}" required>
                        @error('partner_company_name')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                      </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mb-3 gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.partner_invoice')}}</label>
                        <input type="text" name="partner_bank_code" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_bank_code',isset($guarant) ? $guarant->partner_bank_code :'')}}" required>
                        @error('partner_bank_code')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                      </div>
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.mfo')}}</label>
                        <input type="text" name="partner_mfo" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_mfo',isset($guarant) ? $guarant->partner_mfo :'')}}" required>
                        @error('partner_mfo')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                    </div>
                    <div class="flex flex-col mb-3 gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.address')}}</label>
                      <input type="text" name="partner_address" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                             value="{{old('partner_address',isset($guarant) ? $guarant->partner_address :'')}}"
                             required>
                      @error('partner_address')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.director')}}</label>
                        <input type="text" name="partner_director" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_director',isset($guarant) ? $guarant->partner_director :'')}}"
                               required>
                        @error('partner_director')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.accountant')}}</label>
                        <input type="text" name="partner_accountant" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_accountant',isset($guarant) ? $guarant->partner_accountant :'')}}"
                               required>
                        @error('partner_accountant')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">
                <h3 class="border-b border-bgray-200 pb-5 text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">{{__('main.guarant_partner')}}</h3>
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-3">
                  <div class="flex flex-col mt-3 mb-3 gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.pinfl')}}</label>
                    <input type="text" name="guarant_pinfl" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                           value="{{old('guarant_pinfl',isset($guarant) ? $guarant->guarant_pinfl :'')}}" required>
                    @error('guarant_pinfl')
                    <small class="invalid-feedback"> {{ $message }} </small>
                    @enderror
                  </div>
                  <div class="flex flex-col mt-3 mb-3 gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.fio')}}</label>
                    <input type="text" name="guarant_fio" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                           value="{{old('guarant_fio',isset($guarant) ? $guarant->guarant_fio :'')}}">
                    @error('guarant_fio')
                    <small class="invalid-feedback"> {{ $message }} </small>
                    @enderror
                  </div>
                  <div class="flex flex-col mt-3 mb-3 gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.position')}}</label>
                    <input type="text" name="guarant_position" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                           value="{{old('guarant_position',isset($guarant) ? $guarant->guarant_position :'')}}" required>
                    @error('guarant_position')
                    <small class="invalid-feedback"> {{ $message }} </small>
                    @enderror
                  </div>
                </div>
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-4">
                  <div class="flex flex-col mt-3 mb-3 gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.mfo')}}</label>
                    <input type="text" name="guarant_mfo" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                           value="{{old('guarant_mfo',isset($guarant) ? $guarant->guarant_mfo :'')}}" required>
                    @error('guarant_mfo')
                    <small class="invalid-feedback"> {{ $message }} </small>
                    @enderror
                  </div>
                  <div class="flex flex-col mt-3 mb-3 gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.passport')}}</label>
                    <input type="text" name="guarant_passport" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                           value="{{old('guarant_passport',isset($guarant) ? $guarant->guarant_passport :'')}}" required>
                    @error('guarant_passport')
                    <small class="invalid-feedback"> {{ $message }} </small>
                    @enderror
                  </div>
                  <div class="flex flex-col mt-3 mb-3 gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.issue')}}</label>
                    <input type="text" name="guarant_issue" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                           value="{{old('guarant_issue',isset($guarant) ? $guarant->guarant_issue :'')}}" required>
                    @error('guarant_issue')
                    <small class="invalid-feedback"> {{ $message }} </small>
                    @enderror
                  </div>
                  <div class="flex flex-col mt-3 mb-3 gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.issue_date')}}</label>
                    <input type="date" name="guarant_issue_date" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                           value="{{old('guarant_issue_date',isset($guarant) ? date('Y-m-d',strtotime($guarant->guarant_issue_date)) :'')}}" required>
                    @error('guarant_issue_date')
                    <small class="invalid-feedback"> {{ $message }} </small>
                    @enderror
                  </div>
                  {{--                  <div class="flex flex-col mt-3 mb-3 gap-2">--}}
                  {{--                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>--}}
                  {{--                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="status">--}}
                  {{--                      <option value="0"--}}
                  {{--                              @if(isset($guarant) && $guarant->status==0) selected @endif >{{__('main.inactive')}}</option>--}}
                  {{--                      <option value="2"--}}
                  {{--                              @if(isset($guarant) && $guarant->status==2) selected @endif >{{__('main.draft')}}</option>--}}
                  {{--                      <option value="1"--}}
                  {{--                              @if(isset($guarant) && $guarant->status==1) selected @endif >{{__('main.active')}}</option>--}}
                  {{--                    </select>--}}
                  {{--                  </div>--}}
                </div>
              </div>
              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">
                <div class="flex justify-between border-b border-bgray-200 pb-5 text-bgray-900 dark:border-darkblack-400">
                  <h3 class="text-2xl font-bold dark:text-white">{{__('main.products')}}</h3>
                  <!-- Button trigger modal -->
                  <div class="modal-open cursor-pointer" id="add_product_items"><i class="fa fa-plus"></i> {{__('main.add')}}</div>
                </div>
                <div class="table-responsive">
                  <table class="w-full">
                    <tbody id="product_table">
                    <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.ikpu')}}</td>
                      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.title')}}</td>
                      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.amount')}}</td>
                      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.quantity')}}</td>
                      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.unit')}}</td>
                      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "></td>
                    </tr>
                    @if( !empty($guarant) && isset($guarant->items))
                      @foreach($guarant->items as $item)
                        <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                          <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{$item->ikpu->code .' - ' . \Illuminate\Support\Str::limit($item->ikpu->title_ru,32)}}</td>
                          <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{$item->title}}</td>
                          <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{$item->amount}}</td>
                          <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{$item->quantity}}</td>
                          <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{isset($item->unit)?$item->unit->getTitle():__('main.not_set')}}</td>
                          <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">
                            <span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>
                            <input type="hidden" name="product_items[]" value="{{$item->ikpu_id}}|{{$item->title}}|{{$item->unit_id}}|{{$item->quantity}}|{{$item->amount}}">
                          </td>
                        </tr>
                      @endforeach
                    @endif
                    </tbody>
                  </table>
                </div>
              </div>

              @if(!isset($guarant) || $guarant->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING)
                <div class="flex justify-end">
                  <button type="submit" class="rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
                </div>
              @endif

            </form>

          </div>

        </div>

      </div>
    </div>
  </div>
@endsection

<!-- Modal -->
<div class="modal fixed inset-0 z-50 h-full overflow-y-auto flex items-center justify-center hidden" id="multi-step-modal">
  <div class="modal-overlay absolute inset-0 bg-gray-500 opacity-75 dark:bg-bgray-900 dark:opacity-50"></div>
  <div class="modal-content md:w-full max-w-3xl px-4">
    <div class="step-content step-1">
      <!-- My Content -->
      <div class="max-w-[750px] rounded-lg bg-white dark:bg-darkblack-600 p-6 transition-all relative">
        <header>
          <div>
            <h3 class="font-bold text-bgray-900 dark:text-white text-2xl mb-1">
              {{__('main.add')}}
            </h3>
          </div>
          <div class="absolute top-0 right-0 pt-5 pr-5">
            <button type="button" id="step-1-cancel" class="rounded-md bg-white dark:bg-darkblack-500 focus:outline-none">
              <span class="sr-only">Close</span>
              <!-- Cross Icon -->
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 6L18 18M6 18L18 6L6 18Z" stroke="#747681" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </button>
          </div>
        </header>
        <div class="pt-4">
          <div class="modal-content">
            <div class="modal-body">
              <div class="product-inputs">
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2 mb-3">
                  <div style="margin-bottom: 15px;" class="flex flex-col gap-2">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.ikpu')}}</label>
                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" id="product_ikpu">
                      <option value="">{{__('main.choice_ikpu')}}</option>
                      @isset($ikpu)
                        @foreach($ikpu as $item)
                          <option value="{{$item->id}}">{{$item->code .' - ' . \Illuminate\Support\Str::limit($item->title_ru,32) }}</option>
                        @endforeach
                      @endisset
                    </select>
                  </div>
                  <div style="margin-bottom: 15px;" class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title')}}</label>
                    <input type="text" id="product_title" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="">
                  </div>
                </div>
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2 mb-3">
                  <div style="margin-bottom: 15px;" class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.amount')}}</label>
                    <input type="text" id="product_amount" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="">
                  </div>
                  <div style="margin-bottom: 15px;" class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.quantity')}}</label>
                    <input type="text" id="product_quantity" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="">
                  </div>
                </div>
                <div style="margin-bottom: 15px;" class="flex flex-col gap-2 mb-5">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.units')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" id="product_unit">
                    <option value="">{{__('main.choice_unit')}}</option>
                    @isset($units)
                      @foreach($units as $unit)
                        <option value="{{$unit->id}}">{{$unit->getTitle()}}</option>
                      @endforeach
                    @endisset
                  </select>

                </div>

              </div>
            </div>
            <div class="py-2 flex justify-end items-center space-x-4">
              {{--              <button class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" data-dismiss="modal"  style="border: 1px solid #718096; color: #718096;">{{__('main.close')}}</button>--}}
              <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition btn-create" id="add_product" _data-dismiss="_modal" style="background: orange">{{__('main.add')}}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@section('js')
  <script>
      $(document).ready(function () {
          var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';

          $('#company_id').change(function () {
              let company_id = $(this).val();
              if (company_id == '') return false;
              $('select[name=company_invoice_id]').html('');
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/get-info',
                  data: {'_token': _csrf_token, 'company_id': company_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('input[name=company_inn]').val($response.data['inn']);
                          $('input[name=company_address]').val($response.data['address']);
                          $('input[name=partner_bank_name]').val($response.data['bank_name']);
                          $('input[name=company_mfo]').val($response.data['mfo']);
                          $('input[name=company_oked]').val($response.data['oked']);
                          $('input[name=company_director]').val($response.data['director']);
                          $('input[name=company_accountant]').val($response.data['accountant']);
                          $('select[name=company_invoice_id]').html($response.data['bank_code']);
                      } else {
                          alert($response.error);
                      }
                  },
                  error: function (e) {
                      alert(e)
                  }
              });

              getIkpu();

          });

          $('.btn-create').click(function (e) {

              if ($('table #product_table tr').length == 0) {
                  e.preventDefault();
                  alert('{{__('main.products_not_set')}}')
                  return false;
              }
              if (!$(e.target).parents('#form_guarant').isValid()) e.preventDefault();
              $('#form_guarant').submit();
          });

          var clicked = false;
          $('.get_company').click(function () {
              let inn = $('#partner_inn').val();
              if (inn == '') return false;
              if (clicked) return false;
              clicked = true;
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/get-company-by-inn',
                  data: {'_token': _csrf_token, 'inn': inn, 'user_id': user_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('input[name=partner_company_name]').val($response.data['name']);
                          $('input[name=partner_address]').val($response.data['address']);
                          $('input[name=partner_bank_name]').val($response.data['bank_name']);
                          $('input[name=partner_bank_code]').val($response.data['bank_code']);
                          $('input[name=partner_mfo]').val($response.data['mfo']);
                          $('input[name=partner_oked]').val($response.data['oked']);
                          $('input[name=partner_director]').val($response.data['director']);
                          $('input[name=partner_accountant]').val($response.data['accountant']);
                      } else {
                          alert($response.error);
                      }
                      clicked = false;
                  },
                  error: function (e) {
                      alert(e)
                      clicked = false;
                  }
              });
          });
        @if(!isset($guarant))
        $('#company_id').change();
        @endif

        $('#add_product_items').click(function () {
            $('#product_ikpu').val('');
            $('#product_title').val('');
            $('#product_amount').val('');
            $('#product_quantity').val('');
            $('#product_unit').val('');
        });
          $('#add_product').click(function (e) {

              ikpu = $('#product_ikpu').val();
              ikpuText = $('#product_ikpu option:selected').text();
              title = $('#product_title').val();
              amount = $('#product_amount').val();
              quantity = $('#product_quantity').val();
              unit = $('#product_unit').val();
              unitText = $('#product_unit option:selected').text();

              if (!$.isNumeric(ikpu)) {
                  alert('{{__('main.choice_ikpu')}}')
                  $('#product_ikpu').focus();
                  return false;
              }
              if (title.length < 1) {
                  alert('{{__('main.choice_title')}}')
                  $('#product_title').focus();
                  return false;
              }
              if (!$.isNumeric(amount) || amount < 1) {
                  alert('{{__('main.choice_amount')}}')
                  $('#product_amount').focus();
                  return false;
              }
              if (!$.isNumeric(quantity) || quantity < 1) {
                  alert('{{__('main.choice_quantity')}}')
                  $('#product_quantity').focus();
                  return false;
              }
              if (!$.isNumeric(unit)) {
                  alert('{{__('main.choice_unit')}}')
                  $('#product_unit').focus();
                  return false;
              }
              input = '<input type="hidden" name="product_items[]" value="' + ikpu + '|' + title + '|' + unit + '|' + quantity + '|' + amount + '">'
              actions = '<td><span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>' + input + '</td>';

              $('table #product_table').append('<tr><td>' + ikpuText + '</td><td>' + title + '</td><td>' + amount + '</td><td>' + quantity + '</td><td>' + unitText + '</td>' + actions + '</tr>');
              $('#close_modal').click();
              $('#update_products').val(1);
          });
          $(document).on('click', '.remove_product', function () {
              $(this).parent().parent().remove();
              $('#update_products').val(1);
          });

          $('#contract_id').change(function () {
              var contract_id = $('#contract_id option:selected').val();
              if (clicked) return false;
              clicked = true;
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/modules/contract/get-company-info',
                  data: {'_token': _csrf_token, 'contract_id': contract_id, 'user_id': user_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('input[name=partner_inn]').val($response.data['inn']);
                          $('input[name=partner_company_name]').val($response.data['name']);
                          $('input[name=partner_address]').val($response.data['address']);
                          $('input[name=partner_bank_name]').val($response.data['bank_name']);
                          $('input[name=partner_bank_code]').val($response.data['bank_code']);
                          $('input[name=partner_mfo]').val($response.data['mfo']);
                          $('input[name=partner_oked]').val($response.data['oked']);
                          $('input[name=partner_director]').val($response.data['director']);
                          $('input[name=partner_accountant]').val($response.data['accountant']);
                      } else {
                          alert($response.error);
                      }
                      clicked = false;
                  },
                  error: function (e) {
                      alert(e)
                      clicked = false;
                  }
              });

          });

          function getIkpu() {
              let company_id = $('#company_id').val();
              if (company_id == '') return false;
              $('#product_ikpu').html('');
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/get-ikpu',
                  data: {'_token': _csrf_token, 'company_id': company_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('#product_ikpu').html($response.data);
                      } else {
                          alert($response.error);
                      }
                  },
                  error: function (e) {
                      alert(e)
                  }
              });
          }

          getIkpu();

      })

  </script>
@endsection
