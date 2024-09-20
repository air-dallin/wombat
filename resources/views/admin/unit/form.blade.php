{{-- Extends layout --}}
@extends('layout.default')
@section('title',__('main.unit'))

{{-- Content --}}
@section('content')

        @include('alert')
        <?php
        /*<div class="page-titles">
                   <ol class="breadcrumb">
                       <li class="breadcrumb-item"><a href="javascript:void(0)">Oldunit</a></li>
                       <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($unit)?$unit->getTitle():'' }}</a></li>
                   </ol>
               </div> */ ?>
        <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data"
                              @isset($unit)
                                  action="{{localeRoute('admin.unit.update',$unit)}}"
                              @else
                                  action="{{localeRoute('admin.unit.store')}}"
                                @endisset
                        >
                            @csrf
                            @isset($unit)
                                @method('PUT')
                            @endisset

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
                                                    <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white" name="title_ru" value="{{ old('title_ru',isset($unit)?$unit->title_ru:'') }}" required>
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
                                                    <input type="text" class="form-control" name="title_uz" value="{{ old('title_uz',isset($unit)?$unit->title_uz:'') }}">
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
                                                    <input type="text" class="form-control" name="title_en" value="{{ old('title_en',isset($unit)?$unit->title_en:'') }}">
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


                            <div class="flex justify-end">
                                <button type="submit" class="mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
