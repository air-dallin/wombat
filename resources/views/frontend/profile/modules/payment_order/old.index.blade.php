@extends('layouts.profile')
@section('title', __('main.payment_orders'))

@section('content')

    @include('alert-profile')


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                        <h3>{{ __('main.payment_orders') }}</h3>

                        <div>
                            <a href="{{ localeRoute('frontend.profile.modules.payment_order.draft') }}" class="btn-create {{App\Helpers\MenuHelper::check($controller.'.'.$action,'paymentorder.draft')}}">{{__('main.draft')}}</a>

                        @if( true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                                <a href="{{ localeRoute('frontend.profile.modules.payment_order.create') }}" class="btn-create bg-success">{{__('main.create')}}</a>
                           @endif

                        </div>
                    </div>
                <div class="card-body">
                    <div class="tickets-table table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                            <tr>
                                <th>{{__('main.status')}}</th>
                                <th class="sorting"
                                    data-sort="contract_number">{{__('main.contract_number')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_number') !!}</th>
                                <th class="sorting"
                                    data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</th>
                                <th class="sorting"
                                    data-sort="invoice">{{__('main.invoice')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('invoice_id') !!}</th>
                                <th class="sorting"
                                    data-sort="contragent">{{__('main.contragent')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent') !!}</th>
                              {{--  <th class="sorting"
                                    data-sort="contract_date">{{__('main.contract_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_date') !!}</th>--}}
                                <th class="sorting"
                                    data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($payment_orders)
                                @foreach($payment_orders as $payment_order)
                                    <tr>
                                        <td>{!! \App\Services\DibankService::getStatusLabel($payment_order->status) !!}</td>
                                        <td>@isset($payment_order->contract){{ $payment_order->contract->contract_number . ' - ' . $payment_order->contract->contract_date }}@endisset</td>
                                        <td>{{$payment_order->amount}}</td>
                                        <td>{{$payment_order->invoice}}</td>
                                        <td>{{$payment_order->contragent}}</td>
                                        {{--<td>{{date('Y-m-d H:i',strtotime($payment_order->contract_date))}}</td>--}}
                                        <td>{{date('Y-m-d H:i',strtotime($payment_order->created_at))}}</td>
                                        <td>
                                            @if($payment_order->status==\App\Models\DibankOption::STATUS_DRAFT)
                                            <a href="{{localeRoute('frontend.profile.modules.payment_order.edit',$payment_order)}}" class="btn btn-primary btn-icon"><i class="fa fa-eye"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            {{ $payment_orders->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        /*$(document).ready(function(){

            $('.region').change(function(){
               location.href = '/' + locale + '/profile/claims?region=' + $(this).val()
           })
        });*/
    </script>
@endsection

