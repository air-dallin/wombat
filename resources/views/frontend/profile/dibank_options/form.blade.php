@extends('layouts.profile')
@section('title', __('main.dibank_service'))
@section('content')

    @include('alert-profile')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between">
{{--
                        <h4>{{__('main.dibank_service')}}</h4>
--}}
                        <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="@if(isset($dibankOption)) {{localeRoute('frontend.profile.companies.dibank.update',$dibankOption)}} @else {{localeRoute('frontend.profile.companies.dibank.store')}} @endif" method="POST">
                        @csrf
                        <div class="">
                            <div class="step-1 {{$step1Class}}">
                            {{--@if(!isset($dibankOption) || in_array($dibankOption->account_status,[\App\Services\DibankService::STATUS_WAIT,\App\Services\DibankService::STATUS_CONFIRM]))--}}
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label control-label">{{__('main.firstname')}}</label>

                                            <input type="text" name="dibank[firstname]" class="form-control @error('firstname') is-invalid @enderror" placeholder="{{__('main.firstname')}}" value="{{old('firstname',isset($dibankOption) ? $dibankOption->firstname :'')}}" required>
                                            @error('firstname')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label control-label">{{__('main.surname')}}</label>

                                            <input type="text" name="dibank[lastname]" class="form-control @error('lastname') is-invalid @enderror" placeholder="{{__('main.lastname')}}" value="{{old('lastname',isset($dibankOption) ? $dibankOption->lastname :'')}}" required>
                                            @error('lastname')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label control-label">{{__('main.email')}}</label>

                                            <input type="text" name="dibank[email]" class="form-control @error('email') is-invalid @enderror" placeholder="{{__('main.email')}}" value="{{old('email',isset($dibankOption) ? $dibankOption->email :'')}}" required>
                                            @error('email')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label control-label">{{__('main.phone')}}</label>

                                            <input type="text" name="dibank[phone]" class="form-control @error('phone') is-invalid @enderror" placeholder="{{__('main.phone')}}" value="{{old('phone',isset($dibankOption) ? $dibankOption->phone :'')}}" required>
                                            @error('phone')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group input-group mb-3">
                                            {{--
                                                                                <label for="password" class="form-label control-label">{{__('main.password')}}</label>
                                            --}}
                                            <input type="text" name="dibank[login]" id="login_dibank" class="form-control @error('login') is-invalid @enderror" placeholder="{{__('main.login_label')}}" value="{{old('login',isset($dibankOption) ? $dibankOption->getLogin() :'')}}" required>
                                            @error('login')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group input-group mb-3">
                                            {{--
                                                                                <label for="password" class="form-label control-label">{{__('main.password')}}</label>
                                            --}}
                                            <input type="password" name="dibank[password]" id="password_dibank" class="form-control @error('password') is-invalid @enderror" placeholder="{{__('main.password')}}" value="{{old('password',isset($dibankOption) ? $dibankOption->getPassword() :'')}}" required>
                                            @error('password')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror

                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary check_dibank" type="button"><i class="fa fa-check "></i> {{__('main.check')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                {{--@endif
                                @if(isset($dibankOption) && $dibankOption->account_status==\App\Services\DibankService::STATUS_BIND)--}}

                            </div>
                            <div class="step-2 {{$step2Class}}">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label control-label">{{__('main.serial')}}</label>
                                            <input type="text" name="dibank[serial]" class="form-control @error('serial') is-invalid @enderror">
                                            @error('serial')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label control-label">{{__('main.signature')}}</label>

                                            <input type="text" name="signature" class="form-control @error('signature') is-invalid @enderror">
                                            @error('signature')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="input-group-append mt-5">
                                            <button class="btn btn-outline-secondary sign_dibank" type="button"><i class="fa fa-check "></i> {{__('main.sign')}}</button>
                                        </div>
                                    </div>
                                </div>

                            <hr>
                           {{--} @endif
                            @if(isset($dibankOption) && $dibankOption->account_status==\App\Services\DibankService::STATUS_SIGN) --}}
                            </div>
                            <div class="step-3 {{$step3Class}}">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="" class="form-label control-label">{{__('main.sms')}}</label>
                                            <input type="text" name="sms" class="form-control @error('sms') is-invalid @enderror" placeholder="{{__('main.sms')}}">

                                        </div>
                                    </div>

                                    <div class="col-1">
                                        <div class="input-group-append mt-4">
                                            <button class="btn btn-outline-secondary confirm_dibank" type="button"><i class="fa fa-check "></i> {{__('main.confirm')}}</button>
                                        </div>
                                    </div>
                                </div>
                            {{--@endif--}}
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

