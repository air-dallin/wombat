@extends('layout.default')
@section('title', __('main.contracts'))

@section('content')

    @include('alert')


    <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tickets-table table-responsive">
                        <table class="w-full">
                            <tbody>
                            <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                                    data-sort="contract_number">{{__('main.contract_number')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_number') !!}</td>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                                    data-sort="contragent">{{__('main.contragent_inn')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent') !!}</td>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                                    data-sort="contragent_company">{{__('main.contragent_company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent_company') !!}</td>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                                    data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</td>
                                <td>{{__('main.status')}}</td>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                                    data-sort="contract_date">{{__('main.contract_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_date') !!}</td>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                                    data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</td>
                                <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"></td>
                            </tr>
                            @if($contracts)
                                @foreach($contracts as $contract)
                                    <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row">
                                        <td class="px-6 py-5">{{$contract->contract_number}}</td>
                                        <td class="px-6 py-5">{{$contract->contragent}}</td>
                                        <td class="px-6 py-5">{{$contract->contragent_company}}</td>
                                        <td class="px-6 py-5">{{$contract->amount}}</td>
                                        <td class="px-6 py-5">{{$contract->contragent}}</td>
                                        <td class="px-6 py-5">{!! \App\Models\Module::getStatusLabel($contract->status) !!}</td>
                                        <td class="px-6 py-5">{{date('Y-m-d H:i',strtotime($contract->contract_date))}}</td>
                                        <td class="px-6 py-5">{{date('Y-m-d H:i',strtotime($contract->created_at))}}</td>
                                        <td class="px-6 py-5">
                                            <div class="payment-select relative">
                                                <button onclick="dateFilterAction('#cardsOptions{{$contract->id}}')" type="button">
                                                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </button>
                                                <div id="cardsOptions{{$contract->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                                                    <ul style="min-width: 100px; text-align: center">
                                                        <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                                            <a href="{{localeRoute('admin.contract.edit',$contract)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                                                {{ __('main.edit') }}
                                                            </a>
                                                        </li>
                                                        {{--                                                        <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">--}}
                                                        {{--                                                            <form action="{{localeRoute('admin.contract.destroy',$contract)}}}" method="POST">--}}
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
                        <div class="mt-4">
                            {{ $contracts->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
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

