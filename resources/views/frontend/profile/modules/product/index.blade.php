@extends('layouts.profile')
@section('title', __('main.products'))

@section('content')

  @include('alert-profile')

  <style>
    .bottom-line {
      border-bottom: 1px solid #848484 !important;
    }
  </style>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header justify-content-between">
          <h3>@if($owner=='incoming')
              {{ __('main.product_incoming')}}
            @else
              {{__('main.product_outgoing') }}
            @endif</h3>

          <div>
            <a href="{{ localeRoute('frontend.profile.modules.product.index',['owner'=> $owner /*'incoming'*/,'update'=>'true']) }}" class="btn-create" title="{{__('main.update_from_didox')}}"><i class="fa fa-download"></i></a>

            <a href="{{ localeRoute('frontend.profile.modules.contract.index',['owner'=>$owner]) }}" class="btn-create @if($owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING) active @endif">{{__('main.contracts')}}</a>

            {{--    @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                    <a href="{{ localeRoute('frontend.profile.modules.contract.create') }}" class="btn-create">{{__('main.create_contract')}}</a>
                @endif--}}


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
            <a href="{{ localeRoute('frontend.profile.modules.product.draft') }}" class="btn-create {{App\Helpers\MenuHelper::check($controller.'.'.$action,'product.draft')}}">{{__('main.draft')}}</a>

            @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
              <a href="{{ localeRoute('frontend.profile.modules.product.create',['owner'=>$owner]) }}" class="btn-create">{{__('main.create')}}</a>
            @endif

          </div>
        </div>
        <div class="card-body">
          <div class="tickets-table table-responsive">
            <table class="table table-responsive-md">
              <thead>
              <tr>
                <th>{{__('main.document_type')}}</th>
                <th class="sorting"
                    data-sort="contract_id">{{__('main.contract')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_id') !!}</th>
                {{--<th class="sorting"
                    data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</th>
                <th class="sorting"
                    data-sort="quantity">{{__('main.quantity')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('quantity') !!}</th>--}}
                <th class="sorting"
                    data-sort="company">{{__('main.company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('company') !!}</th>
                <th class="sorting"
                    data-sort="contragent">{{__('main.contragent_inn')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent') !!}</th>
                <th class="sorting"
                    data-sort="contragent_company">{{__('main.contragent_company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent_company') !!}</th>
                <th>{{__('main.status')}}</th>

                <th class="sorting"
                    data-sort="date">{{__('main.date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('date') !!}</th>
                <th class="sorting"
                    data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              @if($products)
                @foreach($products as $product)
                  <tr>
                    <td>{{__('main.factura')}}</td>
                    <td>@isset($product->contract)
                        {{$product->contract->contract_number . ' - ' . $product->contract->contract_date }}
                      @endisset</td>
                    {{--
                                                            <td>{{$product->amount}}</td>
                                                            <td>{{$product->quantity}}</td>
                    --}}
                    <td>{{$product->company->name}}</td>
                    <td>{{$product->contragent}}</td>
                    <td>{{$product->contragent_company}}</td>
                    <td>{!! \App\Services\DidoxService::getStatusLabel($product->doc_status)  !!}</td>
                    <td>{{date('Y-m-d H:i',strtotime($product->date))}}</td>
                    <td>{{date('Y-m-d H:i',strtotime($product->created_at))}}</td>
                    <td>
                      <a href="{{localeRoute('frontend.profile.modules.product.check-status',$product)}}" class="btn btn-primary btn-icon" title="{{__('main.update_from_didox')}}"><i class="fa fa-download"></i></a>

                      @if($product->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING && $product->doc_status==\App\Services\DidoxService::STATUS_CREATED)
                        <a href="{{localeRoute('frontend.profile.modules.product.edit',$product)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                      @endif
                      <a href="{{localeRoute('frontend.profile.modules.product.view',$product)}}" class="btn btn-primary btn-icon"><i class="fa fa-eye"></i></a>
                      @if($product->canDestroy())
                        <form method="post" action="{{ localeRoute('frontend.profile.modules.product.destroy',$product) }}">
                          @csrf
                          @method('PUT')
                          <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                        </form>
                      @endif

                    </td>
                  </tr>
                  @isset($product->contract)
                    <tr class="bottom-line">
                      <td>{{__('main.contract')}}</td>
                      <td>@isset($product->contract)
                          {{$product->contract->contract_number . ' - ' . $product->contract->contract_date }}
                        @endisset</td>
                      {{--
                                                              <td>{{$product->amount}}</td>
                                                              <td>{{$product->quantity}}</td>
                      --}}
                      <td>{{$product->company->name}}</td>
                      <td>{{$product->contract->contragent}}</td>
                      <td>{{$product->contract->contragent_company}}</td>
                      <td>{!! \App\Services\DidoxService::getStatusLabel($product->contract->doc_status)  !!}</td>
                      <td>{{date('Y-m-d H:i',strtotime($product->contract->contract_date))}}</td>
                      <td>{{date('Y-m-d H:i',strtotime($product->contract->created_at))}}</td>
                      <td>
                        <a href="{{localeRoute('frontend.profile.modules.product.check-status',$product)}}" class="btn btn-primary btn-icon" title="{{__('main.update_from_didox')}}"><i class="fa fa-download"></i></a>

                        @if($product->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING && $product->doc_status==\App\Services\DidoxService::STATUS_CREATED)
                          <a href="{{localeRoute('frontend.profile.modules.product.edit',$product)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                        @endif
                        <a href="{{localeRoute('frontend.profile.modules.product.view',$product)}}" class="btn btn-primary btn-icon"><i class="fa fa-eye"></i></a>
                        {{--@if($product->canDestroy())
                            <form method="post" action="{{ localeRoute('frontend.profile.modules.product.destroy',$product) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                            </form>
                        @endif--}}

                      </td>
                    </tr>
                  @endisset
                @endforeach
              @endif
              </tbody>
            </table>
          </div>
          {{ $products->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
        </div>
      </div>
    </div>
  </div>

@endsection



