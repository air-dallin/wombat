@extends('layout.default')
@section('title', __('main.guarants'))

@section('content')

  @include('alert')



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
          {{-- <div>
              @if($tarif->checkTarifIsActive())
                   <a href="{{ localeRoute('admin.modules.guarant.create') }}" class="btn-create btn btn-success">{{__('main.create')}}</a>
              @endif
               <a href="{{ localeRoute('admin.modules.guarant.draft') }}" class="btn-create btn btn-success">{{__('main.draft')}}</a>

           </div>--}}
        </div>
        <div class="card-body">
          <div class="tickets-table table-responsive">
            <table class="table table-responsive-md">
              <thead>
              <tr>
                <th>{{__('main.status')}}</th>
                <th class="sorting"
                    data-sort="guarant_number">{{__('main.guarant_number')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('guarant_number') !!}</th>
                <th class="sorting"
                    data-sort="contract_number">{{__('main.contract_number')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_number') !!}</th>
                <th class="sorting"
                    data-sort="contract_date">{{__('main.contract_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_date') !!}</th>
                <th class="sorting"
                    data-sort="contragent">{{__('main.contragent')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent') !!}</th>
                <th class="sorting"
                    data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</th>

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
                    <td>{!! \App\Models\Module::getStatusLabel($guarant->status) !!}</td>
                    <td>{{$guarant->guarant_number}}</td>
                    <td>{{$guarant->contract_number}}</td>
                    <td>{{$guarant->contract_date}}</td>
                    <td>{{$guarant->contragent}}</td>
                    <td>{{$guarant->amount}}</td>
                    <td>{{date('Y-m-d H:i',strtotime($guarant->guarant_date))}}</td>
                    <td>{{date('Y-m-d H:i',strtotime($guarant->guarant_date_expire))}}</td>
                    <td>{{date('Y-m-d H:i',strtotime($guarant->created_at))}}</td>
                    <td>
                      <div class="payment-select relative">
                        <button onclick="dateFilterAction('#cardsOptions{{$guarant->id}}')" type="button">
                          <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          </svg>
                        </button>
                        <div id="cardsOptions{{$guarant->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                          <ul style="min-width: 100px; text-align: center">
                            <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                              <a href="{{localeRoute('admin.guarant.edit',$guarant)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                {{ __('main.edit') }}
                              </a>
                            </li>
                            {{--                                                        <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">--}}
                            {{--                                                            <form action="{{localeRoute('admin.guarant.destroy',$guarant)}}}" method="POST">--}}
                            {{--                                                                @csrf--}}
                            {{--                                                                @method('PUT')--}}
                            {{--                                                                <button class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">--}}
                            {{--                                                                    {{ __('main.delete') }}--}}
                            {{--                                                                </button>--}}
                            {{--                                                            </form>--}}
                            {{--                                                        </li>--}}
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                @endforeach
              @endif
              </tbody>
            </table>
            <div class="d-flex justify-content-end">
              {{ $guarants->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
            </div>
          </div>
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

