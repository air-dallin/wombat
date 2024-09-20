@extends('layouts.profile')
@section('title', __('main.users_create'))
@section('content')

    @include('alert-profile')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between">
                        <h4>{{__('main.create_user')}}</h4>
                        <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="@if(isset($user)) {{localeRoute('frontend.profile.users.update',$user)}} @else {{localeRoute('frontend.profile.users.store')}} @endif" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="" class="form-label control-label">{{__('main.name')}}</label>
                                    <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" placeholder="" value="{{old('firstname',isset($user) ? $user->info->firstname :'')}}">

                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label control-label">{{__('main.middlename')}}</label>
                                    <input type="text" name="middlename" class="form-control @error('firstname') is-invalid @enderror" placeholder="" value="{{old('middlename',isset($user) ? $user->info->middlename :'')}}">
                                    @error('middlename')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">{{__('main.lastname')}}</label>
                                    <input type="text" name="lastname" class="form-control" placeholder="" value="{{old('lastname',isset($user) ? $user->info->lastname :'')}}">
                                </div>


                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="" class="form-label">{{__('main.birthdate')}}</label>
                                    <input type="date" name="bithdate" class="form-control" value="{{old('birthdate',isset($user) ? date('Y-m-d',strtotime($user->info->bithdate)) :'')}}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{__('main.gender')}}</label>
                                    <select class="select2-regions" name="gender" style="width: 100%;">
                                        <option value="1">{{__('main.man')}}</option>
                                        <option value="2">{{__('main.woman')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="form-label">{{__('main.phone')}}</label>
                                    <input type="text" name="phone" class="form-control phone-mask"  placeholder="(__) ___-__-__" value="{{old('phone',isset($user) ? $user->phone :'')}}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{__('main.region')}}</label>
                                    <select class="select2-regions regions" name="region_id" style="width: 100%;">
                                        @isset($regions)
                                            <option value="0">{{__('main.choice_region')}}</option>
                                            @foreach($regions as $region)
                                                <option value="{{$region->id}}" @if(isset($user->info) && $user->info->region_id==$region->id) selected @endif >{{$region->title_ru}}</option>
                                            @endforeach
                                        @endisset
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{__('main.city')}}</label>
                                    <select class="select2-regions" name="city_id" id="cities" style="width: 100%;">
                                        @isset($cities)
                                            <option value="0">{{__('main.choice_city')}}</option>
                                            @foreach($cities as $city)
                                                <option value="{{$city->id}}"
                                                        @if(isset($user->info) && $user->info->city_id==$city->id) selected @endif >{{$city->title_ru}}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{__('main.image')}}</label>
                                    {{--                            <img class="img-thumbnail" width="128px" src="{{ isset($user->image) ? \Illuminate\Support\Facades\Storage::url($user->image->small()) : asset('frontend/assets/img/profile-user.png') }}">--}}
                                    <input type="file" name="image" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn-create">{{__('main.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <link href="{{asset('/css/image.css')}}" rel="stylesheet">
    <script src="{{asset('/js/image.js')}}"></script>

    <script>
        var hasRequired = false;
        var isNew = {{isset($user)?0:1}};

        $('.btn-submit').click(function () {
            $('.invalid-feedback').css('display', 'none');
            var hasRequired = false;
            $('.required').each(function () {
                if ($(this).val() == '') {
                    $('.nav-link[href="#' + $(this).data('tab') + '"]').click();
                    $(this).parent().find('.invalid-feedback').css('display', 'block');
                    hasRequired = true;
                    $(this).focus();
                    return false;
                }
            });
            if (hasRequired) return false;

            if (isNew) {
                if ($('input#password').val() == '') {
                    $('.nav-link[href="#password"]').click();
                    $('input#password').parent().find('.invalid-feedback').css('display', 'block');
                    $('input#password').focus();
                    hasRequired = true;
                }
            }
            if (hasRequired) return false;
            return true;
        });

        $('form#user').submit(function (e) {
            if (hasRequired) return false;
            return true;
        });

    </script>

@endpush
