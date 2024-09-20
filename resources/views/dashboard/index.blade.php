@extends('layout.default')
@section('title', __('main.dashboard'))
@section('content')
  <section class="mb-6 2xl:mb-0 2xl:flex-1">
    <div class="flex grid-cols-2 flex-col-reverse mb-5 gap-12 xl:grid 2xl:flex-row">
      <div class="space-x-2 rounded-xl bg-success-300 px-4 py-8">
        <h3 class="text-xl font-bold text-bgray-900 dark:text-bgray-50 lg:text-3xl lg:leading-[36.4px]">{{__('main.clients')}}</h3>
        <h1 class="text-xl font-bold text-bgray-900 dark:text-bgray-50 lg:text-3xl lg:leading-[36.4px]" style="margin-left: 0">{{App\Models\User::where(['role'=>\App\Models\User::ROLE_CLIENT])->count()}}</h1>
      </div>
      <div class="gap-2 space-x-2 rounded-xl px-4 py-8" style="background: orange">
        <h3 class="text-xl font-bold text-bgray-900 dark:text-bgray-50 lg:text-3xl lg:leading-[36.4px]">{{__('main.companies')}}</h3>
        <h1 class="text-xl font-bold text-bgray-900 dark:text-bgray-50 lg:text-3xl lg:leading-[36.4px]" style="margin-left: 0">{{App\Models\User::where(['role'=>\App\Models\User::ROLE_COMPANY])->count()}}</h1>
      </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 mb-5">
      <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
        <div class="col-xl-6 col-lg-6 col-xxl-6 col-sm-6">
          <div class="card">
            <div class="card-header">
              <div class="mb-3 flex items-center justify-between">
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white">
                  {{__('main.payment_orders')}}
                </h3>
                <div class="payment-select relative">
                  <button onclick="dateFilterAction('#payment_orders')" type="button">
                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                  <div id="payment_orders" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-0 z-10 top-full hidden overflow-hidden" style="display: none;">
                    <ul style="min-width: 100px; text-align: center">
                      <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        <a href="#/{{app()->getLocale() .'/admin/ticket'}}" class="btn btn-success light sharp">{{__('main.show_all')}}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive recentOrderTable">
                <table class="w-full">
                  <tbody>
                  <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.from')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.assign_to')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.region')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.created_at')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.status')}}</td>
                  </tr>
                  {{-- @if(isset($tickets))
                       @foreach($tickets as $ticket)
                           <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row">
                               <td class="px-6 py-5">{{isset($ticket->userFrom)?$ticket->userFrom->info->getFullname():''}}</td>
                               <td class="px-6 py-5">{{isset($ticket->userTo) ? $ticket->userTo->info->getFullname() : ''}}</td>
                               <td class="px-6 py-5">{{isset($ticket->userFrom)?$ticket->userFrom->info->region->getTitle():''}}</td>
                               <td class="px-6 py-5">{{date('Y-m-d',strtotime($ticket->created_at))}}</td>
                               <td class="px-6 py-5">{!! $ticket->getStatusLabel()!!}</td>
                           </tr>
                       @endforeach
                   @endif--}}

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
        <div class="col-xl-6 col-lg-6 col-xxl-6 col-sm-6">
          <div class="card">
            <div class="card-header">
              <div class="mb-3 flex items-center justify-between">
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white"> {{__('main.contracts')}}</h3>
                <div class="payment-select relative">
                  <button onclick="dateFilterAction('#contracts')" type="button">
                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                  <div id="contracts" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-0 z-10 top-full hidden overflow-hidden" style="display: none;">
                    <ul style="min-width: 100px; text-align: center">
                      <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        <a href="#/{{app()->getLocale() .'/admin/claim'}}" class="btn btn-success light sharp">{{__('main.show_all')}}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive recentOrderTable">
                <table class="w-full">
                  <tbody>
                  <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.number')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.region')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.created_at')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.status')}}</td>
                  </tr>
                  {{-- @if(isset($claims))
                       @foreach($claims as $claim)
                         <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row">
                       <td class="px-6 py-5">{{isset($claim->userFrom)?$claim->userFrom->info->getFullName():''}} </td>
                       <td class="px-6 py-5">{{isset($claim->userFrom)?$claim->userFrom->info->region->getTitle():''}}</td>
                       <td class="px-6 py-5">{{$claim->title}}</td>
                       <td class="px-6 py-5">{{date('Y-m-d',strtotime($claim->created_at))}}</td>
                       <td class="px-6 py-5">{!! $claim->getStatusLabel()!!}</td>
                   </tr>
                       @endforeach
                   @endif--}}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="grid gap-6 md:grid-cols-2">
      <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
        <div class="col-xl-6 col-lg-6 col-xxl-6 col-sm-6">
          <div class="card">
            <div class="card-header">
              <div class="mb-3 flex items-center justify-between">
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white"> {{__('main.new_users')}}</h3>
                <div class="payment-select relative">
                  <button onclick="dateFilterAction('#new_users')" type="button">
                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                  <div id="new_users" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-0 z-10 top-full hidden overflow-hidden" style="display: none;">
                    <ul style="min-width: 100px; text-align: center">
                      <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        <a href="#/{{app()->getLocale() .'/admin/user?type=1'}}" class="btn btn-success light sharp">{{__('main.show_all')}}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive recentOrderTable">
                <table class="w-full">
                  <tbody>
                  <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.role')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.name')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.date')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.status')}}</td>
                  </tr>
                  @if(isset($users))
                    @foreach($users as $user)
                      <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row">
                        <td class="px-6 py-5">{{$user->getRoleTitle($user->role)}}</td>
                        <td class="px-6 py-5">@isset($user->info)
                            {{$user->info->getFullname()}}
                          @else
                            {{__('main.not_set')}}
                          @endisset</td>
                        <td class="px-6 py-5">{{date('Y-m-d H:i',strtotime($user->created_at))}}</td>
                        <td class="px-6 py-5">{!! $user->getStatusLabel()!!}</td>
                      </tr>
                    @endforeach
                  @endif

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
        <div class="col-xl-6 col-lg-6 col-xxl-6 col-sm-6">
          <div class="card">
            <div class="card-header">
              <div class="mb-3 flex items-center justify-between">
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white"> {{__('main.new_companies')}}</h3>
                <div class="payment-select relative">
                  <button onclick="dateFilterAction('#new_companies')" type="button">
                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                  <div id="new_companies" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-0 z-10 top-full hidden overflow-hidden" style="display: none;">
                    <ul style="min-width: 100px; text-align: center">
                      <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        <a href="#/{{app()->getLocale() .'/admin/publication'}}" class="btn btn-success light sharp">{{__('main.show_all')}}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive recentOrderTable">
                <table class="w-full">
                  <tbody>
                  <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.image')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.user')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.title')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.category')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.date')}}</td>
                    <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.status')}}</td>
                  </tr>
                  @if(isset($publications))
                    @foreach($publications as $publication)
                      <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row">
                        <td class="px-6 py-5"> @isset($publication->image)
                            <img class="me-3 rounded-0 width70 height70" src="{{ Storage::url( $publication->image->small())  }}">
                          @endisset </td>
                        <td class="px-6 py-5" style=" line-height: 1; ">{{$publication->getTitle()}}</td>
                        <td class="px-6 py-5">{{$publication->category->getTitle()}}</td>
                        <td class="px-6 py-5">{{date('Y-m-d',strtotime($publication->created_at))}}</td>
                        <td class="px-6 py-5">{!! $publication->getStatusLabel()!!}</td>
                      </tr>
                    @endforeach
                  @endif


                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
