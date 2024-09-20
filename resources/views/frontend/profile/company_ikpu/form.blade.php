@extends('layouts.profile')
@section('title', __('main.company_ikpus'))
@section('content')

  @include('alert-profile')
  <div class="w-full rounded-lg bg-white px-[24px] py-[20px] dark:bg-darkblack-600">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form id="form_ikpu" action="@if(isset($companyIkpu)) {{localeRoute('frontend.profile.company_ikpu.update',$companyIkpu)}} @else {{localeRoute('frontend.profile.company_ikpu.store')}} @endif" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col mb-3 gap-2">
              <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
              <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="company_id" id="company_id" required>
                <option value="">{{__('main.choice_company')}}</option>
                @php
                  $current_company = App\Models\Company::getCurrentCompanyId();
                @endphp
                @foreach($companies as $company)
                  <option value="{{$company->id}}" @if( (isset($companyIkpu) && $companyIkpu->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                @endforeach
              </select>
              @error('company_id')
              <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
              @enderror
            </div>
            <div class="flex flex-col mb-3 gap-2">
              <label>{{__('main.ikpu')}}</label>
              <select class="form-control ikpu" name="ikpu_id" id="ikpu_id" required>
                <option value="">{{__('main.choice_ikpu')}}</option>
                @foreach($ikpus as $ikpu)
                  <option value="{{$ikpu->id}}" @if( (isset($companyIkpu) && $companyIkpu->ikpu_id==$ikpu->id))  selected @endif >{{$ikpu->code . ' - ' . Str::limit($ikpu->title_ru,128)}}</option>
                @endforeach
              </select>
              @error('ikpu_id')
              <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
              @enderror
            </div>
            <div><b>{{__('main.selecetd_ikpu')}}</b></div>
            <table id="ikpu" class="w-full">
              @isset($ikpuList)
                <tbody>
                <tr class="border-b border-bgray-300 dark:border-darkblack-400">
                  <td class=""></td>
                  <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.code') }}</td>
                  <td class="text-base font-medium text-bgray-600 dark:text-bgray-50 px-6 py-5 ">{{__('main.title')}}</td>
                </tr>
                @foreach ($ikpuList as $n=>$item)
                  <tr id="{{$item->ikpu->id}}" class="border-b border-bgray-300 dark:border-darkblack-400">
                    <td class="">{{++$n}}</td>
                    <td class="px-6 py-5 ">{{$item->ikpu->code}}</td>
                    <td class="px-6 py-5 ">{{$item->ikpu->title_ru}}</td>
                  </tr>
                @endforeach
                </tbody>

              @endisset
            </table>


            <hr>

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
      $(document).ready(function () {
          $('.ikpu').select2();
          $('#company_id').change(function () {
              var company_id = $(this).val();
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/company_ikpu/get-ikpu',
                  data: {'_token': _csrf_token, 'company_id': company_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('table#ikpu').html($response.data);
                      }
                  },
                  error: function (e) {
                      alert(e)
                  }
              });

          });
          $('form#form_ikpu').submit(function (e) {
              if ($('tr#' + $('#ikpu_id').val()).html() != undefined) {
                  e.preventDefault();
                  alert('{{__('main.ikpu_exist')}}');
                  return false;
              }
              return true;
          });

      });
  </script>
@endsection
