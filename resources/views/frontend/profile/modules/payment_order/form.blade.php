@extends('layouts.profile')
@section('title', __('main.payment_orders'))
@section('content')

  @include('alert-profile')
  <div class="container-fluid">
    <div class="">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form method="POST"
                  @isset($payment_order)
                    action="{{localeRoute('frontend.profile.modules.payment_order.update',$payment_order)}}"
                  @else
                    action="{{localeRoute('frontend.profile.modules.payment_order.store')}}"
                @endisset
            >
              @csrf
              <div class="p-5 rounded-lg bg-white dark:bg-darkblack-600 mb-5">
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contract_id') is-invalid @enderror" name="contract_id" id="contract_id" required>
                    <option value="">{{__('main.choice_contract')}}</option>
                    @foreach($contracts as $contract)
                      <option value="{{$contract->id}}" data-date="{{ $contract->contract_date }}" @if(isset($payment_order) && $payment_order->contract_id==$contract->id) selected @endif >{{ $contract->contract_number . ' - ' .$contract->contract_date }}</option>
                    @endforeach
                  </select>
                  @error('contract_id')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>
              </div>

              <div class="p-5 rounded-lg bg-white dark:bg-darkblack-600 mb-5">
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                  <div>
                    <h3 class="border-b border-bgray-200 pb-5 text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">Ваша компания</h3>
                    <div class="flex flex-col gap-2 mb-3 mt-3">
                      <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
                      <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="company_id" id="company_id" required>
                        <option value="">{{__('main.choice_company')}}</option>
                        @php
                          $current_company = App\Models\Company::getCurrentCompanyId();
                        @endphp
                        @foreach($companies as $company)
                          <option value="{{$company->id}}" @if( (isset($payment_order) && $payment_order->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                        @endforeach
                      </select>
                      @error('company_id')
                      <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                      @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                      <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company_invoices')}}</label>
                      <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('company_invoice_id') is-invalid @enderror" name="company_invoice_id" id="company_invoice_id" required>
                        <option value="">{{__('main.choice_company_invoice')}}</option>
                        @isset($company_invoices)
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
                  </div>
                  <div>
                    <h3 class="border-b border-bgray-200 pb-5 text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">Информация компании партнера</h3>
                    <div class="flex flex-col gap-2 mb-3 mt-3">
                      <label for="contragent" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent')}}</label>
                      <div class="flex h-[56px] w-full">
                        <input type="text" name="inn_ct" id="contragent" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('inn_ct') is-invalid @enderror" style="width: 80%;" placeholder="{{__('main.enter_inn')}}" value="{{old('inn_ct',isset($payment_order) ? $payment_order->inn_ct :'')}}" required>
                        @error('inn_ct')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror

                        <button type="button" class="bg-gray-300 get_company p-3 get_company" style="width: 20%;"><i class="fa fa-search send"></i></button>
                      </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_company')}}</label>
                        <input type="text" name="name_ct" id="contragent_company" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contragent_company') is-invalid @enderror" value="{{old('name_ct',isset($payment_order) ? $payment_order->name_ct :'')}}" required>
                        @error('name_ct')
                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                      </div>
                      <div class="flex flex-col gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_invoice')}}</label>
                        <input type="text" name="acc_ct" id="contragent_bank_code" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('acc_ct') is-invalid @enderror" value="{{old('acc_ct',isset($payment_order) ? $payment_order->acc_ct :'')}}" required>
                        @error('acc_ct')
                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                      </div>
                    </div>


                  </div>

                </div>
              </div>
              <div class="p-5 rounded-lg bg-white dark:bg-darkblack-600 mb-5">
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2 mb-3">
           {{--       <div class="flex flex-col gap-2">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.invoice')}}</label>
                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('invoice_id') is-invalid @enderror" name="invoice_id" required>
                      <option value="">{{__('main.choice_invoice')}}</option>
                      @foreach($invoices as $invoice)
                        <option value="{{$invoice->id}}" @if(isset($payment_order) && $payment_order->invoice_id==$invoice->id) selected @endif >{{$invoice->getTitle()}}</option>
                      @endforeach
                    </select>
                    @error('invoice_id')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>--}}
                  <div class="flex flex-col gap-2">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.movements')}}</label>
                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('choice_movement') is-invalid @enderror" name="movement_id" required>
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
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.purpose')}}</label>
                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('choice_purpose') is-invalid @enderror" name="purpose_id" id="purpose" required>
                      <option value="">{{__('main.choice_purpose')}}</option>
                      @foreach($purposes as $purpose)
                        <option value="{{$purpose->id}}" @if(isset($payment_order) && $payment_order->purpose_id==$purpose->id) selected @endif >{{$purpose->code . ' - ' . Str::limit($purpose->getTitle(),160)}}</option>
                      @endforeach
                    </select>
                    @error('purpose_id')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                </div>
                <div class="flex flex-col gap-2">
                  <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.amount')}}</label>
                  <input type="text" name="amount" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('amount') is-invalid @enderror" placeholder="" value="{{old('amount',isset($payment_order) ? $payment_order->amount :'')}}" required>
                  @error('amount')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>
                <div class="flex flex-col mt-5">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.purpose')}}</label>
                  <textarea class="h-24 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="purpose" rows="6">{{old('purpose',isset($payment_order) ? $payment_order->purpose :'')}}</textarea>
                  @error('purpose')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>
              </div>


              {{--   <div class="flex flex-col gap-2">
                   <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.serial')}}</label>
                   <input type="text" name="serial" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('serial') is-invalid @enderror" placeholder="" value="{{old('serial',isset($payment_order) ? $payment_order->serial :'')}}" required>
                   @error('serial')
                   <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                   @enderror
                 </div>

                 <div class="flex flex-col gap-2">
                   <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.signature')}}</label>
                   <input type="text" name="signature" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('signature') is-invalid @enderror" placeholder="" value="{{old('signature',isset($payment_order) ? $payment_order->signature :'')}}" required>
                   @error('signature')
                   <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                   @enderror
                 </div>--}}




              {{--<div class="flex flex-col mt-5">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.comment')}}</label>
                <textarea class="h-24 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="comment" rows="6">{{old('comment',isset($payment_order) ? $payment_order->comment :'')}}</textarea>
                @error('comment')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>--}}


              <div class="flex justify-end">
                @isset($payment_order)
                  <a href="{{localeRoute('frontend.profile.modules.payment_order.print',$payment_order)}}" target="_blank" class="btn-create btn btn-info" title="{{__('main.print')}}"><i class="fa fa-print"></i></a>
                @endif
                <button type="submit" name="send_state" value="draft" class="btn-creat rounded-lg px-4 py-3.5 font-semibold text-white" style="margin-right: 10px;border: 1px solid #ffa500; color: #ffa500;">{{__('main.draft')}}</button>
                <button type="submit" name="send_state" value="public" class="btn-create rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
              </div>

            </form>

          </div>

        </div>

      </div>
    </div>
  </div>

@endsection


@section('js')
  <script>
      $(document).ready(function () {
          var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';

          $('#company_id').change(function () {
              let company_id = $(this).val();
              if (company_id == '') return false;
              $('#company_invoice_id').html('');
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/modules/payment_order/invoices',
                  data: {'_token': _csrf_token, 'company_id': company_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('#company_invoice_id').html($response.data);
                      } else {
                          alert($response.error);
                      }
                  },
                  error: function (e) {
                      alert(e)
                  }
              });
          });


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
                          $('input[name=name_ct]').val($response.data['name']);
                          //$('input[name=address]').val($response.data['address']);
                          //$('input[name=bank_name]').val($response.data['bank_name']);
                          $('input[name=acc_ct]').val($response.data['bank_code']);
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

          $('#company_id').change();

          $('#contract_id').change(function () {
              var contract_id = $('#contract_id option:selected').val();
              if (contract_id == '') return false;
              if (clicked) return false;
              clicked = true;
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/modules/contract/get-company-info',
                  data: {'_token': _csrf_token, 'contract_id': contract_id, 'user_id': user_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('input[name=inn_ct]').val($response.data['inn']);
                          $('input[name=name_ct]').val($response.data['name']);
                          $('input[name=acc_ct]').val($response.data['bank_code']);
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
          $('#purpose').select2();
      });

  </script>
@endsection
