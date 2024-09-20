{{-- Extends layout --}}
@extends('layout.default')


{{-- Content --}}
@section('content')


    @include('alert')


    <div class="container-fluid">
        <?php /*<div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">News</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($news)?$news->getTitle():'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="row">
            <div class="col-12">


                <div class="card">
                    <?php /*<div class="card-header">
                        <h4 class="card-title">Custom Tab 1</h4>
                    </div>  */ ?>
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data"
                              @isset($news)
                              action="{{localeRoute('admin.news.update',$news)}}"
                              @else
                              action="{{localeRoute('admin.news.store')}}"
                            @endisset
                        >
                        @csrf
                        @isset($news)
                            @method('PUT')
                        @endisset


                            <label>{{__('main.status')}}</label>
                            <select class="form-control" name="status">
                                <option value="0" @if(isset($news) && $news->status==0) selected @endif >{{__('main.inactive')}}</option>
                                <option value="1" @if(isset($news) && $news->status==1) selected @endif >{{__('main.active')}}</option>
                            </select>

                        <!-- Nav tabs -->
                            <div class="custom-tab-1">
                                <ul class="nav nav-tabs">
                                   {{-- <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#ru">RU</a>
                                    </li><li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#en">EN</a>
                                    </li>
--}}
                                    <li class="nav-item active">
                                        <a class="nav-link" data-toggle="tab" href="#uz">UZ</a>
                                    </li>

                                </ul>
                                <div class="tab-content">
                                   {{-- <div class="tab-pane fade show active" id="ru" role="tabpanel">
                                        <div class="pt-4">
                                            <div class="basic-form">
                                                <div class="form-group">
                                                    <label>{{__('main.title_ru')}}</label>
                                                    <input type="text" class="form-control" name="title_ru" value="{{ old('title_ru',isset($news)?$news->title_ru:'') }}" required>
                                                    @error('title_ru')
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('main.text_ru')}}</label>
                                                    <textarea class="form-control summernote" name="text_ru" rows="6" required>{{ old('text_ru',isset($news)?$news->text_ru:'') }}</textarea>
                                                    @error('text_ru')
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label>{{__('main.meta_title_ru')}}</label>
                                                    <input type="text" class="form-control" name="meta_title_ru" value="{{ old('meta_title_ru',isset($news)?$news->meta_title_ru:'') }}" required>
                                                    @error('meta_title_ru')
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('main.meta_keywords_ru')}}</label>
                                                    <input type="text" class="form-control" name="meta_keywords_ru" value="{{ old('meta_keywords_ru',isset($news)?$news->meta_keywords_ru:'') }}" required>
                                                    @error('meta_keywords_ru')
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('main.meta_description_ru')}}</label>
                                                    <textarea class="form-control" name="meta_description_ru" rows="6" required>{{ old('meta_description_ru',isset($news)?$news->meta_description_ru:'') }}</textarea>
                                                    @error('meta_description_ru')
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
                                            <div class="pt-4">
                                                <div class="basic-form">
                                                    <div class="form-group">
                                                        <label>{{__('main.title_uz')}}</label>
                                                        <input type="text" class="form-control" name="title_uz" value="{{ old('title_uz',isset($news)?$news->title_uz:'') }}" required>
                                                        @error('title_uz')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{__('main.text_uz')}}</label>
                                                        <?=$news->text_uz?>ggdfgfg
                                                        <textarea class="form-control summernote" name="text_uz" rows="6" required>{{ old('text_uz',isset($news)?$news->text_uz:'') }}</textarea>
                                                        @error('text_uz')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label>{{__('main.meta_title_uz')}}</label>
                                                        <input type="text" class="form-control" name="meta_title_uz" value="{{ old('meta_title_uz',isset($news)?$news->meta_title_uz:'') }}" required>
                                                        @error('meta_title_uz')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{__('main.meta_keywords_uz')}}</label>
                                                        <input type="text" class="form-control" name="meta_keywords_uz" value="{{ old('meta_keywords_uz',isset($news)?$news->meta_keywords_uz:'') }}" required>
                                                        @error('meta_keywords_uz')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{__('main.meta_description_uz')}}</label>
                                                        <textarea class="form-control" name="meta_description_uz" rows="6" required>{{ old('meta_description_uz',isset($news)?$news->meta_description_uz:'') }}</textarea>
                                                        @error('meta_description_uz')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="tab-pane fade" id="en">
                                        <div class="pt-4">
                                            <div class="pt-4">
                                                <div class="basic-form">
                                                    <div class="form-group">
                                                        <label>{{__('main.title_en')}}</label>
                                                        <input type="text" class="form-control" name="title_en" value="{{ old('title_en',isset($news)?$news->title_en:'') }}" required>
                                                        @error('title_en')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{__('main.text_en')}}</label>
                                                        <textarea class="form-control summernote" name="text_en" rows="6" required>{{ old('text_en',isset($news)?$news->text_en:'') }}</textarea>
                                                        @error('text_en')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label>{{__('main.meta_title_en')}}</label>
                                                        <input type="text" class="form-control" name="meta_title_en" value="{{ old('meta_title_en',isset($news)?$news->meta_title_en:'') }}" required>
                                                        @error('meta_title_en')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{__('main.meta_keywords_en')}}</label>
                                                        <input type="text" class="form-control" name="meta_keywords_en" value="{{ old('meta_keywords_en',isset($news)?$news->meta_keywords_en:'') }}" required>
                                                        @error('meta_keywords_en')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{__('main.meta_description_en')}}</label>
                                                        <textarea class="form-control" name="meta_description_en" rows="6" required>{{ old('meta_description_en',isset($news)?$news->meta_description_en:'') }}</textarea>
                                                        @error('meta_description_en')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>--}}
                                </div>
                            </div>
                            <hr>
                            @if(isset($news) && $news->images)
                                @foreach($news->images as $image)
                                    <div class="preview-image" id="image_{{$image->id}}">
                                        <div class="remove-image" data-id="{{$image->id}}">x</div>
                                        <img src="{{ Storage::url($image->image) }}" width="200px" class="image-preview"/>
                                    </div>
                                @endforeach
                            @endif

                            <div class="clearfix"></div>

                            <div class="form-group ">
                                <label>{{__('main.image')}}</label>
                                <input type="file" class="form-control" name="image[]" <?php /*value="{{ old('image',isset($news)?$news->image:'') }}" */ ?> multiple>
                            </div>

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

@push('scripts')
    <link href="{{asset('/css/image.css')}}" rel="stylesheet">
    <script src="{{asset('/js/image.js')}}"></script>

    <link href="{{asset('/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendor/summernote/summernote.css')}}" rel="stylesheet">
    <script src="{{asset('/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('/vendor/summernote/js/summernote.min.js')}}"></script>
    <script src="{{asset('/js/plugins-init/summernote-init.js')}}"></script>

@endpush
