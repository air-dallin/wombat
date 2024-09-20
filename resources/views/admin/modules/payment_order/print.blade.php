{{-- Extends layout --}}
@extends('layout.default')


{{-- Content --}}
@section('content')

    @include('alert')
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
                            <h4>{{__('main.payment_order')}}</h4>
                            <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                        </div>
                    </div>



                    <div class="card-body">
                        <form method="POST"
                            @isset($payment_order)
                              action="{{localeRoute('admin.modules.payment_order.update',$payment_order)}}"
                              @else
                              action="{{localeRoute('admin.modules.payment_order.store')}}"
                            @endisset
                        >
                        @csrf

                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.contract_number')}}</label>
                                <input type="text" name="contract_number" class="form-control @error('contract_number') is-invalid @enderror @error('contract_number') is-invalid @enderror" placeholder="" value="{{old('contract_number',isset($payment_order) ? $payment_order->contract_number :'')}}" required>
                                @error('contract_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.contract_date')}}</label>
                                <input type="date" name="contract_date" class="form-control @error('contract_date') is-invalid @enderror" placeholder="" value="{{old('contract_date',isset($payment_order) ? $payment_order->contract->contract_date :'')}}" required>
                                @error('contract_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.contragent')}}</label>
                                <input type="text" name="contragent" class="form-control @error('contragent') is-invalid @enderror" placeholder="" value="{{old('contragent',isset($payment_order) ? $payment_order->contragent :'')}}" required>
                                @error('contragent')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.invoices')}}</label>
                                <select class="form-control @error('invoice_id') is-invalid @enderror" name="invoice_id" required>
                                    <option value="">{{__('main.choice_invoice')}}</option>
                                    @foreach($invoices as $invoice)
                                    <option value="{{$invoice->id}}" @if(isset($payment_order) && $payment_order->invoice_id==$invoice->id) selected @endif >{{$invoice->getTitle()}}</option>
                                    @endforeach
                                {{--    @isset($company_invoices)
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

                            <div class="form-group">
                                <label>{{__('main.company')}}</label>
                                <select class="form-control" name="company_id" id="company_id" required>
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

                            <div class="form-group">
                                <label>{{__('main.company_invoice')}}</label>
                                <input type="text" name="company_invoice" id="company_invoice" class="form-control @error('company_invoice') is-invalid @enderror" placeholder="" value="{{old('company_invoice',isset($payment_order) ? $payment_order->company_invoice :'')}}" required>
                                @error('company_invoice')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                           {{-- <div class="form-group">
                                <label>{{__('main.company_invoices')}}</label>
                                <select class="form-control @error('company_invoice_id') is-invalid @enderror" name="company_invoice_id" id="company_invoice_id" required>
                                    <option value="">{{__('main.choice_company_invoice')}}</option>
                                    @isset($payment_order)
                                    @foreach($company_invoices as $invoice)
                                        <option value="{{$invoice->id}}" @if(isset($payment_order) && $payment_order->company_invoice_id==$invoice->id) selected @endif >{{$invoice->company->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                @error('company_invoice_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>--}}

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
                                <label>{{__('main.comment')}}</label>
                                <textarea class="form-control" name="comment" rows="6" >{{old('comment',isset($payment_order) ? $payment_order->comment :'')}}</textarea>
                                @error('comment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.status')}}</label>
                                <select class="form-control" name="status" required>
                                    {{--<option value="0" @if(isset($payment_order) && $payment_order->status==0) selected @endif >{{__('main.inactive')}}</option>--}}
                                    <option value="2" @if(isset($payment_order) && $payment_order->status==2) selected @endif >{{__('main.draft')}}</option>
                                    {{--
                                    пока отключить отправку на внешний сервис
                                    <option value="3" @if(isset($payment_order) && $payment_order->status==3) selected @endif >{{__('main.send')}}</option>--}}
                                    <option value="1" @if(isset($payment_order) && $payment_order->status==1) selected @endif >{{__('main.active')}}</option>
                                </select>
                            </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn-create btn btn-success">{{__('main.save')}}</button>
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
            window.print();
            window.close();
        })

    </script>
@endsection
