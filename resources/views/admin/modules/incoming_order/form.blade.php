{{-- Extends layout --}}
@extends('layout.default')
@section('title', __('main.incoming_order'))

{{-- Content --}}
@section('content')

    @include('alert')
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($incoming_order)?$incoming_order->title_ru :'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <form method="POST"
                            @isset($incoming_order)
                              action="{{localeRoute('admin.modules.incoming_order.update',$incoming_order)}}"
                              @else
                              action="{{localeRoute('admin.modules.incoming_order.store')}}"
                            @endisset
                        >
                        @csrf
                            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-3 mb-3">
                                <div class="flex flex-col gap-2">
                                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
                                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="company_id" id="company_id" required>
                                        <option value="">{{__('main.choice_company')}}</option>
                                        @php
                                            $current_company = App\Models\Company::getCurrentCompanyId();
                                        @endphp
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" @if( (isset($incoming_order) && $incoming_order->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_number')}}</label>
                                    <input type="text" name="contract_number" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('contract_number') is-invalid @enderror @error('contract_number') is-invalid @enderror" placeholder="" value="{{old('contract_number',isset($incoming_order) ? $incoming_order->contract_number :'')}}" required>
                                    @error('contract_number')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="contragent" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{ __('main.contragent') }}</label>
                                    <div class="flex h-[56px] w-full">
                                        <input type="text" name="contragent" id="contragent" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contragent') is-invalid @enderror" style="width: 80%;" placeholder="{{__('main.enter_inn')}}" value="{{old('contragent',isset($incoming_order) ? $incoming_order->contragent :'')}}" required="">

                                        <button type="button" class="bg-gray-300 get_company p-3 get_company" style="width: 20%;"><i class="fa fa-clock-o "></i> {{ __('main.search') }}</button>
                                    </div>
                                    @error('contragent')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>

                            </div>
                            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-4 mb-3">
                                <div class="flex flex-col gap-2">
                                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.order_date')}}</label>
                                    <input type="date" name="order_date" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('order_date') is-invalid @enderror" placeholder="" value="{{old('order_date',isset($incoming_order) ? $incoming_order->order_date :'')}}" required>
                                    @error('order_date')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_company')}}</label>
                                    <input type="text" name="contragent_company" id="contragent_company" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('contragent_company') is-invalid @enderror" value="{{old('contragent_company',isset($incoming_order) ? $incoming_order->contragent_company :'')}}" required>
                                    @error('contragent_company')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_invoice')}}</label>
                                    <input type="text" name="contragent_bank_code" id="contragent_bank_code" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('contragent_bank_code') is-invalid @enderror" value="{{old('contragent_bank_code',isset($incoming_order) ? $incoming_order->contragent_bank_code :'')}}" required>
                                    @error('contragent_bank_code')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_date')}}</label>
                                    <input type="date" name="contract_date" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('contract_date') is-invalid @enderror" placeholder="" value="{{old('contract_date',isset($incoming_order) ? $incoming_order->contract_date :'')}}" required>
                                    @error('contract_date')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-4 mb-3">
                                <div class="flex flex-col gap-2">
                                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.casse')}}</label>
                                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('casse_id') is-invalid @enderror" name="casse_id" required>
                                        <option>{{__('main.choice_casse')}}</option>
                                        <option value="1">Касса 1</option>
                                        <option value="2">Касса 2</option>
                                            <?php /*@foreach($invoices as $invoice)
                                    <option value="{{$invoice->id}}" @if(isset($incoming_order) && $incoming_order->casse_id==$invoice->id) selected @endif >{{$invoice->getTitle()}}</option>
                                    @endforeach */ ?>
                                    </select>
                                    @error('casse_id')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.amount')}}</label>
                                    <input type="text" name="amount" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('amount') is-invalid @enderror" placeholder="" value="{{old('amount',isset($incoming_order) ? $incoming_order->amount :'')}}" required>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.movements')}}</label>
                                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('choice_movement') is-invalid @enderror" name="movement_id" required>
                                        <option>{{__('main.choice_movement')}}</option>
                                        @foreach($movements as $movement)
                                            <option value="{{$movement->id}}" @if(isset($incoming_order) && $incoming_order->movement_id==$movement->id) selected @endif >{{$movement->getTitle()}}</option>
                                        @endforeach
                                    </select>
                                    @error('movement_id')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.comment')}}</label>
                                    <textarea class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="comment" rows="6" >{{old('comment',isset($incoming_order) ? $incoming_order->comment :'')}}</textarea>
                                    @error('comment')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>
                                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="status">
                                    <option value="0" @if(isset($incoming_order) && $incoming_order->status==0) selected @endif >{{__('main.inactive')}}</option>
                                    <option value="2" @if(isset($incoming_order) && $incoming_order->status==2) selected @endif >{{__('main.draft')}}</option>
                                    <option value="1" @if(isset($incoming_order) && $incoming_order->status==1) selected @endif >{{__('main.active')}}</option>
                                </select>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

@endsection
