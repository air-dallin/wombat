{{-- Exteplan layout --}}
@extends('layouts.profile')
@section('title',__('main.movement_report') .' - ' . $company->name)
{{-- Content --}}
@section('content')

    <?php
    /*<div class="page-titles">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                   <li class="breadcrumb-item active"><a href="javascript:void(0)">OldCategory</a></li>
               </ol>
           </div> */ ?>
    <!-- row -->

    @include('alert')

    <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between">
                        <a class="btn btn-outline-primary" href="{{ $urlBack }}">
                            <button type="submit" class="mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.back')}}</button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="w-full">
                            <tbody>
                            <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                                <?php
                                /* <th class="width50">
                                                         <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                                                           <input type="checkbox" class="custom-control-input" id="checkAll" required="">
                                                           <label class="custom-control-label" for="checkAll"></label>
                                                         </div>
                                                       </th> */ ?>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="date"><strong>{{__('main.date')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('date') !!}</td>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="debit_account"><strong>{{__('main.debit_account')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('debit_account_'.app()->getLocale()) !!}</td>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="credit_account"><strong>{{__('main.credit_account')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('credit_account') !!}</td>
                                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="amount"><strong>{{__('main.summ')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</td>
                                {{--
                                                <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"></td>
                                --}}
                            </tr>
                            @if($accounts)
                                @foreach($accounts as $account)
                                    <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row">
                                        <?php
                                        /* <td>
                                                                 <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                                                                   <input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
                                                                   <label class="custom-control-label" for="customCheckBox2"></label>
                                                                 </div>
                                                               </td> */ ?>
                                        <td class="px-6 py-5">{{ $account->date }}  </td>
                                        <td class="px-6 py-5">{{ $account->debit_account }}  </td>
                                        <td class="px-6 py-5">{{ $account->credit_account }}  </td>
                                        <td class="px-6 py-5">{{ number_format($account->amount,2,'.',' ') }}  </td>
                                        {{-- <td class="px-6 py-5">
                                           <div class="payment-select relative">
                                             <button onclick="dateFilterAction('#cardsOptions{{$account->id}}')" type="button">
                                               <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                 <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                               </svg>
                                             </button>
                                             <div id="cardsOptions{{$account->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                                               <ul style="min-width: 100px; text-align: center">
                                                 <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                                   <a href="{{localeRoute('admin.plan.edit',$account)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                                     {{ __('main.edit') }}
                                                   </a>
                                                 </li>
                                                 <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                                   <form action="{{localeRoute('admin.plan.destroy',$account)}}}" method="POST">
                                                     @csrf
                                                     @method('PUT')
                                                     <button class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                                       {{ __('main.delete') }}
                                                     </button>
                                                   </form>
                                                 </li>
                                               </ul>
                                             </div>
                                           </div>
                                         </td>--}}
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                    {{ $accounts->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}

                </div>
            </div>
        </div>

    </div>

@endsection
@push('scripts')
    <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sorting.js') }}"></script>
@endpush
