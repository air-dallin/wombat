@extends('layouts.profile')
@section('title', __('main.contracts'))
@section('content')
  @include('alert-profile')
  <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
    <div class="col-12">
      <div class="card">
        <div class="card-header justify-content-between">
          <div class="flex justify-between items-center border-b border-bgray-200 dark:border-darkblack-400 pb-5">
            <div class="flex h-[56px] w-full space-x-4">
              <div class="hidden h-full rounded-lg border border-transparent bg-bgray-100 px-[18px] dark:bg-darkblack-500 sm:block sm:w-70 lg:w-88">
                <div class="flex h-full w-full items-center space-x-[15px]">
                          <span>
                            <svg class="stroke-bgray-900 dark:stroke-white" width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="9.80204" cy="10.6761" r="8.98856" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                              <path d="M16.0537 17.3945L19.5777 20.9094" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                          </span>
                  <label for="listSearch" class="w-full">
                    <input type="text" id="listSearch" placeholder="{{ __('main.search_placeholder') }}" class="search-input w-full border-none bg-bgray-100 px-0 text-sm tracking-wide text-bgray-600 placeholder:text-sm placeholder:font-medium placeholder:text-bgray-500 focus:outline-none focus:ring-0 dark:bg-darkblack-500">
                  </label>
                </div>
              </div>
              <div class="relative h-full">
                @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                  <div class="flex h-full  space-x-4">
                    @if($owner!=9)
                      <a href="{{ localeRoute('frontend.profile.modules.contract.index',['owner'=> \App\Services\DidoxService::getOwnerLabel($owner) /*'incoming'*/,'update'=>'true']) }}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500" title="{{__('main.update_from_didox')}}" style="border: 1px solid #718096; color: #718096; min-width: 100px;"><i class="fa fa-download"></i></a>
                    @endisset

                    <a href="{{ localeRoute('frontend.profile.modules.contract.index',['owner'=>'outgoing']) }}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500 @if($owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING) active @endif" style="border: 1px solid #718096; color: #718096; min-width: 200px;">{{__('main.outgoing')}}</a>
                    <a href="{{ localeRoute('frontend.profile.modules.contract.index',['owner'=>'incoming']) }}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500 @if($owner==\App\Services\DidoxService::OWNER_TYPE_INCOMING) active @endif" style="border: 1px solid #718096; color: #718096; min-width: 200px;">{{__('main.incoming')}}</a>
                    <a href="{{ localeRoute('frontend.profile.modules.contract.draft') }}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500  {{App\Helpers\MenuHelper::check($controller.'.'.$action,'contract.draft')}}" style="border: 1px solid #ffa500; color: #ffa500; min-width: 200px;">{{__('main.draft')}}</a>

                    @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                      <a href="{{ localeRoute('frontend.profile.modules.contract.create') }}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-success-300 text-white transition duration-300 ease-in-out hover:bg-success-400" style="min-width: 200px;">{{__('main.create')}}</a>
                    @endif
                  </div>
                @endif
              </div>
            </div>
          </div>
          @if(in_array(\Illuminate\Support\Facades\Auth::user()->role,[\App\Models\User::ROLE_DIRECTOR]))
            <select name="region_id" class="form-select region select2-regions" style="min-width: 30%;">
              <option value="0">{{__('main.select_all')}}</option>
              @foreach( $regions as $region )
                <option value="{{ $region->id }}" @if($region_id==$region->id) selected @endif>{{ $region->getTitle() }}</option>
              @endforeach
            </select>
          @endif

        </div>
        <div class="card-body">
          <div class="tickets-table table-responsive">
            <table class="w-full">
              <tbody>
              <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                    data-sort="contract_number">{{__('main.contract_number')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_number') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                    data-sort="contragent">{{__('main.contragent_inn')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent_inn') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                    data-sort="contragent_company">{{__('main.contragent_company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent_company') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                    data-sort="contragent_bank_code">{{__('main.contragent_invoice')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contragent_bank_code') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                    data-sort="amount">{{__('main.amount')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</td>
                <td>{{__('main.status')}}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                    data-sort="contract_date">{{__('main.contract_date')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('contract_date') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                    data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</td>
                <td class=" text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"
                >{{__('main.actions')}} </td>
              </tr>
              @if($contracts)
                @foreach($contracts as $contract)
                  <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="px-6 py-5">{{$contract->contract_number}}</td>
                    <td class="px-6 py-5">{{$contract->contragent}}</td>
                    <td class="px-6 py-5">{{$contract->contragent_company}}</td>
                    <td class="px-6 py-5">{{$contract->contragent_bank_code}}</td>
                    <td class="px-6 py-5">{{$contract->amount}}</td>
                    <td class="px-6 py-5">{!! \App\Services\DidoxService::getStatusLabel($contract->doc_status) !!}</td>
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
                          <ul style="min-width: 140px; text-align: center">
                            <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                              <a href="{{localeRoute('frontend.profile.modules.contract.check-status',$contract)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                {{__('main.update_from_didox')}}
                              </a>
                            </li>
                            <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                              @if($contract->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING && $contract->doc_status==\App\Services\DidoxService::STATUS_CREATED)
                                <a href="{{localeRoute('frontend.profile.modules.contract.edit',$contract)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                  {{ __('main.edit') }}
                                </a>
                              @endif
                            </li>
                            <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                              <a href="{{localeRoute('frontend.profile.modules.contract.view',$contract)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                {{ __('main.show') }}
                              </a>
                            </li>
                            @if($contract->canDestroy())
                              <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                <form method="POST" action="{{ localeRoute('frontend.profile.modules.contract.destroy',$contract) }}">
                                  @csrf
                                  @method('PUT')
                                  <button type="submit" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                    {{ __('main.delete') }}
                                  </button>
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
          {{ $contracts->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
        </div>
      </div>
    </div>
  </div>

@endsection

