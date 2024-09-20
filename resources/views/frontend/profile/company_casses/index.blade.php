@extends('layouts.profile')
@section('title', $company->name)
@section('content')
  @include('alert-profile')

  <div class="grid grid-cols-1 rounded-xl bg-white dark:bg-darkblack-600 xl:grid-cols-12">

      <!-- Sidebar Tabs -->

  @include('frontend.profile.sections.company_menu',['menu'=>'company_casse'])


    <!--Tab Content -->
    <div class="tab-content col-span-9 px-10 py-8">
      <!-- Personal Information -->
      <!-- Notification -->
      <div id="tab2" class="tab-pane active">
        <div class="border-b border-bgray-200 pb-5 dark:border-darkblack-400 flex justify-between items-center">
          <h3 class=" text-2xl font-bold text-bgray-900 dark:text-white">{{ __('main.company_casses') }}</h3>
          <a href="{{ localeRoute('frontend.profile.company_casse.create') }}" class="rounded-lg bg-success-300 px-4 py-3.5 font-semibold text-white">{{__('main.create')}}</a>
        </div>
        <table class="w-full">
          <tbody>
          <tr class="border-b border-bgray-300 dark:border-darkblack-400">
            <td class="">
              <label class="text-center">
                <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 bg-transparent text-success-300 focus:outline-none focus:ring-0">
              </label>
            </td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 xl:px-0" data-sort="status">{{__('main.status')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('status') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50" data-sort="name">{{__('main.company_name')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('name') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50" data-sort="title">{{__('main.title')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('title') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50" data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</td>
            <td class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{ __('main.actions') }}</td>

          </tr>
          @foreach($companyCasses as $casse)
            <tr class="border-b border-bgray-300 dark:border-darkblack-400">
              <td class="">
                <label class="text-center">
                  <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 text-success-300 focus:outline-none focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-600">
                </label>
              </td>
              <td class="px-6 py-5 xl:px-0">{!! $casse->getStatusLabel()!!}</td>
              <td class="px-6 py-5 xl:px-0">{{$casse->company->name}}</td>
              <td class="px-6 py-5 xl:px-0">{{$casse->getTitle()}}</td>
              <td class="px-6 py-5 xl:px-0">{{date('Y-m-d H:i',strtotime($casse->created_at))}}</td>
              <td>
                <div class="payment-select relative">
                  <button onclick="dateFilterAction('#cardsOptions{{$casse->id}}')" type="button">
                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                  <div id="cardsOptions{{$casse->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                    <ul style="min-width: 100px; text-align: center">
                      <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        <a href="{{localeRoute('frontend.profile.company_casse.edit',$casse)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                          {{ __('main.edit') }}
                        </a>
                      </li>
                      <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        <form action="{{localeRoute('frontend.profile.company_casse.destroy',$casse)}}}" method="POST">
                          @csrf
                          <button class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                            {{ __('main.delete') }}
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
  <script src="{{ asset('js/sorting.js') }}"></script>
@endpush
