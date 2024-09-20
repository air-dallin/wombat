@extends('layouts.profile')
@section('title',__('main.incoming_orders'))
@section('content')

  @include('alert-profile')
  <div class="container-fluid">
    <div class="p-5 rounded-lg bg-white dark:bg-darkblack-600">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form method="POST"
                  @isset($incoming_order)
                    action="{{localeRoute('frontend.profile.modules.incoming_order.update',$incoming_order)}}"
                  @else
                    action="{{localeRoute('frontend.profile.modules.incoming_order.store')}}"
                @endisset
            >
              @csrf
              <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('contract_id') is-invalid @enderror" name="contract_id" id="contract_id" required>
                    <option value="">{{__('main.choice_contract')}}</option>
                    @foreach($contracts as $contract)
                      <option value="{{$contract->id}}" data-date="{{ $contract->contract_date }}" @if(isset($incoming_order) && $incoming_order->contract_id==$contract->id) selected @endif >{{ $contract->contract_number . ' - ' . date('Y-m-d',strtotime($contract->contract_date)) }}</option>
                    @endforeach
                  </select>
                  @error('contract_id')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>
                <div class="flex flex-col gap-2">
                  <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.order_date')}}</label>
                  <input type="date" name="order_date" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('order_date') is-invalid @enderror" placeholder="" value="{{old('order_date',isset($incoming_order) ? $incoming_order->order_date :date('Y-m-d'))}}" required>
                  @error('order_date')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>

                <div class="flex flex-col gap-2">
                  <label for="contragent" class="form-label">{{__('main.contragent')}}</label>
                  <div class="flex h-[56px] w-full">
                    <input type="text" name="contragent" id="contragent" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('contragent') is-invalid @enderror" style="width: 80%;" placeholder="{{__('main.enter_inn')}}" value="{{old('contragent',isset($incoming_order) ? $incoming_order->contragent :'')}}" required>
                    @error('contragent')
                    <small class="invalid-feedback"> {{ $message }} </small>
                    @enderror

                    <button type="button" class="bg-gray-300 get_company p-3 get_company" style="width: 20%;"><i class="fa fa-search send"></i></button>
                  </div>
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_company')}}</label>
                  <input type="text" name="contragent_company" id="contragent_company" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('contragent_company') is-invalid @enderror" value="{{old('contragent_company',isset($incoming_order) ? $incoming_order->contragent_company :'')}}" required>
                  @error('contragent_company')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_invoice')}}</label>
                  <input type="text" name="contragent_bank_code" id="contragent_bank_code" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('contragent_bank_code') is-invalid @enderror" value="{{old('contragent_bank_code',isset($incoming_order) ? $incoming_order->contragent_bank_code :'')}}" required>
                  @error('contragent_bank_code')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  " name="company_id" id="company_id" required>
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
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.casse')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('casse_id') is-invalid @enderror" name="casse_id" id="casse_id" required>
                    <option>{{__('main.choice_casse')}}</option>
                    @isset($company_casses)
                      @foreach($company_casses as $casse)
                        <option value="{{$casse->id}}" @if(isset($incoming_order) && $incoming_order->casse_id==$casse->id) selected @endif >{{$casse->getTitle()}}</option>
                      @endforeach
                    @endisset
                  </select>
                  @error('casse_id')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>

                <div class="flex flex-col gap-2">
                  <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.amount')}}</label>
                  <input type="text" name="amount" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('amount') is-invalid @enderror" placeholder="" value="{{old('amount',isset($incoming_order) ? $incoming_order->amount :'')}}" required>
                  @error('amount')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>


                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.movements')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('choice_movement') is-invalid @enderror" name="movement_id" required>
                    <option value="">{{__('main.choice_movement')}}</option>
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
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  " name="status">
                    <option value="0" @if(isset($incoming_order) && $incoming_order->status==0) selected @endif >{{__('main.inactive')}}</option>
                    <option value="2" @if(isset($incoming_order) && $incoming_order->status==2) selected @endif >{{__('main.draft')}}</option>
                    <option value="1" @if(isset($incoming_order) && $incoming_order->status==1) selected @endif >{{__('main.active')}}</option>
                  </select>
                </div>

              </div>
              <div class="flex flex-col mt-5">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.comment')}}</label>
                <textarea class="h-24 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  " name="comment" rows="6">{{old('comment',isset($incoming_order) ? $incoming_order->comment :'')}}</textarea>
                @error('comment')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>


              <div class="flex justify-end">
                @isset($incoming_order)
                  <a href="{{localeRoute('frontend.profile.modules.incoming_order.print',$incoming_order)}}" target="_blank" class="btn-create btn btn-info" title="{{__('main.print')}}"><i class="fa fa-print"></i></a>
                  <a href="{{localeRoute('frontend.profile.modules.incoming_order.exportPdf',$incoming_order)}}" target="_blank" class="btn-create btn btn-info" title="{{__('main.pdf')}}"><i class="fa fa-file-pdf"></i></a>

                @endif
                <button type="submit" class="mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
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
                  url: '/ru/profile/modules/incoming_order/casses',
                  data: {'_token': _csrf_token, 'company_id': company_id},
                  success: function ($response) {
                      $('#casse_id').html('');
                      if ($response.status) {
                          $('#casse_id').html($response.data);
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
                          $('input[name=contragent]').val($response.data['inn']);
                          $('input[name=contragent_company]').val($response.data['name']);
                          $('input[name=contragent_bank_code]').val($response.data['bank_code']);
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

      })

  </script>
@endsection
