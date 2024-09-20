{{-- Exteplan layout --}}
@extends('layouts.profile')
@section('title',__('main.turnover_balance_sheet') .' - ' . $company->name)
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
          @include('frontend.profile.company_account.filter')
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
                <td rowspan="2" class=" text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort=""><strong>{{__('main.plan_name')}}</strong> </td>
                <td colspan="2" class=" text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="date"><strong>{{__('main.saldo_start')}}</strong> </td>
                <td colspan="2" class=" text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="date"><strong>{{__('main.turnover')}}</strong> </td>
                <td colspan="2" class=" text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="date"><strong>{{__('main.saldo_end')}}</strong> </td>
              </tr>
              <tr>
                  <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="debit_account"><strong>{{__('main.debit')}}</strong> </td>
                  <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="credit_account"><strong>{{__('main.credit')}}</strong> </td>
                  <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="debit_account"><strong>{{__('main.debit')}}</strong> </td>
                  <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="credit_account"><strong>{{__('main.credit')}}</strong> </td>
                  <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="debit_account"><strong>{{__('main.debit')}}</strong> </td>
                  <td class="sorting text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5" data-sort="credit_account"><strong>{{__('main.credit')}}</strong> </td>
                  {{--
                                  <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5"></td>
                  --}}
              </tr>
              @if($planRows)

                @foreach($planRows as $plan)
                  <tr class="border-b border-bgray-300 dark:border-darkblack-400 hover:bg-gray-300 clickable-row">
                      <?php
                      /* <td>
                                               <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                                                 <input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
                                                 <label class="custom-control-label" for="customCheckBox2"></label>
                                               </div>
                                             </td> */ ?>
                      <td class="px-6 py-5"><a class="saldo-link" href="{{$plan['url']}}">{{ $plan['code'] .', ' . $plan['title']  }}</a></td>
                    <td class="px-6 py-5">{{ $plan['items'][0]==0 ? '' : number_format($plan['items'][0],2,'.',' ') }}  </td>
                    <td class="px-6 py-5">{{ $plan['items'][1]==0 ? '' : number_format($plan['items'][1],2,'.',' ') }}  </td>
                    <td class="px-6 py-5">{{ $plan['items'][2]==0 ? '' : number_format($plan['items'][2],2,'.',' ') }}  </td>
                    <td class="px-6 py-5">{{ $plan['items'][3]==0 ? '' : number_format($plan['items'][3],2,'.',' ') }}  </td>
                    <td class="px-6 py-5">{{ $plan['items'][4]==0 ? '' : number_format($plan['items'][4],2,'.',' ') }}  </td>
                    <td class="px-6 py-5">{{ $plan['items'][5]==0 ? '' : number_format($plan['items'][5],2,'.',' ') }}  </td>

                @endforeach
              @endif

              </tbody>
            </table>
          </div>

          {{ $planRows->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}

        </div>
      </div>
    </div>

  </div>

@endsection
@push('scripts')
  <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
  <script src="{{ asset('js/sorting.js') }}"></script>

@endpush

