{{-- Extends layout --}}
@extends('layout.default')
@section('title',__('main.ikpu'))

{{-- Content --}}
@section('content')
  @include('alert')
  <?php
  /*<div class="page-titles">
             <ol class="breadcrumb">
                 <li class="breadcrumb-item"><a href="javascript:void(0)">Oldikpu</a></li>
                 <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($ikpu)?$ikpu->getTitle():'' }}</a></li>
             </ol>
         </div> */ ?>
  <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
    <div class="col-12">

      <div class="card">
        <div class="card-body">

          <form method="POST" enctype="multipart/form-data"
                @isset($ikpu)
                  action="{{localeRoute('admin.ikpu.update',$ikpu)}}"
                @else
                  action="{{localeRoute('admin.ikpu.store')}}"
              @endisset
          >
            @csrf
            @isset($ikpu)
              @method('PUT')
            @endisset

            <div class="flex flex-col mb-8 gap-2">
              <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.code')}}</label>
              <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="code" value="{{ old('code',isset($ikpu)?$ikpu->code:'') }}" required>
              @error('code')
              <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
              @enderror
            </div>

            <!-- Nav tabs -->
            <div class="flex flex-col gap-2">
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
                <div class="tab-pane fade  show active" id="ru" role="tabpanel">
                  <div class="pt-4">
                    <div class="basic-form">
                      <div class="flex flex-col gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_ru')}}</label>
                        <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="title_ru" value="{{ old('title_ru',isset($ikpu)?$ikpu->title_ru:'') }}" required>
                        @error('title_ru')
                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="uz">
                  <div class="pt-4">
                    <div class="basic-form">
                      <div class="flex flex-col gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_uz')}}</label>
                        <input type="text" class="form-control" name="title_uz" value="{{ old('title_uz',isset($ikpu)?$ikpu->title_uz:'') }}">
                        @error('title_uz')
                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="en">
                  <div class="pt-4">
                    <div class="basic-form">
                      <div class="flex flex-col gap-2">
                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_en')}}</label>
                        <input type="text" class="form-control" name="title_en" value="{{ old('title_en',isset($ikpu)?$ikpu->title_en:'') }}">
                        @error('title_en')
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

              <div class="flex flex-col mt-5 gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="status">
                  <option value="0" @if(isset($ikpu) && $ikpu->status==0) selected @endif >{{__('main.inactive')}}</option>
                  <option value="1" @if(isset($ikpu) && $ikpu->status==1) selected @endif >{{__('main.active')}}</option>
                </select>
              </div>

            <div class="flex justify-end">
              <button type="submit" class="mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <link href="{{asset('/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
  <script src="{{asset('/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
@endpush
