@extends('layouts.profile')
@section('title', $company->name)
@section('content')
  @include('alert-profile')

  <div class="grid grid-cols-1 rounded-xl bg-white dark:bg-darkblack-600 xl:grid-cols-12">

    @include('frontend.profile.sections.company_menu',['menu'=>'company_invoice'])

        <style>
            .set_main_invoice{
                cursor:pointer;
            }
        </style>
    <!--Tab Content -->
    <div class="tab-content col-span-9 px-10 py-8">
      <!-- Personal Information -->
      <!-- Notification -->
      <div id="tab4" class="tab-pane active">
        <div class="border-b border-bgray-200 pb-5 dark:border-darkblack-400 flex justify-between items-center">
          <h3 class=" text-2xl font-bold text-bgray-900 dark:text-white">{{ __('main.company_invoices') }}</h3>
          <a href="{{ localeRoute('frontend.profile.company_invoice.create') }}" class="rounded-lg bg-success-300 px-4 py-3.5 font-semibold text-white">{{__('main.create')}}</a>
        </div>
        <table class="w-full">
          <tbody>
          <tr class="border-b border-bgray-300 dark:border-darkblack-400">
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 xl:px-0" data-sort="name"></td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 xl:px-0" data-sort="name">{{__('main.bank_name')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('bank_name') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50" data-sort="title">{{__('main.title')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('title') !!}</td>
            <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50" data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</td>
            <td class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{ __('main.actions') }}</td>

          </tr>
          @foreach($companyInvoices as $invoice)
            <tr class="border-b border-bgray-300 dark:border-darkblack-400">
              <td class="px-6 py-5 xl:px-0">

                          <span class="set_main_invoice" data-id="{{$invoice->id}}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path id="{{$invoice->id}}" d="M12.0001 17.75L5.82808 20.995L7.00708 14.122L2.00708 9.25495L8.90708 8.25495L11.9931 2.00195L15.0791 8.25495L21.9791 9.25495L16.9791 14.122L18.1581 20.995L12.0001 17.75Z" fill="#{{$invoice->is_main ?'F6A723':'FFFFFF'}}" stroke="#F6A723" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                          </span>
                {{--  NO ACTIVE  --}}
                {{--                          <span>--}}
                {{--                            <svg class="fill-bgray-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
                {{--                              <path d="M12.0001 17.75L5.82808 20.995L7.00708 14.122L2.00708 9.25495L8.90708 8.25495L11.9931 2.00195L15.0791 8.25495L21.9791 9.25495L16.9791 14.122L18.1581 20.995L12.0001 17.75Z" stroke-linecap="round" stroke-linejoin="round"></path>--}}
                {{--                            </svg>--}}
                {{--                          </span>--}}
              </td>
              <td class="px-6 py-5 xl:px-0">{{ 'Капитал банк' /* Str::limit($invoice->company->bank_name,32)*/}}</td>
              <td class="px-6 py-5 xl:px-0">{{$invoice->bank_invoice }}</td>
              <td class="px-6 py-5 xl:px-0">{{date('Y-m-d H:i',strtotime($invoice->created_at))}}</td>
              <td class="px-6 py-5 xl:px-0">
                <div class="payment-select relative">
                  <button onclick="dateFilterAction('#cardsOptions{{$invoice->id}}')" type="button">
                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                  <div id="cardsOptions{{$invoice->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                    <ul style="min-width: 100px; text-align: center">
                      <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        <a href="{{localeRoute('frontend.profile.company_invoice.edit',$invoice)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                          {{ __('main.edit') }}
                        </a>
                      </li>
                      <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                        <form action="{{localeRoute('frontend.profile.company_invoice.destroy',$invoice)}}}" method="POST">
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
@section('js')
    <script>
        $(document).ready(function () {
            $('.set_main_invoice').click(function () {
                id = $(this).data('id');
                obj = $(this);
                company_id = '{{$company->id}}';
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/company_invoice/set-main-invoice',
                    data: {'_token': _csrf_token, 'id': id,'company_id':company_id},
                    success: function ($response) {
                        if ($response.status) {
                            $('.set_main_invoice path').attr('fill','#FFFFFF');
                            $('path#'+id).attr('fill','#F6A723');
                        } else {
                            alert($response.error);
                        }
                        clicked = false;
                    },
                    error: function (e) {
                        alert(e)
                        clicked = false;
                    }
                });
            });
        });
    </script>
@endsection
