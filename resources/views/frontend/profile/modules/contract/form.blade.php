@extends('layouts.profile')
@section('title')

  @include('frontend.profile.sections.document_create_menu',['currentMenuType'=>'contract'])

@endsection
@section('content')

  @include('alert-profile')
  <div class="container-fluid">
    <div class="">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form method="POST"
                  @isset($contract)
                    action="{{localeRoute('frontend.profile.modules.contract.update',$contract)}}"
                  @else
                    action="{{localeRoute('frontend.profile.modules.contract.store')}}"
                @endisset
            >
              @csrf

              <input type="hidden" name="update_products" id="update_products" value="0">
              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">
                <div class="flex flex-col mt-3 mb-3 gap-2 input-group">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.plan')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('plan_id') is-invalid @enderror" name="plan_id" required>
                    <option value="0">{{__('main.choice_invoice')}}</option>
                    @isset($plans)
                      @foreach($plans as $plan)
                        <option value="{{$plan->id}}"
                                @if((isset($contract) && $contract->plan_id==$plan->id) || (!empty($selected_plan) && $selected_plan->plan_id==$plan->id)) selected @endif >{{$plan->code . ' - ' . $plan->title_ru }}
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


                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">

                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_number')}}</label>
                    <input type="text" name="contract_number" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_number') is-invalid @enderror @error('contract_number') is-invalid @enderror" placeholder="" value="{{old('contract_number',isset($contract) ? $contract->contract_number : $new_number)}}" required>
                    @error('contract_number')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_date')}}</label>
                    <input type="date" name="contract_date" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_date') is-invalid @enderror" placeholder="" value="{{old('contract_date',isset($contract) ? $contract->contract_date :date('Y-m-d'))}}" required>
                    @error('contract_date')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_expire')}}</label>
                    <input type="date" name="contract_expire" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_expire') is-invalid @enderror" placeholder="" value="{{old('contract_expire',isset($contract) ? $contract->contract_expire :date('Y-m-d'))}}" required>
                    @error('contract_expire')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_place')}}</label>
                    <input type="text" name="contract_place" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_place') is-invalid @enderror" placeholder="" value="{{old('contract_place',isset($contract) ? $contract->contract_place :'')}}" required>
                    @error('contract_place')
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
                               value="{{old('company_inn',isset($contract) ? $contract->company_inn :'')}}">
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
                            <option value="{{$_company->id}}" @if( (isset($contract) && $contract->company_id==$_company->id) || $_company->id==$current_company) selected @endif >{{$_company->name}}</option>
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
                          @isset($contract->company->invoices)
                            @foreach($contract->company->invoices as $invoice)
                              <option value="{{$invoice->id}}"
                                      @if(isset($contract) && $contract->company_account==$invoice->bank_invoice) selected @endif >{{$invoice->bank_invoice}}</option>
                                      {{--@if(isset($contract) && $contract->company_invoice_id==$invoice->id) selected @endif >{{$invoice->bank_invoice}}</option>--}}
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
                               value="{{old('company_mfo',isset($contract) ? $contract->company_mfo :'')}}" required>
                        @error('company_mfo')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                    </div>
                    <div class="flex flex-col mb-3 gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.address')}}</label>
                      <input type="text" name="company_address" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                             value="{{old('company_address',isset($contract) ? $contract->company_address :'')}}"
                             required>
                      @error('company_address')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.director')}}</label>
                        <input type="text" name="company_director" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('company_director',isset($contract) ? $contract->company_director :'')}}"
                               required>
                        @error('company_director')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.accountant')}}</label>
                        <input type="text" name="company_accountant" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('company_accountant',isset($contract) ? $contract->company_accountant :'')}}"
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
                      <div class="flex flex-col mt-3 mb-3 gap-2 input-group">
                        <label for="partner_inn" class="text-base font-medium text-bgray-600 dark:text-bgray-50 control-label">{{__('main.enter_inn')}}</label>
                        <div class="flex h-[56px] w-full">
                          <input type="text" name="partner_inn" id="partner_inn" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('partner_inn') placeholder="{{__('main.enter_inn')}}" is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('partner_inn',isset($contract) ? $contract->partner_inn :'')}}" style="width: 70%;" required>
                          @error('partner_inn')
                          <small class="invalid-feedback"> {{ $message }} </small>
                          @enderror
                          <button type="button" class="bg-gray-300 get_company p-3 get_company" style="width: 30%;"><i class="fa fa-search send "></i></button>
                        </div>


                      </div>
                      <div class="flex flex-col mt-3 mb-3 gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.partner_company_name')}}</label>
                        <input type="text" name="partner_company_name" id="partner_company_name" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_company_name',isset($contract) ? $contract->partner_company_name :'')}}" required>
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
                        <input type="text" name="partner_bank_code" id="partner_bank_code" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_bank_code',isset($contract) ? $contract->partner_bank_code :'')}}" required>
                        @error('partner_bank_code')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                      </div>
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.mfo')}}</label>
                        <input type="text" name="partner_mfo" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_mfo',isset($contract) ? $contract->partner_mfo :'')}}" required>
                        @error('partner_mfo')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                    </div>
                    <div class="flex flex-col mb-3 gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.address')}}</label>
                      <input type="text" name="partner_address" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                             value="{{old('partner_address',isset($contract) ? $contract->partner_address :'')}}"
                             required>
                      @error('partner_address')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.director')}}</label>
                        <input type="text" name="partner_director" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_director',isset($contract) ? $contract->partner_director :'')}}"
                               required>
                        @error('partner_director')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                      </div>
                      <div class="flex flex-col mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.accountant')}}</label>
                        <input type="text" name="partner_accountant" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                               value="{{old('partner_accountant',isset($contract) ? $contract->partner_accountant :'')}}"
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
                <div class="flex justify-between border-b border-bgray-200 pb-5 text-bgray-900 dark:border-darkblack-400">
                  <h3 class="text-2xl font-bold dark:text-white">{{__('main.products')}}</h3>
                  <!-- Button trigger modal -->
                  <div class="modal-open cursor-pointer" id="add_product_items"><i class="fa fa-plus"></i> {{__('main.add')}}</div>
                </div>
                <table class="w-full" style="white-space: normal">
                  <tbody id="product_table">
                  <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.ikpu')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.description')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.barcode')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.unit')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.quantity')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.amount')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.summa')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.nds')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.nds_summa')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{__('main.total')}}</td>
                    <th></th>
                  </tr>

                  @if( !empty($contract) && isset($contract->items))
                    @foreach($contract->items as $item)
                      @php
                        /** @var $item */
                        $ndsTitle = $item->nds->getTitle();
                        $ndsValue = preg_replace('/[^.0-9]/','',$ndsTitle);
                        $summa = $item->amount * $item->quantity;
                        $ndsSumma = is_numeric($ndsValue) ? $summa/100*$ndsValue : 0;
                        $total = $summa + $ndsSumma;
                        $warehouse_id = isset($item->warehouse) ? $item->warehouse->id : 0;
                      @endphp
                      <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$item->ikpu->code}}</td>
                        {{--                          <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$item->ikpu->code .' - ' . \Illuminate\Support\Str::limit($item->ikpu->title_ru,32)}}</td>--}}
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$item->title}}</td>
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$item->barcode}}</td>
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$item->package->getTitle()}}</td>
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$item->quantity}}</td>
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$item->amount}}</td>
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$summa}}</td>
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$ndsTitle}}</td>
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$ndsSumma}}</td>
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">{{$total}}</td>
                        {{-- <td>@isset($item->warehouse) {{$item->warehouse->getTitle()}} @else {{__('main.not_set')}}@endisset</td>
                         <td>{{App\Models\Product::getOriginLabel($item->product_origin)}}</td>--}}
                        <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-3 py-2 ">
                          <span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>
                          <input type="hidden" name="product_items[]" value="{{$item->ikpu_id}}|{{$item->title}}|{{$item->barcode}}|{{$item->unit_id}}|{{$item->quantity}}|{{$item->amount}}|{{$item->nds_id}}"> {{--|{{$warehouse_id}}|{{$item->product_origin}}"> --}}
                        </td>
                      </tr>
                    @endforeach
                  @endif
                  </tbody>
                </table>
              </div>
              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">
                <div class="flex flex-col mb-3 gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_name')}}</label>
                  <input type="text" name="contract_name" id="contract_name" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_name') is-invalid @enderror" value="{{old('contract_name',isset($contract) ? $contract->contract_name :'')}}" required>
                  @error('contract_name')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_text')}}</label>
                  <textarea class="h-34 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="contract_text" rows="6" required>{{old('contract_text',isset($contract) ? $contract->contract_text :'')}}</textarea>
                  @error('contract_text')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>
              </div>
              @if(!isset($contract) || $contract->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING)
                <div class="flex justify-end">

                  <button type="submit" class="rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
                  <button type="submit" class="rounded-lg px-4 py-3.5 font-semibold text-white" style="background: green; margin: 0 15px;">{{__('main.sign')}}</button>
                  <button type="submit" class="rounded-lg px-4 py-3.5 font-semibold text-white" style="background: red">{{__('main.reject')}}</button>

                </div>
              @endif
            </form>

          </div>

        </div>

      </div>
    </div>
  </div>

@endsection
<!-- component -->
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
                  <div class="flex flex-col gap-2">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.ikpu')}}</label>
                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" id="product_ikpu" style="width: 100% !important;">
                      <option value="">{{__('main.choice_ikpu')}}</option>
                      @isset($ikpu)
                        @foreach($ikpu as $item)
                          <option value="{{$item->id}}" title="{{$item->title_ru}}">{{ $item->code .' - '. \Illuminate\Support\Str::limit( $item->title_ru,32) }}</option>
                        @endforeach
                      @endisset
                    </select>
                  </div>
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.description')}}</label>
                    <input type="text" id="product_title" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="">
                  </div>
                </div>
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2 mb-3">
                  <div class="flex flex-col gap-2">
                    <label for="" class="form-label">{{__('main.barcode')}}</label>
                    <input type="text" id="product_barcode" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="">
                  </div>
                  <div class="flex flex-col gap-2">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.nds')}}</label>
                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" id="product_nds">
                      <option value="">{{__('main.choice_nds')}}</option>
                      @isset($nds)
                        @foreach($nds as $item)
                          <option value="{{$item->id}}" data-nds="{{preg_replace('/[^.0-9]/','',$item->getTitle())}}">{{$item->getTitle()}}</option>
                        @endforeach
                      @endisset
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2  mb-3">
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.amount')}}</label>
                    <input type="text" id="product_amount" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="">
                  </div>
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.quantity')}}</label>
                    <input type="text" id="product_quantity" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="">
                  </div>
                </div>
                <div class="flex flex-col gap-2 mb-5">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.units')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" id="product_unit">
                    <option value="">{{__('main.choice_unit')}}</option>
                    @isset($packages)
                      @foreach($packages as $package)
                        <option value="{{$package->id}}">{{$package->getTitle() . ' (' . $package->code .')'}}</option>
                      @endforeach
                    @endisset
                  </select>

                </div>
              </div>
            </div>
            <div class="py-2 flex justify-end items-center space-x-4">
              {{--              <button class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" data-dismiss="modal"  style="border: 1px solid #718096; color: #718096;">{{__('main.close')}}</button>--}}
              <div class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition -btn-create" id="add_product" _data-dismiss="_modal" style="background: orange">{{__('main.add')}}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal End-->


@include('frontend.sections.notify')
@section('js')
  <script>
      $(document).ready(function () {
          var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';
          var clicked = false;
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
              if (!$.isNumeric($('#company_id').val())) {
                  alert('{{__('main.choice_company')}}')
                  $('#company_id').focus();
                  return false;
              }
              if ($('#partner_inn').val().length < 1) {
                  alert('{{__('main.choice_contragent')}}')
                  $('#partner_inn').focus();
                  return false;
              }
              if ($('#date').val().length < 1) {
                  alert('{{__('main.choice_date')}}')
                  $('#date').focus();
                  return false;
              }
              if ($('#partner_company_name').val().length < 1) {
                  alert('{{__('main.choice_contragent_company')}}')
                  $('#partner_company_name').focus();
                  return false;
              }
              if ($('#partner_bank_code').val().length < 1) {
                  alert('{{__('main.choice_contragent_bank_code')}}')
                  $('#partner_bank_code').focus();
                  return false;
              }

              if ($('table #product_table tr').length == 0) {
                  e.preventDefault();
                  alert('{{__('main.products_not_set')}}')
                  return false;
              }
              //if(!$(e.target).parents('#form_contract').isValid()) e.preventDefault();
              $('#form_contract').submit();
          });

          $('.get_company').click(function () {
              let inn = $('#partner_inn').val();
              if (inn == '') return false;
              if (clicked) return false;
              clicked = true;
              notify('{{__('main.wait')}}', 'primary', 10000);
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
                          notify('{{__('main.success')}}', 'success', 3000);
                      } else {
                          notify($response.error, 'danger', 10000);
                      }
                      clicked = false;
                  },
                  error: function (e) {
                      alert(e)
                      clicked = false;
                  }
              });
          });

          $('#add_product_items').click(function () {
              $('#product_ikpu').val('');
              $('#product_title').val('');
              $('#product_unit').val('');
              $('#product_quantity').val('');
              $('#product_amount').val('');
              $('#product_barcode').val('');
              $('#product_nds').val('');
              /*$('#product_warehouse').val('');
              $('#product_origin').val('');*/
          });
          $('#add_product').click(function (e) {

              ikpu = $('#product_ikpu').val();
              ikpuText = $('#product_ikpu option:selected').text();
              title = $('#product_title').val();
              barcode = $('#product_barcode').val();
              unit = $('#product_unit').val();
              quantity = $('#product_quantity').val();
              amount = $('#product_amount').val();
              unitText = $('#product_unit option:selected').text();
              nds = $('#product_nds').val();
              ndsText = $('#product_nds option:selected').text();
              /*warehouse = $('#product_warehouse').val()
              warehouseText = $('#product_warehouse option:selected').text();
              origin = $('#product_origin').val();
              originText = $('#product_origin option:selected').text();*/

              if (!$.isNumeric(ikpu)) {
                  alert('{{__('main.choice_ikpu')}}')
                  $('#product_ikpu').focus();
                  return false;
              }
              if (title.length < 1) {
                  alert('{{__('main.choice_description')}}')
                  $('#product_title').focus();
                  return false;
              }
              if (!$.isNumeric(unit)) {
                  alert('{{__('main.choice_unit')}}')
                  $('#product_unit').focus();
                  return false;
              }
              if (!$.isNumeric(quantity) || quantity < 1) {
                  alert('{{__('main.choice_quantity')}}')
                  $('#product_quantity').focus();
                  return false;
              }
              if (!$.isNumeric(amount) || amount < 1) {
                  alert('{{__('main.choice_amount')}}')
                  $('#product_amount').focus();
                  return false;
              }
              if (!$.isNumeric(nds)) {
                  alert('{{__('main.choice_nds')}}')
                  $('#product_nds').focus();
                  return false;
              }
              /*    if(!$.isNumeric(warehouse)) {
                      alert('{ {__('main.choice_warehouse')}}')
                      $('#product_warehouse').focus();
                      return false;
                  }

                  if(!$.isNumeric(origin)) {
                      alert('{ {__('main.choice_product_origin')}}')
                      $('#product_origin').focus();
                      return false;
                  }*/

              ndsValue = $('#product_nds option:selected').data('nds');

              summa = amount * quantity;
              ndsSumma = summa / 100 * ndsValue;
              total = summa + ndsSumma;

              input = '<input type="hidden" name="product_items[]" value="' + ikpu + '|' + title + '|' + barcode + '|' + unit + '|' + quantity + '|' + amount + '|' + nds + '">' /* |'+warehouse+'|'+origin+'">'*/
              actions = '<td><span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>' + input + '</td>';

              $('table #product_table').append('<tr class="border-b border-bgray-300 dark:border-darkblack-400"><td style="max-width: 80px; overflow: hidden; white-space: nowrap" class="px-3 py-2">' + ikpuText + '</td><td class="px-3 py-2">' + title + '</td><td class="px-3 py-2">' + barcode + '</td>' + '</td><td class="px-3 py-2">' + unitText + '</td>' + '</td><td class="px-3 py-2">' + quantity + '</td><td class="px-3 py-2">' + amount + '</td><td class="px-3 py-2">' + summa + '</td><td class="px-3 py-2">' + ndsText + '</td><td class="px-3 py-2">' + ndsSumma + '</td><td class="px-3 py-2">' + total + '</td>' /*<td>'+warehouseText+'</td><td>'+originText+'</td>'*/ + actions + '</tr>');
              $('#multi-step-modal').addClass('hidden');
              $('#update_products').val(1);
          });

          $(document).on('click', '.remove_product', function () {
              $(this).parent().parent().remove();
              $('#update_products').val(1);
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
                          if ($response.error != undefined) alert($response.error);
                      }
                      if ($response.data == undefined) notify('{{__('main.ikpu_not_set')}}', 'danger', 5000);
                  },
                  error: function (e) {
                      alert(e)
                  }
              });
          }

          getIkpu();
          /*$('#product_ikpu').select2({
              maximumSelectionLength: Infinity,
          });*/
        @if(!isset($contract) && $current_company) /*$current_company)*/
        $('#company_id option[value={{$current_company}}]').attr('selected', 'selected').change();
        @endif
      })

  </script>
@endsection

