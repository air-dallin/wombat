<div class="card-body table-responsive">
  <table class="w-full">
    <tbody>
    <tr class="border-b border-bgray-300 dark:border-darkblack-400">
      {{--              <td class="">--}}
      {{--                <label class="text-center">--}}
      {{--                  <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 bg-transparent text-success-300 focus:outline-none focus:ring-0">--}}
      {{--                </label>--}}
      {{--              </td>--}}
      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="contract_number">{{__('main.document')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_number') !!}</td>
      {{--              <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "--}}
      {{--                  data-sort="doctype">{{__('main.document_type')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('doctype') !!}</td>--}}

        @if($owner=='outgoing') {{-- swap --}}
          <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
              data-sort="company_name">{{__('main.company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('company_name') !!}</td>
          <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
              data-sort="contragent_company">{{__('main.contragent_company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent_company') !!}</td>
        @else
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
                data-sort="contragent_company">{{__('main.company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent_company') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
                data-sort="company_name">{{__('main.contragent_company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('company_name') !!}</td>
        @endif

      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="quantity">{{__('main.quantity')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('quantity') !!}</td>
      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</td>
      {{--                <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "--}}
      {{--                    data-sort="doc_status">{{__('main.status')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('doc_status') !!}</td>--}}
      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="date">{{__('main.date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('date') !!}</td>
      {{--              <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "--}}
      {{--                  data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</td>--}}
      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{ __('main.actions') }}</td>
    </tr>
    @isset($documentsQueues)
        @foreach($documentsQueues as $document)
            <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row" style="color:#d5d5d5;">

                <td class="px-6 py-5 ">
                    <div class="flex items-center">
                        <div>
                            {!! \App\Services\DidoxService::getStatusLabel(100)  !!}
                        </div>
                        <div class="flex" style="flex-direction: column">
                            <span>{{ $document['number'] . ' - ' . $document['date'] }}</span>
                            <small class="text-bgray-100" style="font-size: 14px; margin-top: 5px">{{__('main.'.$document['doctype'])}}</small>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-5 ">{{$document['company_name']}}</td>
                <td class="px-6 py-5 ">
                    <div class="flex" style="flex-direction: column">
                        <span>{{$document['contragent_company']}} </span>
                        <small class="text-bgray-100" style="font-size: 14px; margin-top: 5px"> {{$document['contragent']}} </small>
                    </div>
                </td>
                <td class="px-6 py-5 ">{{$document['quantity']}}</td>
                <td class="px-6 py-5 ">{{$document['amount']}}</td>
                {{--                    <td class="px-6 py-5 ">{!! \App\Services\DidoxService::getStatusLabel($document->doc_status)  !!}</td>--}}
                <td class="px-6 py-5 ">{{date('Y-m-d',strtotime($document['date']))}}</td>
                {{--                  <td class="px-6 py-5 ">{{date('Y-m-d H:i',strtotime($document->created_at))}}</td>--}}
                <td class="px-6 py-5 ">
                </td>
            </tr>
        @endforeach
    @endif


    @if($documents)
      @foreach($documents as $document)
        <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row" data-href="{{localeRoute('frontend.profile.modules.'.$document->doctype.'.view',$document->id)}}">
          {{--                  <td class="">--}}
          {{--                    <label class="text-center">--}}
          {{--                      <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 text-success-300 focus:outline-none focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-600">--}}
          {{--                    </label>--}}
          {{--                  </td>--}}
          <td class="px-6 py-5 ">
            <div class="flex items-center">
              <div>
                {!! \App\Services\DidoxService::getStatusLabel($document->doc_status)  !!}
              </div>
              <div class="flex" style="flex-direction: column">
                <span>{{$document->contract_number . ' - ' . $document->contract_date }}</span>
                <small class="text-bgray-600" style="font-size: 14px; margin-top: 5px">{{__('main.'.$document->doctype)}}</small>
              </div>
            </div>
          </td>
          {{--                  <td class="px-6 py-5 ">--}}
          {{--                    <div class="flex items-center">--}}
          {{--                      {!! \App\Services\DidoxService::getStatusLabel($document->doc_status)  !!} {{__('main.'.$document->doctype)}}--}}
          {{--                    </div>--}}
          {{--                  </td>--}}
            @if( \App\Helpers\DocumentHelper::isMyCompany($document,$myCompaniesInn))
                <td class="px-6 py-5 ">{{$document->company_name}}</td>
                <td class="px-6 py-5 ">
                    <div class="flex" style="flex-direction: column">
                        <span>{{$document->contragent_company}}</span>
                        <small class="text-bgray-600" style="font-size: 14px; margin-top: 5px"> {{$document->contragent}} </small>
                    </div>
                </td>
            @else {{-- change --}}
                <td class="px-6 py-5 ">{{$document->contragent_company}}</td>
                <td class="px-6 py-5 ">
                    <div class="flex" style="flex-direction: column">
                        <span>{{$document->company_name}}</span>
                        <small class="text-bgray-600" style="font-size: 14px; margin-top: 5px"> {{$document->company_inn}}</small>
                    </div>
                </td>
            @endif

          <td class="px-6 py-5 ">{{$document->quantity}}</td>
          <td class="px-6 py-5 ">{{number_format($document->amount,2,'.',' ')}}</td>
          {{--                    <td class="px-6 py-5 ">{!! \App\Services\DidoxService::getStatusLabel($document->doc_status)  !!}</td>--}}
          <td class="px-6 py-5 ">{{date('Y-m-d',strtotime($document->date))}}</td>
          {{--                  <td class="px-6 py-5 ">{{date('Y-m-d H:i',strtotime($document->created_at))}}</td>--}}
          <td class="px-6 py-5 ">
            <div class="payment-select relative">
              <button onclick="dateFilterAction('#cardsOptions_{{$document->doctype.'_'.$document->id}}')" type="button">
                <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
              <div id="cardsOptions_{{$document->doctype.'_'.$document->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">


                  <ul style="min-width: 100px; text-align: center">
                  <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                    <a href="{{localeRoute('frontend.profile.modules.'.$document->doctype.'.check-status',$document->id)}}" class="btn btn-primary btn-icon" title="{{__('main.update_from_didox')}}">{{--<i class="fa fa-download"></i>--}} {{__('main.update_from_didox')}}</a>
                  </li>
                  @if($owner=='outgoing' && $document->doc_status==\App\Services\DidoxService::STATUS_CREATED)
                    <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                      <a href="{{localeRoute('frontend.profile.modules.'.$document->doctype.'.edit',$document->id)}}" class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        {{ __('main.edit') }}
                      </a>
                    </li>
                  @endif
                  <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                    <a href="{{localeRoute('frontend.profile.modules.'.$document->doctype.'.view',$document->id)}}" class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                      {{ __('main.view') }}
                    </a>
                  </li>

                  @if(\App\Services\DidoxService::canDestroy($document))
                    <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                      <form method="post" action="{{ localeRoute('frontend.profile.modules.'.$document->doctype.'.destroy',$document->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">{{ __('main.delete') }}</button>
                      </form>
                    </li>
                  @endif
                </ul>


              </div>
            </div>
          </td>
        </tr>
      @endforeach
    @endif
    </tbody>
  </table>
</div>
{{ $documents->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}

