{{-- Exteplan layout --}}
@extends('layout.default')
@section('title',__('main.plan'))
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
          <div class="flex h-[56px] w-full space-x-4">

              <div class="hidden h-full rounded-lg border border-transparent bg-bgray-100 px-[18px] dark:bg-darkblack-500 sm:block sm:w-70 lg:w-88">
                  <div class="flex h-full w-full items-center space-x-[15px]">
                          <span>
                            <svg class="stroke-bgray-900 dark:stroke-white" width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="9.80204" cy="10.6761" r="8.98856" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                              <path d="M16.0537 17.3945L19.5777 20.9094" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                          </span>
                      <label for="listSearch" class="w-full" >
                          <input type="text" id="listSearch" placeholder="{{ __('main.search'). '...'}}" data-field="title_{{app()->getLocale()}},code" autocapitalize="words" class="search-input w-full border-none bg-bgray-100 px-0 text-sm tracking-wide text-bgray-600 placeholder:text-sm placeholder:font-medium placeholder:text-bgray-500 focus:outline-none focus:ring-0 dark:bg-darkblack-500" value="{{\App\Helpers\QueryHelper::getSearchQuery()}}">
                      </label>
                  </div>

              </div>

          </div>
        <div class="card-body">
            @include('admin.plan.table')
        </div>
      </div>
    </div>

  </div>

@endsection
@push('scripts')
  <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
  <script src="{{ asset('js/sorting.js') }}"></script>
  <script>
        var searchUrl = '{{\App\Helpers\QueryHelper::getUrl()}}';
  </script>
@endpush
