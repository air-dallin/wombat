@extends('layouts.profile')
@section('title', __('main.warehouse'))
@section('content')

  @include('alert-profile')
  <div class="p-5 rounded-lg bg-white dark:bg-darkblack-600">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="@if(isset($companyWarehouse)) {{localeRoute('frontend.profile.company_warehouse.update',$companyWarehouse)}} @else {{localeRoute('frontend.profile.company_warehouse.store')}} @endif" method="POST" enctype="multipart/form-data">
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
                    <option value="{{$company->id}}" @if( (isset($companyWarehouse) && $companyWarehouse->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                  @endforeach
                </select>
                @error('warehouse_id')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <!-- Nav tabs -->
              @if(app()->getLocale()=='ru')
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_ru')}}</label>
                  <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="title_ru" value="{{ old('title_ru',isset($companyWarehouse)?$companyWarehouse->title_ru:'') }}" required>
                  @error('title_ru')
                  <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                  @enderror
                </div>
              @elseif(app()->getLocale()=='uz')
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_uz')}}</label>
                  <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="title_uz" value="{{ old('title_uz',isset($companyWarehouse)?$companyWarehouse->title_uz:'') }}">
                  @error('title_uz')
                  <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                  @enderror
                </div>
              @elseif(app()->getLocale()=='oz')
                <div class="flex flex-col gap-2">
                  <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_en')}}</label>
                  <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="title_en" value="{{ old('title_en',isset($companyWarehouse)?$companyWarehouse->title_en:'') }}">
                  @error('title_en')
                  <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                  @enderror
                </div>
              @endif
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="status">
                  <option value="0" @if(isset($companyWarehouse) && $companyWarehouse->status==0) selected @endif >{{__('main.inactive')}}</option>
                  <option value="1" @if(isset($companyWarehouse) && $companyWarehouse->status==1) selected @endif >{{__('main.active')}}</option>
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

