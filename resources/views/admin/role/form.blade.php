{{-- Extends layout --}}
@extends('layout.default')


{{-- Content --}}
@section('content')


    <div class="container-fluid">
        @include('alert')
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">role</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($role)?$role->name :'' }}</a></li>
            </ol>
        </div> -*/ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('main.roles')}}</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data"
                            @isset($role)
                              action="{{localeRoute('admin.role.update',$role)}}"
                              @else
                              action="{{localeRoute('admin.role.store')}}"
                            @endisset
                        >
                        @csrf
                        @isset($role)
                            @method('PUT')
                        @endisset


                        <!-- Nav tabs -->
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                               {{-- <li class="nav-item">
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
                                                <input type="text" class="form-control" name="title_ru" value="{{ old('title_ru',isset($role)?$role->title_ru:'') }}" required>
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
                                                <input type="text" class="form-control" name="title_uz" value="{{ old('title_uz',isset($role)?$role->title_uz:'') }}" required>
                                                @error('title_uz')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="tab-pane fade" id="uz">
                                    <div class="pt-4">
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <label>{{__('main.title_en')}}</label>
                                                <input type="text" class="form-control" name="title_en" value="{{ old('title_en',isset($role)?$role->title_en:'') }}" required>
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
    <script>
        $(document).ready(function(){
            $('.remove-image').click(function(){
                id = $(this).data('id');
                obj = $('#image_'+id)
                $.ajax({
                    type: 'post',
                    url: '/admin/photo/destroy/'+id,
                    data: { "_token": "{{ csrf_token() }}"},
                    success: function($response) {
                        console.log($response)
                        if ($response.status) {
                            $(obj).fadeOut();
                        } else {
                            alert('Can`t remove image!')
                        }
                    },
                    error: function(e) {
                        alert('Server error or Internet connection failed!')
                    }
                });
            });
        });

    </script>

@endpush
