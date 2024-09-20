<div class="card-body table-responsive">

  <table class="w-full">
    <tbody>
    <tr class="border-b border-bgray-300 dark:border-darkblack-400">
      <td class="">
        <label class="text-center">
          <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 bg-transparent text-success-300 focus:outline-none focus:ring-0">
        </label>
      </td>
      {{--              <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "--}}
      {{--                  data-sort="contract_number">{{__('main.contract_number')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_number') !!}</td>--}}

      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="amount">{{__('main.summa_main')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('summa_main') !!}</td>
      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="dir">{{__('main.dir')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('dir') !!}</td>
      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="acc_ct">{{__('main.invoice')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('acc_ct') !!}</td>
      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="name_ct">{{__('main.contragent_company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('name_ct') !!}</td>
      {{--<td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
        data-sort="contragent">{{__('main.contragent')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent') !!}</td>--}}
      <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="vdate">{{__('main.date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('vdate') !!}</td>
      {{--<td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 "
          data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</td>--}}
      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.status')}}</td>
      <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{ __('main.actions') }}</td>
    </tr>
    @if($payment_orders)
      @foreach($payment_orders as $payment_order)
        <tr class="border-b border-bgray-300 dark:border-darkblack-400">
          <td class="">
            <label class="text-center">
              <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 text-success-300 focus:outline-none focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-600">
            </label>
          </td>
          {{--                  <td class="px-6 py-5 ">@isset($payment_order->contract)--}}
          {{--                      {{ $payment_order->contract->contract_number . ' - ' . $payment_order->contract->contract_date }}--}}
          {{--                    @endisset</td>--}}
          <td class="px-6 py-5 ">{{number_format($payment_order->amount/100,2,'.',' ')}}</td>

          <td class="px-6 py-5 ">{!! \App\Services\KapitalService::getTypeLabel($payment_order->dir)!!}</td>
          <td class="px-6 py-5 ">{{$payment_order->acc_ct}}</td>
          <td class="px-6 py-5 ">
            <div class="flex" style="flex-direction: column">
              <span>{{$payment_order->name_dt}}</span>
              <small class="text-bgray-600" style="font-size: 14px; margin-top: 5px"> {{$payment_order->inn_dt}} </small>
            </div>
          </td>
          <td class="px-6 py-5 ">{{$payment_order->vdate}}</td>
          {{--
                            <td class="px-6 py-5 ">{{date('Y-m-d H:i',strtotime($payment_order->created_at))}}</td>
          --}}
          <td class="px-6 py-5 ">{!! \App\Services\KapitalService::getStatusLabel($payment_order->status) !!}</td>
          <td class="px-6 py-5 ">
            <div class="payment-select relative">
              <button onclick="dateFilterAction('#cardsOptions{{$payment_order->id}}')" type="button">
                <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
              <div id="cardsOptions{{$payment_order->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-0 z-10 top-full hidden overflow-hidden" style="display: none;">
                <ul style="min-width: 150px; text-align: center">
                  {{--<li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                    <a href="{{localeRoute('frontend.profile.modules.payment_order.print', $payment_order)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1" target="_blank">
                      {{ __('main.download_cash') }}
                    </a>
                  </li>--}}
                  <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                    <a href="{{localeRoute('frontend.profile.modules.payment_order.download_order', $payment_order)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1" target="_blank">
                      {{ __('main.download_cash') }}
                    </a>
                  </li>
                  {{--<li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                    <a href="{{localeRoute('frontend.profile.modules.payment_order.edit',$payment_order)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                      {{ __('main.edit') }}
                    </a>
                  </li>--}}
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

{{ $payment_orders->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
