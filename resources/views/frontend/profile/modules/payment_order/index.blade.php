@extends('layouts.profile')
@section('title', __('main.payment_orders'))

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
                    <input type="text" id="listSearch" placeholder="{{ __('main.search_placeholder') }}" class="search-input w-full border-none bg-bgray-100 px-0 text-sm tracking-wide text-bgray-600 placeholder:text-sm placeholder:font-medium placeholder:text-bgray-500 focus:outline-none focus:ring-0 dark:bg-darkblack-500" value="{{\App\Helpers\QueryHelper::getSearchQuery()}}">
                  </label>
                </div>
              </div>
              <div class="relative h-full">
                @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                  <div class="flex h-full  space-x-4">
                    <a href="{{ localeRoute('frontend.profile.modules.payment_order.draft') }}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500 {{App\Helpers\MenuHelper::check($controller.'.'.$action,'incomingorder.draft')}}" style="border: 1px solid #ffa500; color: #ffa500; min-width: 200px;">{{__('main.draft')}}</a>
                    <a href="{{ localeRoute('frontend.profile.modules.payment_order.create') }}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-success-300 text-white transition duration-300 ease-in-out hover:bg-success-400" style="min-width: 200px;">{{__('main.create')}}</a>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        @include('frontend.profile.modules.payment_order.table')
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
      //var lang = '{{app()->getLocale()}}';
      //var searchUrl = '/'+lang+'/profile/modules/payment_order';
      var searchUrl = '{{\App\Helpers\QueryHelper::getUrl()}}';

  </script>
@endsection

