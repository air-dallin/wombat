{{-- Extends layout --}}
@extends('layouts.profile')

{{-- Content --}}
@section('content')

    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between">
                        <h3>Приходной ордер ордер: {{}} от {{$incomingOrder->order_date}}</h3>

                    </div>
                    <div class="w-100">

                        <a href="{{localeRoute('frontend.profile.modules.expense_order.exportPdf',$incomingOrder->id)}}" type="button" class="btn btn-link" target="_blank">Скачать PDF</a>
                        <a href="{{localeRoute('frontend.profile.modules.expense_order.print',$incomingOrder->id)}}" type="button" class="btn btn-link" target="_blank">Печать</a>

                    </div>
                </div>

                <div class="card-body">



                </div>

            </div>
        </div>
@endsection



