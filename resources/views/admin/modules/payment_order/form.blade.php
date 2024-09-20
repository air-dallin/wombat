{{-- Extends layout --}}
@extends('layout.default')
@section('title', __('main.payment_orders'))

{{-- Content --}}
@section('content')

  @include('alert')
  <?php
  /* <div class="page-titles">
             <ol class="breadcrumb">
                 <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                 <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($payment_order)?$payment_order->title_ru :'' }}</a></li>
             </ol>
         </div> */ ?>
  <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="POST"
                @isset($payment_order)
                  action="{{localeRoute('admin.modules.payment_order.update',$payment_order)}}"
                @else
                  action="{{localeRoute('admin.modules.payment_order.store')}}"
              @endisset
          >
            @csrf

            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-3 mb-3">
              <div class="flex flex-col gap-2">
                <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_number')}}</label>
                <input type="text" name="contract_number" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_number') is-invalid @enderror @error('contract_number') is-invalid @enderror" placeholder="" value="{{old('contract_number',isset($payment_order) ? $payment_order->contract_number :'')}}" required>
                @error('contract_number')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_date')}}</label>
                <input type="date" name="contract_date" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_date') is-invalid @enderror" placeholder="" value="{{old('contract_date',isset($payment_order) ? $payment_order->contract->contract_date :'')}}" required>
                @error('contract_date')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label for="contragent" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{ __('main.contragent') }}</label>
                <div class="flex h-[56px] w-full">
                  <input type="text" name="contragent" id="contragent" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contragent') is-invalid @enderror" style="width: 80%;" placeholder="{{__('main.enter_inn')}}" value="{{old('contragent',isset($payment_order) ? $payment_order->contragent :'')}}" required="">

                  <button type="button" class="bg-gray-300 get_company p-3 get_company" style="width: 20%;"><i class="fa fa-clock-o "></i> {{ __('main.search') }}</button>
                </div>
                @error('contragent')
                <small class="invalid-feedback"> {{ $message }} </small>
                @enderror
              </div>
            </div>
            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-4 mb-3">
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_company')}}</label>
                <input type="text" name="contragent_company" id="contragent_company" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contragent_company') is-invalid @enderror" value="{{old('contragent_company',isset($payment_order) ? $payment_order->contragent_company :'')}}" required>
                @error('contragent_company')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_invoice')}}</label>
                <input type="text" name="contragent_bank_code" id="contragent_bank_code" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contragent_bank_code') is-invalid @enderror" value="{{old('contragent_bank_code',isset($payment_order) ? $payment_order->contragent_bank_code :'')}}" required>
                @error('contragent_bank_code')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.invoices')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('invoice_id') is-invalid @enderror" name="invoice_id" required>
                  <option value="">{{__('main.choice_invoice')}}</option>
                  @foreach($invoices as $invoice)
                    <option value="{{$invoice->id}}" @if(isset($payment_order) && $payment_order->invoice_id==$invoice->id) selected @endif >{{$invoice->getTitle()}}</option>
                  @endforeach
                  {{--  @isset($company_invoices)
                        @foreach($company_invoices as $invoice)
                            <option value="{{$invoice->id}}" @if(isset($payment_order) && $payment_order->company_invoice_id==$invoice->id) selected @endif >{{$invoice->bank_invoice}}</option>
                        @endforeach
                    @endisset--}}
                </select>
                @error('invoice_id')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="company_id" id="company_id" required>
                  <option value="">{{__('main.choice_company')}}</option>
                  @foreach($companies as $company)
                    <option value="{{$company->id}}" @if(isset($payment_order) && $payment_order->company_id==$company->id) selected @endif >{{$company->name}}</option>
                  @endforeach
                </select>
                @error('casse_id')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
            </div>
            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-4 mb-3">
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company_invoices')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('company_invoice_id') is-invalid @enderror" name="company_invoice_id" id="company_invoice_id" required>
                  <option value="">{{__('main.choice_company_invoice')}}</option>
                  @isset($payment_order)
                    @foreach($company_invoices as $invoice)
                      <option value="{{$invoice->id}}" @if(isset($payment_order) && $payment_order->company_invoice_id==$invoice->id) selected @endif >{{$invoice->bank_invoice}}</option>
                    @endforeach
                  @endisset
                </select>
                @error('company_invoice_id')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.amount')}}</label>
                <input type="text" name="amount" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('amount') is-invalid @enderror" placeholder="" value="{{old('amount',isset($payment_order) ? $payment_order->amount :'')}}" required>
                @error('amount')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.movements')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('choice_movement') is-invalid @enderror" name="movement_id" required>
                  <option value="">{{__('main.choice_movement')}}</option>
                  @foreach($movements as $movement)
                    <option value="{{$movement->id}}" @if(isset($payment_order) && $payment_order->movement_id==$movement->id) selected @endif >{{$movement->getTitle()}}</option>
                  @endforeach
                </select>
                @error('movement_id')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="status" required>
                  {{--<option value="0" @if(isset($payment_order) && $payment_order->status==0) selected @endif >{{__('main.inactive')}}</option>--}}
                  <option value="2" @if(isset($payment_order) && $payment_order->status==2) selected @endif >{{__('main.draft')}}</option>
                  {{--
                  пока отключить отправку на внешний сервис
                  <option value="3" @if(isset($payment_order) && $payment_order->status==3) selected @endif >{{__('main.send')}}</option>--}}
                  <option value="1" @if(isset($payment_order) && $payment_order->status==1) selected @endif >{{__('main.active')}}</option>
                </select>
              </div>
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.comment')}}</label>
              <textarea class="h-24 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="comment" rows="6">{{old('comment',isset($payment_order) ? $payment_order->comment :'')}}</textarea>
              @error('comment')
              <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
              @enderror
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


@section('js')
  <script>
      $(document).ready(function () {
          var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';
          var clicked = false;
          $('.get_company').click(function () {
              let inn = $('#contragent').val();
              if (inn == '') return false;
              if (clicked) return false;
              clicked = true;
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/get-company-by-inn',
                  data: {'_token': _csrf_token, 'inn': inn, 'user_id': user_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('input[name=contragent_company]').val($response.data['name']);
                          //$('input[name=address]').val($response.data['address']);
                          //$('input[name=bank_name]').val($response.data['bank_name']);
                          $('input[name=contragent_bank_code]').val($response.data['bank_code']);
                          //$('input[name=mfo]').val($response.data['mfo']);
                          //$('input[name=oked]').val($response.data['oked']);
                          //$('input[name=nds_code]').val($response.data['nds_code']);
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

          $('#company_id').change(function () {
              let company_id = $(this).val();
              if (company_id == '') return false;
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/modules/payment_order/invoices',
                  data: {'_token': _csrf_token, 'company_id': company_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('#company_invoice').val($response.data);
                      } else {
                          alert($response.error);
                      }
                  },
                  error: function (e) {
                      alert(e)
                  }
              });
          });

          $('#company_id').change();

      })

  </script>
@endsection
