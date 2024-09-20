{{-- Extends layout --}}
@extends('layouts.profile')


{{-- Content --}}
@section('content')

    @include('alert-profile')
    <div class="container-fluid">
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($payment_order)?$payment_order->title_ru :'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="w-100 d-flex justify-content-between">
                            <h4>{{__('main.payment_orders')}}</h4>
                            <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                        </div>
                    </div>



                    <div class="card-body">
                        <form method="POST"
                            @isset($payment_order)
                              action="{{localeRoute('frontend.profile.modules.payment_order.update',$payment_order)}}"
                              @else
                              action="{{localeRoute('frontend.profile.modules.payment_order.store')}}"
                            @endisset
                        >
                        @csrf

                            <div class="form-group">
                                <label>{{__('main.contract')}}</label>
                                <select class="form-control @error('contract_id') is-invalid @enderror" name="contract_id" id="contract_id" required>
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

                            <div>
                                <label for="contragent" class="form-label">{{__('main.contragent')}}</label>
                            </div>
                            <div class="form-group input-group mb-3">
                                <input type="text" name="contragent" id="contragent" class="form-control @error('contragent') is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('contragent',isset($payment_order) ? $payment_order->contragent :'')}}" required>
                                @error('contragent')
                                <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror

                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary get_company" type="button"><i class="fa fa-clock-o "></i> {{__('main.find')}}</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contragent_company')}}</label>
                                <input type="text" name="contragent_company" id="contragent_company" class="form-control @error('contragent_company') is-invalid @enderror" value="{{old('contragent_company',isset($payment_order) ? $payment_order->contragent_company :'')}}" required>
                                @error('contragent_company')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contragent_invoice')}}</label>
                                <input type="text" name="contragent_bank_code" id="contragent_bank_code" class="form-control @error('contragent_bank_code') is-invalid @enderror" value="{{old('contragent_bank_code',isset($payment_order) ? $payment_order->contragent_bank_code :'')}}" required>
                                @error('contragent_bank_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.company')}}</label>
                                <select class="form-control" name="company_id" id="company_id" required>
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

                            {{--<div class="form-group">
                                <label>{{__('main.company_invoice')}}</label>
                                <input type="text" name="company_invoice" id="company_invoice" class="form-control @error('company_invoice') is-invalid @enderror" placeholder="" value="{{old('company_invoice',isset($payment_order) ? $payment_order->company_invoice :'')}}" required>
                                @error('company_invoice')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>--}}

                            <div class="form-group">
                                <label>{{__('main.company_invoices')}}</label>
                                <select class="form-control @error('company_invoice_id') is-invalid @enderror" name="company_invoice_id" id="company_invoice_id" required>
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

                            <div class="form-group">
                                <label>{{__('main.invoice')}}</label>
                                <select class="form-control @error('invoice_id') is-invalid @enderror" name="invoice_id" required>
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
                            </div>

                            <div class="form-group">
                                <label>{{__('main.movements')}}</label>
                                <select class="form-control @error('choice_movement') is-invalid @enderror" name="movement_id" required>
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

                            <div class="form-group">
                                <label>{{__('main.purpose')}}</label>
                                <select class="form-control @error('choice_purpose') is-invalid @enderror" name="purpose_id" id="purpose" required>
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


                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.amount')}}</label>
                                <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="" value="{{old('amount',isset($payment_order) ? $payment_order->amount :'')}}" required>
                                @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.serial')}}</label>
                                <input type="text" name="serial" class="form-control @error('serial') is-invalid @enderror" placeholder="" value="{{old('serial',isset($payment_order) ? $payment_order->serial :'')}}" required>
                                @error('serial')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.signature')}}</label>
                                <input type="text" name="signature" class="form-control @error('signature') is-invalid @enderror" placeholder="" value="{{old('signature',isset($payment_order) ? $payment_order->signature :'')}}" required>
                                @error('signature')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label>{{__('main.comment')}}</label>
                                <textarea class="form-control" name="comment" rows="6" >{{old('comment',isset($payment_order) ? $payment_order->comment :'')}}</textarea>
                                @error('comment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            {{--<div class="form-group">
                                <label>{{__('main.status')}}</label>
                                <select class="form-control" name="status" required>
                                    <option value="2" @if(isset($payment_order) && $payment_order->status==2) selected @endif >{{__('main.draft')}}</option>
                                    <option value="1" @if(isset($payment_order) && $payment_order->status==1) selected @endif >{{__('main.active')}}</option>
                                </select>
                            </div>
--}}
                        <div class="d-flex justify-content-end">
                            @isset($payment_order)
                                <a href="{{localeRoute('frontend.profile.modules.payment_order.print',$payment_order)}}" target="_blank" class="btn-create btn btn-info" title="{{__('main.print')}}"><i class="fa fa-print"></i></a>
                            @endif
                            <button type="submit" name="send_state" value="draft" class="btn-create">{{__('main.draft')}}</button>
                            <button type="submit" name="send_state" value="public" class="btn-create">{{__('main.save')}}</button>
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
                if(company_id=='') return false;
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

            $('#company_id').change();

            $('#contract_id').change(function () {
                var contract_id =$('#contract_id option:selected').val();
                if(contract_id=='') return false;
                if(clicked) return false;
                clicked =true;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/modules/contract/get-company-info',
                    data: {'_token': _csrf_token, 'contract_id': contract_id,'user_id':user_id},
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
            $('#purpose').select2();
        });

    </script>
@endsection
