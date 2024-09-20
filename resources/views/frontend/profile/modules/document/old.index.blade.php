@extends('layouts.profile')
@section('title', __('main.products'))

@section('content')

    @include('alert-profile')

    <style>
        .bottom-line{
            border-bottom: 1px solid #848484 !important;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                        <h3>@if($owner=='incoming') {{ __('main.product_incoming')}} @else {{__('main.product_outgoing') }} @endif</h3>

                        <div>
                            <a href="{{ localeRoute('frontend.profile.modules.document.index',['owner'=> $owner /*'incoming'*/,'update'=>'true']) }}" class="btn-create" title="{{__('main.update_from_didox')}}"><i class="fa fa-download"></i></a>

                            {{--<a href="{{ localeRoute('frontend.profile.modules.contract.index',['owner'=>$owner]) }}" class="btn-create @if($owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING) active @endif">{{__('main.contracts')}}</a>--}}
                            <a href="{{ localeRoute('frontend.profile.modules.document.draft',['owner'=>$owner]) }}" class="btn-create {{App\Helpers\MenuHelper::check($controller.'.'.$action,'product.draft')}}">{{__('main.draft')}}</a>

                            @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                                <a href="{{ localeRoute('frontend.profile.modules.contract.create') }}" class="btn-create">{{__('main.create_contract')}}</a>
                            @endif
                            @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                                <a href="{{ localeRoute('frontend.profile.modules.guarant.create') }}" class="btn-create">{{__('main.create_guarant')}}</a>
                            @endif


                           {{--
                            <a href="{{ localeRoute('frontend.profile.modules.product.index',['owner'=>'outgoing']) }}" class="btn-create @if($owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING) active @endif">{{__('main.outgoing')}}</a>
                            <a href="{{ localeRoute('frontend.profile.modules.product.index',['owner'=>'incoming']) }}" class="btn-create  @if($owner==\App\Services\DidoxService::OWNER_TYPE_INCOMING) active @endif">{{__('main.incoming')}}</a>
                            --}}

                               {{--<div class="btn-group">
                                   <a href="{{ localeRoute('frontend.profile.modules.product.index',['owner'=>'incoming']) }}" class="btn-create  @if($owner==\App\Services\DidoxService::OWNER_TYPE_INCOMING) active @endif">{{__('main.incoming')}}</a>
                                   @if($company_id)
                                       <button type="button" class="btn-create dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></button>
                                       <div class="dropdown-menu">
                                           <a href="{{ localeRoute('frontend.profile.modules.product.index',['owner'=>$owner/*'incoming'*/,'update'=>'true']) }}" class="dropdown-item">{{__('main.update_from_didox')}}</a>
                                       </div>
                                   @endif
                               </div>--}}

                            @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                                   <a href="{{ localeRoute('frontend.profile.modules.product.create',['owner'=>$owner]) }}" class="btn-create">{{__('main.create_factura')}}</a>
                               @endif

                        </div>
                    </div>
                <div class="card-body">
                    <div class="tickets-table table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                            <tr>
                                <th class="sorting"
                                    data-sort="doctype">{{__('main.document_type')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('doctype') !!}</th>
                                <th class="sorting"
                                    data-sort="contract_number">{{__('main.contract')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_number') !!}</th>
                                {{--<th class="sorting"
                                    data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</th>
                                <th class="sorting"
                                    data-sort="quantity">{{__('main.quantity')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('quantity') !!}</th>--}}
                                <th class="sorting"
                                    data-sort="company_name">{{__('main.company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('company_name') !!}</th>
                                <th class="sorting"
                                    data-sort="contragent">{{__('main.contragent_inn')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent') !!}</th>
                                <th class="sorting"
                                    data-sort="contragent_company">{{__('main.contragent_company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent_company') !!}</th>
                                <th class="sorting"
                                    data-sort="quantity">{{__('main.quantity')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('quantity') !!}</th>
                                <th class="sorting"
                                    data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</th>
                                <th class="sorting"
                                    data-sort="doc_status">{{__('main.status')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('doc_status') !!}</th>
                                <th class="sorting"
                                    data-sort="date">{{__('main.date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('date') !!}</th>
                                <th class="sorting"
                                    data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($documents)

                                @foreach($documents as $document)

                                    <tr>
                                        <td>{{__('main.'.$document->doctype)}}</td>
                                        <td>{{$document->contract_number . ' - ' . $document->contract_date }}</td>
{{--
                                        <td>{  {$document->amount}}</td>
                                        <td>{ {$document->quantity}}</td>
--}}
                                        <td>{{$document->company_name}}</td>
                                        <td>{{$document->contragent}}</td>
                                        <td>{{$document->contragent_company}}</td>
                                        <td>{{$document->quantity}}</td>
                                        <td>{{$document->amount}}</td>
                                        <td>{!! \App\Services\DidoxService::getStatusLabel($document->doc_status)  !!}</td>
                                        <td>{{date('Y-m-d H:i',strtotime($document->date))}}</td>
                                        <td>{{date('Y-m-d H:i',strtotime($document->created_at))}}</td>
                                        <td>
                                            <a href="{{localeRoute('frontend.profile.modules.'.$document->doctype.'.check-status',$document->id)}}" class="btn btn-primary btn-icon" title="{{__('main.update_from_didox')}}"><i class="fa fa-download"></i></a>

                                            @if($owner=='outgoing' && $document->doc_status==\App\Services\DidoxService::STATUS_CREATED)
                                                <a href="{{localeRoute('frontend.profile.modules.'.$document->doctype.'.edit',$document->id)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                                            @endif
                                            <a href="{{localeRoute('frontend.profile.modules.'.$document->doctype.'.view',$document->id)}}" class="btn btn-primary btn-icon"><i class="fa fa-eye"></i></a>
                                            @if(\App\Services\DidoxService::canDestroy($document))
                                                <form method="post" action="{{ localeRoute('frontend.profile.modules.'.$document->doctype.'.destroy',$document->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                                </form>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            {{ $documents->onEachSide(3)->withQueryString()->links'frontend.profile.sections.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



