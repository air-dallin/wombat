{{-- Extends layout --}}
@extends('layout.default')
@section('title', __('main.contract'))

{{-- Content --}}
@section('content')

    @include('alert')
    <div class="container-fluid">
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($contract)?$contract->title_ru :'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
            <div class="col-12">

                <div class="card">



                    <div class="card-body">
                        <form method="POST"
                            @isset($contract)
                              action="{{localeRoute('admin.modules.contract.update',$contract)}}"
                              @else
                              action="{{localeRoute('admin.modules.contract.store')}}"
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
                                            <option value="{{$company->id}}" @if( (isset($contract) && $contract->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('casse_id')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_number')}}</label>
                                    <input type="text" name="contract_number" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('contract_number') is-invalid @enderror @error('contract_number') is-invalid @enderror" placeholder="" value="{{old('contract_number',isset($contract) ? $contract->contract_number :'')}}" required>
                                    @error('contract_number')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="contragent" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{ __('main.contragent') }}</label>
                                    <div class="flex h-[56px] w-full">
                                        <input type="text" name="contragent" id="contragent" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('contragent') is-invalid @enderror" style="width: 80%;" placeholder="{{__('main.enter_inn')}}" value="{{old('contragent',isset($contract) ? $contract->contragent :'')}}" required="">

                                        <button type="button" class="bg-gray-300 get_company p-3 get_company" style="width: 20%;"><i class="fa fa-clock-o "></i> {{ __('main.search') }}</button>
                                    </div>
                                    @error('contragent')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-3 mb-3">
                                <div class="flex flex-col gap-2">
                                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_date')}}</label>
                                    <input type="date" name="contract_date" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('contract_date') is-invalid @enderror" placeholder="" value="{{old('contract_date',isset($contract) ? $contract->contract_date :'')}}"  required>
                                    @error('contract_date')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                            <div class="flex flex-col gap-2">
                                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_company')}}</label>
                                <input type="text" name="contragent_company" id="contragent_company" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('contragent_company') is-invalid @enderror" value="{{old('contragent_company',isset($contract) ? $contract->contragent_company :'')}}" required>
                                @error('contragent_company')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-2">
                                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contragent_invoice')}}</label>
                                <input type="text" name="contragent_bank_code" id="contragent_bank_code" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('contragent_bank_code') is-invalid @enderror" value="{{old('contragent_bank_code',isset($contract) ? $contract->contragent_bank_code :'')}}" required>
                                @error('contragent_bank_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>



                            </div>
                            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-3 mb-3">
                                <div class="flex flex-col gap-2">
                                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.amount')}}</label>
                                    <input type="text" name="amount" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('amount') is-invalid @enderror" placeholder="" value="{{old('amount',isset($contract) ? $contract->amount :'')}}" required>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract_text')}}</label>
                                    <textarea class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="contract_text" rows="6" required>{{old('contract_text',isset($contract) ? $contract->contract_text :'')}}</textarea>
                                    @error('contract_text')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>
                                    <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="status">
                                        <option value="0" @if(isset($contract) && $contract->status==0) selected @endif >{{__('main.inactive')}}</option>
                                        <option value="2" @if(isset($contract) && $contract->status==2) selected @endif >{{__('main.draft')}}</option>
                                        <option value="1" @if(isset($contract) && $contract->status==1) selected @endif >{{__('main.active')}}</option>
                                    </select>
                                </div>

                            </div>



                            <div class="flex justify-end">
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
                if(inn=='') return false;
                if(clicked) return false;
                clicked =true;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/companies/get-company-by-inn',
                    data: {'_token': _csrf_token, 'inn': inn,'user_id':user_id},
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

        })

    </script>
@endsection
