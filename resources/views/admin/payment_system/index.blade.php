{{-- Extends layout --}}
@extends('layout.default')
@section('title',__('main.payment_systems'))
{{-- Content --}}
@section('content')

  <div class="container-fluid">
    <?php
    /*  <div class="page-titles">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                   <li class="breadcrumb-item active"><a href="javascript:void(0)">News</a></li>
               </ol>
           </div> */ ?>
        <!-- row -->

    @include('alert')
    <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">

      <div class="col-12">
        <div class="card">
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
                  {{--
                                                      <th><strong>{{__('main.image')}}</strong></th>
                  --}}
                  <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5">{{__('main.status')}}</td>
                  <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" class="sorting" data-sort="title_{{app()->getLocale()}}"><strong>{{__('main.title')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('title_'.app()->getLocale()) !!}</td>
                  <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"></td>
                </tr>
                @if($payment_systems)
                  @foreach($payment_systems as $payment_system)
                    <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row">
                        <?php
                        /* <td>
                                                 <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                                                   <input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
                                                   <label class="custom-control-label" for="customCheckBox2"></label>
                                                 </div>
                                               </td> */ ?>
                        <?php
                        /*                                 <td> @if(isset($payment_system->image))
                                                                       <div class="d-flex align-items-center"><img src="{{ isset($payment_system->image)?Storage::url($payment_system->image->small()) .'?v='.time():'' }}" class="rounded-lg mr-2" width="128px" alt="" /></div></td>
                                                                       @endif */ ?>
                      <td class="px-6 py-5">{!! $payment_system->getStatusLabel() !!}</td>

                      <td class="px-6 py-5">{{ $payment_system->getTitle() }}  </td>
                      <td class="px-6 py-5">
                        <div class="payment-select relative">
                          <button onclick="dateFilterAction('#cardsOptions{{$payment_system->id}}')" type="button">
                            <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                              <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                              <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                          </button>
                          <div id="cardsOptions{{$payment_system->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                            <ul style="min-width: 100px; text-align: center">
                              <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                <a href="{{localeRoute('admin.payment_system.edit',$payment_system)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                  {{ __('main.edit') }}
                                </a>
                              </li>
                              <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                <form action="{{localeRoute('admin.payment_system.destroy',$payment_system)}}}" method="POST">
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
            <div class="mt-4">
              {{ $payment_systems->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

@endsection
@push('scripts')
  <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
  <script src="{{ asset('js/sorting.js') }}"></script>
@endpush
