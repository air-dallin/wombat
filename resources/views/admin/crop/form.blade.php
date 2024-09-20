{{-- Extends layout --}}
@extends('layout.default')


{{-- Content --}}
@section('content')


    <div class="container-fluid">
        @include('alert')
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Region</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($region)?$region->name :'' }}</a></li>
            </ol>
        </div> -*/ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('main.crop')}}</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data"
                            @isset($crop)
                              action="{{localeRoute('admin.crop.update',$crop)}}"
                              @else
                              action="{{localeRoute('admin.crop.store')}}"
                            @endisset
                        >
                        @csrf
                        @isset($crop)
                            @method('PUT')
                        @endisset


                        <!-- Nav tabs -->
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                {{--<li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#ru">RU</a>
                                </li> <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#en">En</a>
                                </li>--}}
                                <li class="nav-item active">
                                    <a class="nav-link" data-toggle="tab" href="#uz">UZ</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                {{--<div class="tab-pane fade show active" id="ru" role="tabpanel">
                                    <div class="pt-4">
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <label>{{__('main.title_ru')}}</label>
                                                <input type="text" class="form-control" name="title_ru" value="{{ old('title_ru',isset($crop)?$crop->title_ru:'') }}" required>
                                                @error('title_ru')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}
                                <div class="tab-pane fade show active" id="uz">
                                    <div class="pt-4">
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <label>{{__('main.title_uz')}}</label>
                                                <input type="text" class="form-control" name="title_uz" value="{{ old('title_uz',isset($crop)?$crop->title_uz:'') }}" required>
                                                @error('title_uz')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="tab-pane fade" id="en">
                                    <div class="pt-4">
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <label>{{__('main.title_en')}}</label>
                                                <input type="text" class="form-control" name="title_en" value="{{ old('title_en',isset($crop)?$crop->title_en:'') }}" required>
                                                @error('title_en')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}

                            </div>
                        </div>
                        <hr>

                        <?php /*<div class="form-group col-6">
                            <label class="form-label">{{__('main.region')}}</label>
                            <select class="form-control" name="region_id">

                                @foreach($regions as $_region)
                                    <option value="{{ $_region->id }}"
                                            @if(isset($region) && $region->region_id==$_region->id) selected @endif >{{ $_region->title_ru }}
                                    </option>
                                @endforeach
                            </select>
                            @error('region_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div> */ ?>


                        <div class="form-group">
                            <button type="submit" class="btn btn-success">{{__('main.save')}}</button>
                        </div>

                        </form>

                    </div>

                </div>





            </div>
        </div>
    </div>

@endsection
