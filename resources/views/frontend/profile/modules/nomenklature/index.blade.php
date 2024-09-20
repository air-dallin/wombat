@extends('layouts.profile')
@section('title', __('main.nomenklatures'))
@section('content')

  <div class="container-fluid">
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
                      <a href="{{ localeRoute('frontend.profile.modules.nomenklature.create') }}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-success-300 text-white transition duration-300 ease-in-out hover:bg-success-400" style="min-width: 230px;">{{__('main.create')}}</a>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table class="w-full">
              <tbody>
              <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                <td class="">
                  <label class="text-center">
                    <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 bg-transparent text-success-300 focus:outline-none focus:ring-0">
                  </label>
                </td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 " data-sort="title_{{ app()->getLocale() }}">{{__('main.title')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('title_'.app()->getLocale()) !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 " data-sort="company_id">{{__('main.company')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('company_id') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 " data-sort="ikpu_id">{{__('main.ikpu')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('ikpu_id') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 " data-sort="unit_id">{{__('main.unit')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('unit_id') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 " data-sort="quantity">{{__('main.quantity')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('quantity') !!}</td>
                <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 " data-sort="status">{{__('main.status')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('status') !!}</td>
                <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{ __('main.actions') }}</td>
              </tr>

              @if(isset($nomenklatures))
                @foreach($nomenklatures as $nomenklature)
                  <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="">
                      <label class="text-center">
                        <input type="checkbox" class="h-5 w-5 cursor-pointer rounded-full border border-bgray-400 text-success-300 focus:outline-none focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-600">
                      </label>
                    </td>
                    <td class="px-6 py-5 ">{{ $nomenklature->getTitle() }}  </td>
                    <td class="px-6 py-5 ">@isset($nomenklature->company)
                        {{ $nomenklature->company->name }}
                      @else
                        ''
                      @endif  </td>
                    <td class="px-6 py-5 ">@isset($nomenklature->ikpu)
                        {{ $nomenklature->ikpu->code . ' - ' . \Illuminate\Support\Str::limit($nomenklature->ikpu->getTitle(),32) }}
                      @else
                        ''
                      @endif </td>
                    <td class="px-6 py-5 ">@isset($nomenklature->unit)
                        {{ $nomenklature->unit->getTitle() }}
                      @else
                        ''
                      @endif </td>
                    <td class="px-6 py-5 ">{{ $nomenklature->quantity }}  </td>
                    <td class="px-6 py-5 ">{!! $nomenklature->getStatusLabel() !!}  </td>
                    <td class="px-6 py-5 ">
                      <div class="payment-select relative">
                        <button onclick="dateFilterAction('#cardsOptions{{$nomenklature->id}}')" type="button">
                          <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          </svg>
                        </button>
                        <div id="cardsOptions{{$nomenklature->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                          <ul style="min-width: 100px; text-align: center">
                            <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                              <a href="{{ localeRoute('frontend.profile.modules.nomenklature.edit',$nomenklature) }}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                {{ __('main.edit') }}
                              </a>
                            </li>
                            <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                              <form action="{{ localeRoute('frontend.profile.modules.nomenklature.destroy',$nomenklature) }}" method="POST">
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
                    </td>
                  </tr>
                @endforeach
              @endif
              </tbody>
            </table>
          </div>
          {{ $nomenklatures->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
  <script src="{{ asset('js/sorting.js') }}"></script>
@endpush