@section('js')
    <script>
        var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';

        $(document).ready(function () {
            var clicked = false;

            $('.step-1 input').attr('readonly', {{$step1Class?true:false}});
            $('.step-2 input').attr('readonly', {{$step2Class?true:false}});
            $('.step-3 input').attr('readonly', {{$step3Class?true:false}});

            $('.check_dibank').click(function () {
                var company_id = '{{!empty($company) ? $idOption : 0}}';
                let firstname = $('input[name="dibank[firstname]"]').val();
                if(firstname=='') {
                    alert('{{__('main.enter_value')}}');
                    $('input[name="dibank[firstname]"]').focus();
                    return false;
                }
                let lastname = $('input[name="dibank[lastname]"]').val();
                if(lastname=='') {
                    alert('{{__('main.enter_value')}}');
                    $('input[name="dibank[lastname]"]').focus();
                    return false;
                }
                let email = $('input[name="dibank[email]"]').val();
                if(email=='') {
                    alert('{{__('main.enter_value')}}');
                    $('input[name="dibank[email]"]').focus();
                    return false;
                }
                let phone = $('input[name="dibank[phone]"]').val();
                if(phone=='') {
                    alert('{{__('main.enter_value')}}');
                    $('input[name="dibank[phone]"]').focus();
                    return false;
                }
                let login = $('#login_dibank').val();
                if(login=='') {
                    alert('{{__('main.enter_value')}}');
                    $('#login_dibank').focus();
                    return false;
                }
                let pw = $('#password_dibank').val();
                if(pw=='') {
                    alert('{{__('main.enter_password')}}');
                    $('#password_dibank').focus();
                    return false;
                }
                if(clicked) return false;
                clicked =true;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/companies/dibank-option/check-dibank',
                    data: {'_token': _csrf_token, 'login': login,'company_id':company_id,'password':pw,'firstname':firstname,'lastname':lastname,'email':email,'phone':phone},
                    success: function ($response) {
                        if ($response.status) {
                            alert('{{__('main.success')}}')
                        } else {
                            alert($response.error);
                        }
                        clicked = false;
                    },
                    error: function (e) {
                        alert(e)
                        clicked = false;
                    }
                });
            });
            @if(isset($dibankOption) && $dibankOption->account_status==\App\Services\DibankService::STATUS_BIND)
            $('.sign_dibank').click(function () {
                let method = $('input[name=method]').val();
                if(method=='') {
                    alert('{{__('main.enter_value')}}');
                    $('input[name=method]').focus();
                    return false;
                }
                let serial = $('input[name=serial]').val();
                if(serial=='') {
                    alert('{{__('main.enter_value')}}');
                    $('input[name=serial]').focus();
                    return false;
                }
                /*   let signature = $('input[name=signature]').val();
                   if(signature=='') {
                       alert('{{__('main.enter_value')}}');
                    $('input[name=signature]').focus();
                    return false;
                }*/
                let login = $('#login_dibank').val();
                if(login=='') {
                    alert('{{__('main.enter_value')}}');
                    $('#login_dibank').focus();
                    return false;
                }
                let pw = $('#password_dibank').val();
                if(pw=='') {
                    alert('{{__('main.enter_password')}}');
                    $('#password_dibank').focus();
                    return false;
                }
                if(clicked) return false;
                clicked =true;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/companies/dibank-option/sign-dibank',
                    data: {'_token': _csrf_token, 'login': login,'company_id':company_id,'password':pw,'method':method,'serial':serial/*,'signature':signature*/},
                    success: function ($response) {
                        if ($response.status) {
                            alert('{{__('main.success')}}')
                        } else {
                            alert($response.error);
                        }
                        clicked = false;
                    },
                    error: function (e) {
                        alert(e)
                        clicked = false;
                    }
                });
            });
            @endif
            @if(isset($dibankOption) && $dibankOption->account_status==\App\Services\DibankService::STATUS_SIGN)
            $('.confirm_dibank').click(function () {
                let sms = $('input[name=sms]').val();
                if(sms=='') {
                    alert('{{__('main.enter_value')}}');
                    $('input[name=sms]').focus();
                    return false;
                }
                let login = $('#login_dibank').val();
                if(login=='') {
                    alert('{{__('main.enter_value')}}');
                    $('#login_dibank').focus();
                    return false;
                }
                let pw = $('#password_dibank').val();
                if(pw=='') {
                    alert('{{__('main.enter_password')}}');
                    $('#password_dibank').focus();
                    return false;
                }
                if(clicked) return false;
                clicked =true;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/companies/dibank-option/confirm-dibank',
                    data: {'_token': _csrf_token, 'login': login,'company_id':company_id,'password':pw,'sms':sms},
                    success: function ($response) {
                        if ($response.status) {
                            alert('{{__('main.success')}}')
                        } else {
                            alert($response.error);
                        }
                        clicked = false;
                    },
                    error: function (e) {
                        alert(e)
                        clicked = false;
                    }
                });
            });
            @endif
        });
    </script>

@endsection
