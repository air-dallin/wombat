@extends('layouts.profile')
@section('title')

  @include('frontend.profile.sections.document_create_menu',['currentMenuType'=>'doc'])

@endsection
@section('content')

  @include('alert-profile')
  <div class="container-fluid">
    <div class="">
      <div class="col-12">
        <div class="card">
            <form method="POST" id="form_doc" enctype="multipart/form-data"
                  @isset($doc)
                  action="{{localeRoute('frontend.profile.modules.doc.update',$doc)}}"
                  @else
                  action="{{localeRoute('frontend.profile.modules.doc.store')}}"
                @endisset
            >
                @csrf
          <div class="card-body">


              <input type="hidden" name="update_products" id="update_products" value="0">
              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">

                  <div class="grid grid-cols-1 gap-6 2xl:grid-cols-3">

                      <div class="flex flex-col gap-2">
                          <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.contract')}}</label>
                          <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white   @error('contract_id') is-invalid @enderror" name="contract_id" id="contract_id" required>
                              <option value="">{{__('main.choice_contract')}}</option>
                              @foreach($contracts as $contract)
                                  <option value="{{$contract->id}}" data-date="{{ $contract->contract_date }}" @if(isset($incoming_order) && $incoming_order->contract_id==$contract->id) selected @endif >{{ $contract->contract_number . ' - ' . date('Y-m-d',strtotime($contract->contract_date)) }}</option>
                              @endforeach
                          </select>

                          @error('contract_id')
                          <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                          @enderror
                      </div>


                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.number')}}</label>
                    <input type="text" name="number" id="number" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('number') is-invalid @enderror @error('number') is-invalid @enderror" placeholder="" value="{{old('number',isset($doc) ? $doc->number : $new_number)}}" required>
                    @error('number')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>
                  <div class="flex flex-col gap-2">
                    <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.date')}}</label>
                    <input type="date" name="date" id="date" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('date') is-invalid @enderror" placeholder="" value="{{old('date',isset($doc) ? $doc->date :date('Y-m-d'))}}" required>
                    @error('date')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  </div>

                </div>
              </div>
              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">

                  <div class="grid grid-cols-1 gap-12 2xl:grid-cols-2">

                      <div class="flex flex-col gap-2">
                          <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title')}}</label>
                          <input type="name" name="name" id="name" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('name') is-invalid @enderror" placeholder="" value="{{old('name',isset($doc) ? $doc->name :'')}}" required>
                          @error('name')
                          <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                          @enderror
                      </div>
                  </div>
              </div>
              <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">
                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                  <div>
                    <h3 class="border-b border-bgray-200 pb-5 text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">{{__('main.your_info')}}</h3>
                    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                      <div class="flex flex-col mt-3 mb-3 gap-2">
                        <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company_inn')}}</label>
                        <input type="text" name="company_inn"
                               class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('company_inn') is-invalid @enderror @error('company_inn') is-invalid @enderror"
                               placeholder=""
                               value="{{old('company_inn',isset($doc) ? $doc->company_inn :'')}}">
                        @error('company_inn')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                      </div>
                      <div class="flex flex-col mt-3 mb-3 gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
                        <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="company_id" id="company_id" required>
                          <option value="">{{__('main.choice_company')}}</option>
                          @php
                            $current_company = App\Models\Company::getCurrentCompanyId();
                          @endphp
                          @foreach($companies as $_company)
                            <option value="{{$_company->id}}" @if( (isset($doc) && $doc->company_id==$_company->id) || $_company->id==$current_company) selected @endif >{{$_company->name}}</option>
                          @endforeach
                        </select>
                        @error('company_id')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                      </div>
                    </div>

                    </div>
                    <div>
                      <h3 class="border-b border-bgray-200 pb-5 text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">{{__('main.partner_info')}}</h3>
                      <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                        <div class="flex flex-col mt-3 mb-3 gap-2 input-group">
                          <label for="partner_inn" class="text-base font-medium text-bgray-600 dark:text-bgray-50 control-label">{{__('main.enter_inn')}}</label>
                          <div class="flex h-[56px] w-full">
                            <input type="text" name="partner_inn" id="partner_inn" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white @error('partner_inn') placeholder="{{__('main.enter_inn')}}" is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('partner_inn',isset($doc) ? $doc->partner_inn :'')}}" style="width: 70%;" required>
                            @error('partner_inn')
                            <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                            <button type="button" class="bg-gray-300 get_company p-3 get_company" style="width: 30%;"><i class="fa fa-search send "></i></button>
                          </div>


                        </div>
                        <div class="flex flex-col mt-3 mb-3 gap-2">
                          <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.partner_company_name')}}</label>
                          <input type="text" name="partner_company_name" id="partner_company_name" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder=""
                                 value="{{old('partner_company_name',isset($doc) ? $doc->partner_company_name :'')}}" required>
                          @error('partner_company_name')
                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                          @enderror
                        </div>
                      </div>

                </div>
              </div>


          </div>

        </div>
          <div class="p-5 mb-5 rounded-lg bg-white dark:bg-darkblack-600">

              <div class="flex flex-col gap-2">

                  <div class="flex h-24 w-100 flex-col items-center justify-center rounded-lg border-2 border-dashed border-bgray-300 dark:border-darkblack-400 md:h-32 md:w-32 lg:h-44 lg:w-44" style="width: 100%;">
                      <div class="flex flex-col items-center" id="choice_file">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-[#F8F8F8] dark:bg-darkblack-500"><svg class="fill-bgray-900 dark:fill-white" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.79995 4.0002C8.79995 3.55837 8.44178 3.2002 7.99995 3.2002C7.55812 3.2002 7.19995 3.55837 7.19995 4.0002V7.2002H3.99995C3.55812 7.2002 3.19995 7.55837 3.19995 8.0002C3.19995 8.44202 3.55812 8.8002 3.99995 8.8002H7.19995V12.0002C7.19995 12.442 7.55812 12.8002 7.99995 12.8002C8.44178 12.8002 8.79995 12.442 8.79995 12.0002V8.8002H12C12.4418 8.8002 12.8 8.44202 12.8 8.0002C12.8 7.55837 12.4418 7.2002 12 7.2002H8.79995V4.0002Z"></path></svg></span>
                          <span class="mt-4 block font-medium text-bgray-500 text-3xl">{{__('main.choice_file')}}</span>
                          <div id="selected_filename"></div>
                      </div>
                  </div>
              </div>
          </div>

            @if(!isset($doc) || $doc->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING)
                <div class="flex justify-center">

                    <button type="submit" class="btn-create rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
                    <button type="submit" class="rounded-lg px-4 py-3.5 font-semibold text-white" style="background: green; margin: 0 15px;">{{__('main.sign')}}</button>
                    <button type="submit" class="rounded-lg px-4 py-3.5 font-semibold text-white" style="background: red">{{__('main.reject')}}</button>

                </div>
                @endif

                <div style="display: none">
                    <input type="file" name="file" accept="application/pdf">
                </div>

          </form>


      </div>
    </div>
  </div>

@endsection
<!-- component -->

@include('frontend.sections.notify')
@section('js')
  <script>
      $(document).ready(function () {
          var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';
          var clicked = false;
          $('#company_id').change(function () {
              let company_id = $(this).val();
              if (company_id == '') return false;
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/get-info',
                  data: {'_token': _csrf_token, 'company_id': company_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('input[name=company_inn]').val($response.data['inn']);
                      } else {
                          alert($response.error);
                      }
                  },
                  error: function (e) {
                      alert(e)
                  }
              });
          });

          $('.btn-create').click(function (e) {
              e.preventDefault()
              if (!$.isNumeric($('#company_id').val())) {
                  alert('{{__('main.choice_company')}}')
                  $('#company_id').focus();
                  return false;
              }
              if ($('#partner_inn').val().length < 1) {
                  alert('{{__('main.choice_contragent')}}')
                  $('#partner_inn').focus();
                  return false;
              }
              if ($('#date').val().length < 1) {
                  alert('{{__('main.choice_date')}}')
                  $('#date').focus();
                  return false;
              }
              if ($('#partner_company_name').val().length < 1) {
                  alert('{{__('main.choice_contragent_company')}}')
                  $('#partner_company_name').focus();
                  return false;
              }
            /*  alert('not save');

              return false;*/

              $('#form_doc').submit();
          });

          $('.get_company').click(function () {
              let inn = $('#partner_inn').val();
              if (inn == '') return false;
              if (clicked) return false;
              clicked = true;
              notify('{{__('main.wait')}}', 'primary', 10000);
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/get-company-by-inn',
                  data: {'_token': _csrf_token, 'inn': inn, 'user_id': user_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('input[name=partner_company_name]').val($response.data['name']);
                          notify('{{__('main.success')}}', 'success', 3000);
                      } else {
                          notify($response.error, 'danger', 10000);
                      }
                      clicked = false;
                  },
                  error: function (e) {
                      alert(e)
                      clicked = false;
                  }
              });
          });

        @if(!isset($doc) && $current_company) /*$current_company)*/
        $('#company_id option[value={{$current_company}}]').attr('selected', 'selected').change();
        @endif

        $('#choice_file').click(function () {
            $('input[name=file]').click();
            $('input[name=file]').change(function (e) {
                $('#selected_filename').text(e.target.files[0].name);
            })
        });

      })

  </script>
@endsection

