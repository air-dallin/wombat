@extends('layouts.profile')
@section('title', __('main.company_invoice'))
@section('content')

  @include('alert-profile')
  <div class="p-5 rounded-lg bg-white dark:bg-darkblack-600">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="@if(isset($companyInvoice)) {{localeRoute('frontend.profile.company_invoice.update',$companyInvoice)}} @else {{localeRoute('frontend.profile.company_invoice.store')}} @endif" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-6 2xl:grid-cols-3">
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="company_id" id="company_id" required>
                  <option value="">{{__('main.choice_company')}}</option>
                  @php
                    $current_company = App\Models\Company::getCurrentCompanyId();
                  @endphp
                  @foreach($companies as $company)
                    <option value="{{$company->id}}" @if( (isset($companyInvoice) && $companyInvoice->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                  @endforeach
                </select>
                @error('casse_id')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.bank_invoice')}}</label>
                <input type="text" name="bank_invoice" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="" value="{{old('bank_invoice',isset($companyInvoice) ? $companyInvoice->bank_invoice :'')}}" required>
                @error('bank_invoice')
                <small class="invalid-feedback"> {{ $message }} </small>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="status">
                  <option value="0" @if(isset($contract) && $contract->status==0) selected @endif >{{__('main.inactive')}}</option>
                  <option value="1" @if(isset($contract) && $contract->status==1) selected @endif >{{__('main.active')}}</option>
                </select>
              </div>
            </div>
            <div class="flex justify-end">
              <button type="submit" class="mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js')
  <script>
      var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';
      $(document).ready(function () {
          var clicked = false;
          $('.get_company').click(function () {
              let inn = $('#inn').val();
              if (inn == '') return false;
              if (clicked) return false;
              clicked = true;
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/get-company-by-inn',
                  data: {'_token': _csrf_token, 'inn': inn, 'user_id': user_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('input[name=name]').val($response.data['name']);
                          $('input[name=address]').val($response.data['address']);
                          $('input[name=bank_name]').val($response.data['bank_name']);
                          $('input[name=bank_code]').val($response.data['bank_code']);
                          $('input[name=mfo]').val($response.data['mfo']);
                          $('input[name=oked]').val($response.data['oked']);
                          $('input[name=nds_code]').val($response.data['nds_code']);
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
