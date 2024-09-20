@extends('layouts.profile')
@section('title',__('main.nomenklatures'))
@section('content')

  <div class="container-fluid">
    @include('alert-profile')
    <div class="p-5 rounded-lg bg-white dark:bg-darkblack-600">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form method="POST" enctype="multipart/form-data"
                  @isset($nomenklature)
                    action="{{localeRoute('frontend.profile.modules.nomenklature.update',$nomenklature)}}"
                  @else
                    action="{{localeRoute('frontend.profile.modules.nomenklature.store')}}"
                @endisset
            >
              @csrf

              <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="company_id" id="company_id" required>
                    <option value="">{{__('main.choice_company')}}</option>
                    @php
                      $current_company = App\Models\Company::getCurrentCompanyId();
                    @endphp
                    @foreach($companies as $company)
                      <option value="{{$company->id}}" @if( (isset($nomenklature) && $nomenklature->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                    @endforeach
                  </select>
                  @error('company_id')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.ikpu')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="ikpu_id" id="ikpu_id" required>
                    @isset($ikpu)
                      @foreach($ikpu as $item)
                        <option value="{{ $item->ikpu_id }}"
                                @if(isset($nomenklature) && $nomenklature->ikpu_id==$item->id) selected @endif >{{ $item->code .' - ' . \Illuminate\Support\Str::limit($item->getTitle(),64) }}
                        </option>
                      @endforeach
                    @endisset
                  </select>
                  @error('ikpu_id')
                  <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                  @enderror
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.units')}}</label>
                  <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="unit_id" required>
                    <option value="">{{__('main.choice_unit')}}</option>
                    @isset($units)
                      @foreach($units as $unit)
                        <option value="{{$unit->id}}">{{$unit->getTitle()}}</option>
                      @endforeach
                    @endisset
                  </select>

                </div>
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.quantity')}}</label>
                  <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="quantity" value="{{ old('quantity',isset($nomenklature)?$nomenklature->quantity:'') }}" required>
                  @error('quantity')
                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                  @enderror
                </div>

                <!-- Nav tabs -->
               {{-- <div class="flex flex-col gap-2 mt-2">
                  <ul class="flex gap-2">
                    <li>
                      <a class="dark:bg-success-300 dark:text-bgray-900 border-2 border-transparent text-white rounded-lg px-4 py-3 font-semibold text-sm" style="background: orange" data-toggle="tab" href="#ru">RU</a>
                    </li>
                    <li>
                      <a class="bg-white dark:bg-darkblack-500 dark:text-bgray-50 dark:border-success-300 border-2 text-bgray-900 rounded-lg px-4 py-3 font-semibold text-sm" data-toggle="tab" href="#en">UZ lat</a>
                    </li>
                    <li>
                      <a class="bg-white dark:bg-darkblack-500 dark:text-bgray-50 dark:border-success-300 border-2 text-bgray-900 rounded-lg px-4 py-3 font-semibold text-sm" data-toggle="tab" href="#uz">UZ</a>
                    </li>

                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade  show active" id="ru" role="tabpanel">--}}

                  @if(app()->getLocale()=='ru')
                      <div class="pt-4">
                        <div class="basic-form">
                          <div class="flex flex-col gap-2">
                            <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_ru')}}</label>
                            <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="title_ru" value="{{ old('title_ru',isset($nomenklature)?$nomenklature->title_ru:'') }}" required>
                            @error('title_ru')
                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                  @elseif(app()->getLocale()=='uz')

                      <div class="pt-4">
                        <div class="basic-form">
                          <div class="flex flex-col gap-2">
                            <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_uz')}}</label>
                            <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="title_uz" value="{{ old('title_uz',isset($nomenklature)?$nomenklature->title_uz:'') }}">
                            @error('title_uz')
                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                  @elseif(app()->getLocale()=='oz')
                      <div class="pt-4">
                        <div class="basic-form">
                          <div class="flex flex-col gap-2">
                            <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_en')}}</label>
                            <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="title_en" value="{{ old('title_en',isset($nomenklature)?$nomenklature->title_en:'') }}">
                            @error('title_en')
                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                  @endif

              </div>
              <div class="flex justify-end">
                <button type="submit" class="mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
              </div>
            </form>

          </div>

        </div>

      </div>
    </div>
  </div>

@endsection

@section('js')
  <script>
      $(document).ready(function () {

          $('#company_id').change(function () {
              let company_id = $(this).val();
              if (company_id == '') return false;
              $('select[name=ikpu_id]').html('');
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/get-ikpu',
                  data: {'_token': _csrf_token, 'company_id': company_id},
                  success: function ($response) {
                      if ($response.status) {
                          $('select[name=ikpu_id]').html($response.data);
                      } else {
                          alert($response.error);
                      }
                  },
                  error: function (e) {
                      alert(e)
                  }
              });
          });

      });
  </script>
@endsection
