@extends('layouts.profile')
@section('title', __('main.users_create'))
@section('content')

    @section('css')
        <style>
            .tarif-block {
                cursor: pointer;
            }

            .tarif-block.active,
            .tarif-block:hover {
                background-color: #cbe3f3;
            }
        </style>
    @endsection

    @include('alert-profile')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between">
                        <h4>{{__('main.tarifs')}} </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form
                        action="@if(isset($company)) {{localeRoute('frontend.profile.tarifs.update',$company)}} @else {{localeRoute('frontend.profile.tarifs.store')}} @endif"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="container py-3">

                            <main>
                                <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
                                    @foreach($tarifs as $key=>$tarif)
                                    <div class="col">
                                        <div class="tarif-block @if($key==0) active @endif card mb-4 rounded-3 shadow-sm">
                                            <div class="card-header py-3">
                                                <h4 class="my-0 fw-normal">{{$tarif->getTitle()}}</h4>
                                            </div>
                                            <div class="card-body">
                                                <h1 class="card-title pricing-card-title">{!! number_format($tarif->price,0,'.','&nbsp')!!}<small
                                                        class="text-body-secondary fw-light">/{{__('main.month_abbr')}}</small></h1>
                                                <div>{!! $tarif->getText(true)!!}</div>
                                                {{--<button type="button" class="w-100 btn btn-lg btn-outline-primary">Sign up for free</button>--}}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>

                                <h2 class="display-6 text-center mb-4">{{__('main.compare_plans')}}</h2>

                                @isset($tarifs)
                                    <div class="table-responsive">
                                        <table class="table text-center">
                                            <thead>
                                            <tr>
                                                <th style="width: 34%;"></th>

                                                @foreach($tarifs as $tarif )
                                                    <th style="width: 22%;">{{$tarif->getTitle()}}</th>
                                                @endforeach

                                            </tr>
                                            </thead>
                                            <tbody>


                                            @foreach($tarif->modules as $module)
                                                <tr>
                                                    <th scope="row" class="text-start">{{$module->getTitle()}}</th>
                                                    @foreach($tarifs as $tarif)
                                                        @if(in_array($tarif->id,$modules[$module->id]))
                                                            <td><i class="fa fa-check-circle"></i></td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            {{--<tr>
                                                <th scope="row" class="text-start">{{__('main.module_casses_orders')}}</th>
                                                <td></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                            </tr>

                                            <tr>
                                                <th scope="row" class="text-start">{{__('main.module_contracts')}}</th>
                                                <td><i class="fa fa-check-circle"></i></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-start">{{__('main.module_nomenklatures')}}</th>
                                                <td></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-start">{{__('main.module_products')}}</th>
                                                <td></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-start">{{__('main.module_guarants')}}</th>
                                                <td></td>
                                                <td></td>
                                                <td><i class="fa fa-check-circle"></i></td>
                                            </tr>--}}
                                            </tbody>
                                        </table>
                                    </div>
                                @endisset
                            </main>

                        </div>

                        <div class="container py-3">

                            <h2 class="display-6 text-center mb-4">{{__('main.payment_systems')}}</h2>
                            @isset($paymentSystems)
                                <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
                                @foreach($paymentSystems as $paymentSystem)

                                        <div class="col">
                                            <div class="payment-system-block active card mb-4 rounded-3 shadow-sm">
                                                <div class="card-header py-3">
                                                    <h4 class="my-0 fw-normal">{{$paymentSystem->getTitle()}}</h4>
                                                </div>
                                                <div class="card-body">
                                                    <button type="button"
                                                            class="w-100 btn btn-lg btn-outline-primary">{{__('main.pay')}}</button>
                                                </div>
                                            </div>
                                        </div>

                                @endforeach
                                </div>
                            @endisset
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
            $('.tarif-block').click(function () {
                $('.tarif-block').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
@endsection
