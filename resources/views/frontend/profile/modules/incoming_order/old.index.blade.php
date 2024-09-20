@extends('layouts.profile')
@section('title', __('main.incoming_orders'))

@section('content')

    @include('alert-profile')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                        <h3>{{ __('main.incoming_orders') }}</h3>
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()->role,[\App\Models\User::ROLE_DIRECTOR]))
                            <select name="region_id" class="form-select region select2-regions" style="min-width: 30%;">
                                <option value="0">{{__('main.select_all')}}</option>
                                @foreach( $regions as $region )
                                    <option value="{{ $region->id }}" @if($region_id==$region->id) selected @endif>{{ $region->getTitle() }}</option>
                                @endforeach
                            </select>
                        @endif
                        <div>

                            <a href="{{ localeRoute("frontend.profile.modules.expense_order.index") }}" class="btn-create">{{__('main.expense_orders')}}</a>

                            <a href="{{ localeRoute('frontend.profile.modules.incoming_order.draft') }}" class="btn-create {{App\Helpers\MenuHelper::check($controller.'.'.$action,'incomingorder.draft')}}">{{__('main.draft')}}</a>

                        @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                                <a href="{{ localeRoute('frontend.profile.modules.incoming_order.create') }}" class="btn-create bg-success">{{__('main.create')}}</a>
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
                                    data-sort="casse_id">{{__('main.casse')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('casse_id') !!}</th>
                                <th class="sorting"
                                    data-sort="movement_id">{{__('main.movement')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('movement_id') !!}</th>
                                <th class="sorting"
                                    data-sort="contragent">{{__('main.contragent')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent') !!}</th>
                                <th class="sorting"
                                    data-sort="contract_date">{{__('main.contract_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_date') !!}</th>
                            <th class="sorting"
                                    data-sort="order_date">{{__('main.order_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('order_date') !!}</th>
                                <th class="sorting"
                                    data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($incoming_orders)
                                @foreach($incoming_orders as $incoming_order)
                                    <tr>
                                        <td>{!! \App\Models\Module::getStatusLabel($incoming_order->status) !!}</td>
                                        <td>@isset($incoming_order->contract){{$incoming_order->contract->contract_number . ' - ' . $incoming_order->contract->contract_date }}@endisset</td>
                                        <td>{{$incoming_order->amount}}</td>
                                        <td>{{$incoming_order->casse->getTitle()}}</td>
                                        <td>{{$incoming_order->movement->getTitle()}}</td>
                                        <td>{{$incoming_order->contragent}}</td>
                                        <td>{{date('Y-m-d H:i',strtotime($incoming_order->contract_date))}}</td>
                                        <td>{{date('Y-m-d H:i',strtotime($incoming_order->order_date))}}</td>
                                        <td>{{date('Y-m-d H:i',strtotime($incoming_order->created_at))}}</td>
                                        <td><a href="{{localeRoute('frontend.profile.modules.incoming_order.edit',$incoming_order)}}" class="btn btn-primary btn-icon"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            {{ $incoming_orders->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function(){
           $('.region').change(function(){
               location.href = '/' + locale + '/profile/claims?region=' + $(this).val()
           })
        });
    </script>
@endsection

