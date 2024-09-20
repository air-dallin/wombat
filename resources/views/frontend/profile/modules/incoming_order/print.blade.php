{{-- Extends layout --}}
@extends('layouts.print')


{{-- Content --}}
@section('content')


    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between">
                        <h3>Приходной ордер от {{$incomingOrder->order_date}}</h3>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection



