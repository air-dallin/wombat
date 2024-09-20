<div class="card-body table-responsive">
    <table class="w-full">
        <tbody>
        <tr class="border-b border-bgray-300 dark:border-darkblack-400">
            <td class="">
                <label class="text-center">
                    <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 bg-transparent text-success-300 focus:outline-none focus:ring-0">
                </label>
            </td>
            {{--              <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.status')}}</td>--}}
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                data-sort="contract_id">{{__('main.contract_number')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_number') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                data-sort="casse_id">{{__('main.casse')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('casse_id') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                data-sort="movement_id">{{__('main.movement')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('movement_id') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                data-sort="contragent">{{__('main.contragent')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                data-sort="order_date">{{__('main.order_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('order_date') !!}</td>
            {{--              <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"--}}
            {{--                  data-sort="order_date">{{__('main.order_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('order_date') !!}</td>--}}
            {{--              <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"--}}
            {{--                  data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</td>--}}
            <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{ __('main.actions') }}</td>
        </tr>


        @if($incoming_orders)
            @foreach($incoming_orders as $incoming_order)
                <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="">
                        <label class="text-center">
                            <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 text-success-300 focus:outline-none focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-600">
                        </label>
                    </td>
                    {{--                  <td class="px-6 py-5">{!! \App\Models\Module::getStatusLabel($incoming_order->status) !!}</td>--}}
                    <td class="px-6 py-5">
                        @isset($incoming_order->contract)
                            {{$incoming_order->contract->contract_number . ' - ' . $incoming_order->contract->contract_date }}
                        @endisset
                    </td>
                    <td class="px-6 py-5">{{$incoming_order->amount}}</td>
                    <td class="px-6 py-5">{{!empty($incoming_order->casse)?$incoming_order->casse->getTitle():''}}</td>
                    <td class="px-6 py-5">{{!empty($incoming_order->movement)?$incoming_order->movement->getTitle():''}}</td>
                    <td class="px-6 py-5">{{$incoming_order->contragent}}</td>
                    <td class="px-6 py-5">{{date('Y-m-d H:i',strtotime($incoming_order->order_date))}}</td>
                    {{--                  <td class="px-6 py-5">{{date('Y-m-d H:i',strtotime($incoming_order->order_date))}}</td>--}}
                    {{--                  <td class="px-6 py-5">{{date('Y-m-d H:i',strtotime($incoming_order->created_at))}}</td>--}}
                    <td class="px-6 py-5">
                        <div class="payment-select relative">
                            <button onclick="dateFilterAction('#cardsOptions{{$incoming_order->id}}')" type="button">
                                <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                            <div id="cardsOptions{{$incoming_order->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                                <ul style="min-width: 100px; text-align: center">
                                    <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                        <a href="{{localeRoute('frontend.profile.modules.incoming_order.edit',$incoming_order)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                            {{ __('main.edit') }}
                                        </a>
                                    </li>
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
@isset($incoming_orders)
    {{ $incoming_orders->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
@endisset
