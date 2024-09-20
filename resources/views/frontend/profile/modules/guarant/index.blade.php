@extends('layouts.profile')
@section('title', __('main.guarants'))

@section('content')

  @include('alert-profile')

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header justify-content-between">
          <h3>{{ __('main.module_guarants') }}</h3>
          @if(in_array(\Illuminate\Support\Facades\Auth::user()->role,[\App\Models\User::ROLE_DIRECTOR]))
            <select name="region_id" class="form-select region select2-regions" style="min-width: 30%;">
              <option value="0">{{__('main.select_all')}}</option>
              @foreach( $regions as $region )
                <option value="{{ $region->id }}" @if($region_id==$region->id) selected @endif>{{ $region->getTitle() }}</option>
              @endforeach
            </select>
          @endif
          <div>

            <a href="{{ localeRoute('frontend.profile.modules.guarant.index',['owner'=> $owner /*'incoming'*/,'update'=>'true']) }}" class="btn-create" title="{{__('main.update_from_didox')}}"><i class="fa fa-download"></i></a>

            <a href="{{ localeRoute('frontend.profile.modules.guarant.index',['owner'=>'outgoing']) }}" class="btn-create @if($owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING) active @endif">{{__('main.outgoing')}}</a>
            <a href="{{ localeRoute('frontend.profile.modules.guarant.index',['owner'=>'incoming']) }}" class="btn-create  @if($owner==\App\Services\DidoxService::OWNER_TYPE_INCOMING) active @endif">{{__('main.incoming')}}</a>
            <a href="{{ localeRoute('frontend.profile.modules.guarant.draft') }}" class="btn-create {{App\Helpers\MenuHelper::check($controller.'.'.$action,'guarant.draft')}}">{{__('main.draft')}}</a>

            @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
              <a href="{{ localeRoute('frontend.profile.modules.guarant.create') }}" class="btn-create">{{__('main.create')}}</a>
            @endif

          </div>
        </div>
        <div class="card-body">
          <div class="tickets-table table-responsive">
            <table class="table table-responsive-md">
              <thead>
              <tr>
                <th class="sorting"
                    data-sort="guarant_number">{{__('main.guarant_number')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('guarant_number') !!}</th>
                <th class="sorting"
                    data-sort="contract_id">{{__('main.contract')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_id') !!}</th>
                {{--<th class="sorting"
                    data-sort="contract_date">{{__('main.contract_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_date') !!}</th>--}}
                <th class="sorting"
                    data-sort="partner_inn">{{__('main.partner_inn')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('partner_inn') !!}</th>
                <th class="sorting"
                    data-sort="partner_company_name">{{__('main.partner_company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('partner_company_name') !!}</th>
                {{--<th class="sorting"
                    data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</th>--}}

                <th>{{__('main.status')}}</th>

                <th class="sorting"
                    data-sort="guarant_date">{{__('main.guarant_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('guarant_date') !!}</th>
                <th class="sorting"
                    data-sort="guarant_date_expire">{{__('main.guarant_date_expire')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('guarant_date_expire') !!}</th>
                <th class="sorting"
                    data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              @if($guarants)
                @foreach($guarants as $guarant)
                  <tr>

                    <td>{{$guarant->guarant_number}}</td>
                    <td>@isset($guarant->contract)
                        {{$guarant->contract->contract_number . ' - ' . $guarant->contract->contract_date }}
                      @endisset</td>
                    <td>{{$guarant->partner_inn}}</td>
                    <td>{{$guarant->partner_company_name}}</td>
                    {{--
                                                            <td>{{$guarant->amount}}</td>
                    --}}
                    <td>{!! \App\Services\DidoxService::getStatusLabel($guarant->status) !!}</td>
                    <td>{{date('Y-m-d H:i',strtotime($guarant->guarant_date))}}</td>
                    <td>{{date('Y-m-d H:i',strtotime($guarant->guarant_date_expire))}}</td>
                    <td>{{date('Y-m-d H:i',strtotime($guarant->created_at))}}</td>
                    <td>
                      <a href="{{localeRoute('frontend.profile.modules.guarant.check-status',$guarant)}}" class="btn btn-primary btn-icon" title="{{__('main.update_status')}}"><i class="fa fa-download"></i></a>

                      @if($guarant->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING && $guarant->doc_status==\App\Services\DidoxService::STATUS_CREATED)
                        <a href="{{localeRoute('frontend.profile.modules.guarant.edit',$guarant)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                      @endif

                      <a href="{{localeRoute('frontend.profile.modules.guarant.view',$guarant)}}" class="btn btn-primary btn-icon"><i class="fa fa-eye"></i></a>
                      @if($guarant->canDestroy())
                        <form method="post" action="{{ localeRoute('frontend.profile.modules.guarant.destroy',$guarant) }}">
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
          </div>
          {{ $guarants->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js')
  <script>
      $(document).ready(function () {
          $('.region').change(function () {
              location.href = '/' + locale + '/profile/claims?region=' + $(this).val()
          })
      });
  </script>
@endsection

