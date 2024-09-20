@extends('layouts.profile')
@section('title', __('main.companies'))
@section('content')
  @include('alert-profile')


  <div class="grid grid-cols-1 rounded-xl bg-white dark:bg-darkblack-600 xl:grid-cols-12">

  @include('frontend.profile.sections.company_menu',['menu'=>'info'])


  <!--Tab Content -->
    <div class="tab-content col-span-9 px-10 py-8">
      <!-- Personal Information -->
      <div id="tab1" class="tab-pane active">
        <div class="row">
          <div class="col-12">
            <div class="card companies">
              {{--                <div class="card-header">--}}
              {{--      <div class="flex justify-between">--}}
              {{--        <h4>{{__('main.company')}}</h4>--}}
              {{--        @isset($company)--}}
              {{--          <a href="{{ localeRoute('frontend.profile.companies.info',$company) }}" class="btn-create">{{__('main.back')}}</a>--}}
              {{--        @else--}}
              {{--          <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>--}}
              {{--        @endif--}}
              {{--      </div>--}}
              {{--                </div>--}}
              <div class="">
                <form action="@if(isset($company)) {{localeRoute('frontend.profile.companies.update',$company)}} @else {{localeRoute('frontend.profile.companies.store')}} @endif" method="POST" enctype="multipart/form-data">
                  @csrf
                  <h3 class="border-b border-bgray-200 pb-5 text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">{{ __('main.profile_company') }}</h3>
                  <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.search_by_inn')}}</label>
                      <div class="flex h-[56px] w-full">
                        <input type="text" name="company[inn]" id="inn" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('inn') is-invalid @enderror" style="width: 60%;" placeholder="{{__('main.enter_inn')}}" value="{{old('inn',isset($company) ? $company->inn :'')}}" required {{!empty($company) ? 'readonly':''}}>
                        @error('inn')
                        <small class="invalid-feedback"> {{ $message }} </small>
                        @enderror
                        <button type="button" class="bg-gray-300 get_company p-3" style="width: 40%;"><i class="fa fa-search "></i> {{__('main.update_gnk')}}</button>
                      </div>
                    </div>
                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.company_name')}}</label>
                      <input type="text" name="company[name]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white  @error('name') is-invalid @enderror" placeholder="" value="{{old('name',isset($company) ? $company->name :'')}}" required {{!empty($company) ? 'readonly':''}}>
                    </div>


                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.oked')}}</label>
                      <input type="text" name="company[oked]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="" value="{{old('oked',isset($company) ? $company->oked :'')}}" required>
                      @error('oked')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.mfo')}}</label>
                      <input type="text" name="company[mfo]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="" value="{{old('mfo',isset($company) ? $company->mfo :'')}}" required>
                      @error('mfo')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.bank_code')}}</label>
                      <input type="text" name="company[bank_code]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="" value="{{old('bank_code',isset($company) ? $company->bank_code :'')}}" required>
                      @error('bank_code')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.nds_code')}}</label>
                      <input type="text" name="company[nds_code]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " placeholder="" value="{{old('nds_code',isset($company) ? $company->nds_code :'')}}" required {{!empty($company) ? 'readonly':''}}>
                      @error('nds_code')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                      <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.nds')}}</label>
                      <select class="select2-regions" name="company[nds_id]" style="width: 100%;" required>
                        <option value="">{{__('main.choice_nds')}}</option>
                        @isset($nds)
                          @foreach($nds as $item)
                            <option value="{{$item->id}}" @if(isset($company) && $company->nds_id==$item->id) selected @endif >{{$item->getTitle()}}</option>
                          @endforeach
                        @endisset
                      </select>
                    </div>
                    <div class="flex flex-col gap-2">
                      <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.excise')}}</label>
                      <select class="select2-regions" name="company[excise]" style="width: 100%;" required>
                        <option value="">{{__('main.choice_excise')}}</option>
                        <option value="0" @if(isset($company) && $company->excise==0) selected @endif >{{__('main.no')}}</option>
                        <option value="1" @if(isset($company) && $company->excise==1) selected @endif >{{__('main.yes')}}</option>
                      </select>
                    </div>
                  </div>
                  <h4 class="pb-6 pt-8 text-xl font-bold text-bgray-900 dark:text-white">{{ __('main.contacts') }}</h4>
                  <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.phone')}}</label>
                      <input type="text" name="company[phone]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="" value="{{old('phone',isset($company) ? $company->phone :'')}}" required>
                      @error('phone')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.address')}}</label>
                      <input type="text" name="company[address]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " placeholder="" value="{{old('address',isset($company) ? $company->address :'')}}" required>
                      @error('address')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                      <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.city')}}</label>
                      <select class="select2-regions" name="company[city_id]" id="cities" style="width: 100%; height: 56px; !important">
                        @isset($cities)
                          <option value="0">{{__('main.choice_city')}}</option>
                          @foreach($cities as $city)
                            <option value="{{$city->id}}"
                                    @if(isset($company) && $company->city_id==$city->id) selected @endif >{{$city->title_ru}}
                            </option>
                          @endforeach
                        @endisset
                      </select>
                    </div>
                    <div class="flex flex-col gap-2">
                      <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.district')}}</label>
                      <select class="select2-regions" name="company[district_id]" id="districts" style="width: 100%;">
                        @isset($districts)
                          <option value="0">{{__('main.choice_district')}}</option>
                          @foreach($districts as $district)
                            <option value="{{$district->id}}" @if(isset($company) && $company->district_id==$district->id) selected @endif >{{$district->title_ru}}</option>
                          @endforeach
                        @endisset
                      </select>

                    </div>
                  </div>
                  <h4 class="pb-6 pt-8 text-xl font-bold text-bgray-900 dark:text-white">{{ __('main.face') }}</h4>
                  <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">


                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.director')}}</label>
                      <input type="text" name="company[director]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="" value="{{old('director',isset($company) ? $company->director :'')}}" required>
                      @error('director')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.director_pinfl')}}</label>
                      <input type="text" name="company[director_pinfl]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="" value="{{old('director_pinfl',isset($company) ? $company->director_pinfl :'')}}" required>
                      @error('director_pinfl')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                      <label for="" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.accountant')}}</label>
                      <input type="text" name="company[accountant]" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" placeholder="" value="{{old('accountant',isset($company) ? $company->accountant :'')}}" required>
                      @error('accountant')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                  </div>
                  <div class="flex justify-end">
                    <button class="mt-10 rounded-lg bg-success-300 px-4 py-3.5 font-semibold text-white">{{__('main.save')}} </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
