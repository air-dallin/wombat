{{-- Extends layout --}}
@extends('layouts.profile')


{{-- Content --}}
@section('content')

    @include('alert-profile')
    <div class="container-fluid">
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($incoming_order)?$incoming_order->title_ru :'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="w-100 d-flex justify-content-between">
                            <h4>{{__('main.incoming_orders')}}</h4>
                            <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                        </div>
                    </div>



                    <div class="card-body">
                        <form method="POST"
                            @isset($incoming_order)
                              action="{{localeRoute('frontend.profile.modules.incoming_order.update',$incoming_order)}}"
                              @else
                              action="{{localeRoute('frontend.profile.modules.incoming_order.store')}}"
                            @endisset
                        >
                        @csrf
                            <div class="form-group">
                                <label>{{__('main.contract')}}</label>
                                <select class="form-control @error('contract_id') is-invalid @enderror" name="contract_id" id="contract_id" required>
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

                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.order_date')}}</label>
                                <input type="date" name="order_date" class="form-control @error('order_date') is-invalid @enderror" placeholder="" value="{{old('order_date',isset($incoming_order) ? $incoming_order->order_date :date('Y-m-d'))}}" required>
                                @error('order_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div>
                                <label for="contragent" class="form-label">{{__('main.contragent')}}</label>
                            </div>
                            <div class="form-group input-group mb-3">
                                <input type="text" name="contragent" id="contragent" class="form-control @error('contragent') is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('contragent',isset($incoming_order) ? $incoming_order->contragent :'')}}" required>
                                @error('contragent')
                                <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror

                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary get_company" type="button"><i class="fa fa-clock-o "></i> {{__('main.find')}}</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contragent_company')}}</label>
                                <input type="text" name="contragent_company" id="contragent_company" class="form-control @error('contragent_company') is-invalid @enderror" value="{{old('contragent_company',isset($incoming_order) ? $incoming_order->contragent_company :'')}}" required>
                                @error('contragent_company')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contragent_invoice')}}</label>
                                <input type="text" name="contragent_bank_code" id="contragent_bank_code" class="form-control @error('contragent_bank_code') is-invalid @enderror" value="{{old('contragent_bank_code',isset($incoming_order) ? $incoming_order->contragent_bank_code :'')}}" required>
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
                                        <option value="{{$company->id}}" @if( (isset($incoming_order) && $incoming_order->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.casse')}}</label>
                                <select class="form-control @error('casse_id') is-invalid @enderror" name="casse_id" id="casse_id" required>
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

                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.amount')}}</label>
                                <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="" value="{{old('amount',isset($incoming_order) ? $incoming_order->amount :'')}}" required>
                                @error('amount')
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
                                        <option value="{{$movement->id}}" @if(isset($incoming_order) && $incoming_order->movement_id==$movement->id) selected @endif >{{$movement->getTitle()}}</option>
                                    @endforeach
                                </select>
                                @error('movement_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.comment')}}</label>
                                <textarea class="form-control" name="comment" rows="6" >{{old('comment',isset($incoming_order) ? $incoming_order->comment :'')}}</textarea>
                                @error('comment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.status')}}</label>
                                <select class="form-control" name="status">
                                    <option value="0" @if(isset($incoming_order) && $incoming_order->status==0) selected @endif >{{__('main.inactive')}}</option>
                                    <option value="2" @if(isset($incoming_order) && $incoming_order->status==2) selected @endif >{{__('main.draft')}}</option>
                                    <option value="1" @if(isset($incoming_order) && $incoming_order->status==1) selected @endif >{{__('main.active')}}</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                @isset($incoming_order)
                                    <a href="{{localeRoute('frontend.profile.modules.incoming_order.print',$incoming_order)}}" target="_blank" class="btn-create btn btn-info" title="{{__('main.print')}}"><i class="fa fa-print"></i></a>
                                    <a href="{{localeRoute('frontend.profile.modules.incoming_order.exportPdf',$incoming_order)}}" target="_blank" class="btn-create btn btn-info" title="{{__('main.pdf')}}"><i class="fa fa-file-pdf"></i></a>

                                @endif
                                <button type="submit" class="btn-create">{{__('main.save')}}</button>
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



            $('#company_id').change(function () {
                let company_id = $(this).val();
                if(company_id=='') return false;
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
                var contract_id =$('#contract_id option:selected').val();
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

        })

    </script>
@endsection
