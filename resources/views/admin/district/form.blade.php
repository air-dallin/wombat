{{-- Extends layout --}}
@extends('layout.default')
@section('title',__('main.district'))

{{-- Content --}}
@section('content')

  @include('alert')
  <?php
  /*<div class="page-titles">
             <ol class="breadcrumb">
                 <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                 <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($district)?$district->title_ru :'' }}</a></li>
             </ol>
         </div> --*/ ?>
  <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
    <div class="col-12">

      <div class="card">
        <div class="card-body">

          <form method="POST" enctype="multipart/form-data"
                @isset($district)
                  action="{{localeRoute('admin.district.update',$district)}}"
                @else
                  action="{{localeRoute('admin.district.store')}}"
              @endisset
          >
            @csrf
            @isset($district)
              @method('PUT')
            @endisset
            <div class="grid grid-cols-2 gap-6 2xl:grid-cols-2">


              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_uz')}}</label>
                <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="title_uz" value="{{ old('title_uz',isset($district)?$district->title_uz:'') }}" required>
                @error('title_uz')
                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                @enderror
              </div>

              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.city')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="city_id">

                  @foreach($cities as $city)
                    <option value="{{ $city->id }}"
                            @if(isset($district) && $district->city_id==$city->id) selected @endif >{{ $city->title_ru }}
                    </option>
                  @endforeach
                </select>
                @error('city_id')
                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                @enderror
              </div>
            </div>

            <div class="flex justify-end">
              <button type="submit" class="btn mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
            </div>

          </form>

        </div>

      </div>

    </div>
  </div>

@endsection

