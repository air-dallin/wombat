@extends('layouts.register')
@section('title', 'Register')
@section('content')

    <div class="register-bg">
        <div class="login-signin">
            <h3>{{__('main.register')}}</h3>
            <a href="{{ localeRoute('frontend.login') }}">{{__('main.login')}}</a>
        </div>
        <div class="form-group step-phone">
            <label for="" class="form-label">{{__('main.phone')}}</label>

            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">+998</span>
                </div>
                <input type="text" id="phone" class="form-control phone" placeholder="( _ _ ) _ _ _ - _ _ - _ _" value="" maxlength="9">
            </div>
            <div class="form-group">
                <label for="" class="form-label">{{__('main.inn')}}</label>
                <input type="text" id="inn" class="form-control inn" value="">
            </div>
            <div class="step-btns">
                <button class="send-sms-code" data-current="step1" data-step="step1" data-next-step="send_sms_code">{{__('main.next')}}</button>
            </div>
        </div>
        <div class="form-group step-preloader">
{{--
            <label for="" class="form-label">{{__('main.wait')}}</label>
--}}
            <img src="/images/preloder.gif" width="">
        </div>

        <div class="step-enter-sms">
            <h4>{{__('main.enter_code')}}</h4>
                <div class="form-group d-flex">
                    <input type="text" class="form-control sms-code" id="sms-code" maxlength="4" required>
                </div>
            <div>{{__('main.we_sended_code')}}</div>

            <div>{{__('main.get_new_code_after')}} <span id="counter">60</span> {{__('main.sec')}}</div>

            <a href="#" class="sms-send-again">{{__('main.send_code_again')}}</a>

            <div class="step-btns">
                <a href="#" class="back-step">{{__('main.back')}}</a>
                <button class="check_sms_code">{{__('main.confirm_phone')}}</button>
            </div>
        </div>
    </div>

@endsection
@section('css')
<style>

    .step-preloader{
        display:flex;
        align-items:center;
        justify-content: center;
    }

    .step-enter-sms{
        display:none;
    }
    .sms-send-again {
        visibility: hidden;
    }
</style>
@endsection
@section('js')
    <script>

        $(document).ready(function () {
            var interval = 0;
            var countdown = 60;
            var sendSmsCodeButtonClicked = false;

            $('.step-preloader').css('display','none');

            $('.back-step').click(function(){
                $('.step-enter-sms').css('display','none');
                $('.step-phone').css('display','block');
                clearInterval(interval);
            });

            $('.check_sms_code').click(function(){
                code = $('#sms-code').val();
                phone = $('#phone').val();
                if(code.length!=4){
                    alert('{{__('main.code_incorrect')}}');
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: '/ru/check-sms-code',
                    data: { '_token': _csrf_token,'code':code ,'phone':'998'+phone},
                    success: function($response) {
                        if ($response.status) {
                            location.href = '/'+locale +'/profile/companies';
                        } else {
                            alert($response.error);
                        }
                    },
                    error: function(e) {
                        alert('{{__('main.server_error')}}');
                    }
                });

            });
            $('.send-sms-code').click(function(e){
                e.preventDefault();

                if(sendSmsCodeButtonClicked) return false;

                sendSmsCodeButtonClicked = true

                phone = $('#phone').val();
                inn = $('#inn').val();
                pw = $('#password').val();

                if(phone.length==0){
                    alert('{{__('main.phone_incorrect')}}');
                    $('#phone').focus();
                    return false;
                }
                if(inn.length==0){
                    alert('{{__('main.inn_incorrect')}}');
                    $('#inn').focus();
                    return false;
                }

                $('.step-phone').css('display','none')
                $('.step-preloader').css('display','flex');

                $.ajax({
                    type: 'post',
                    url: '/ru/send-sms-code',
                    data: { '_token': _csrf_token,'phone':'998'+phone,'inn':inn,'password':pw,'register':1},
                    success: function($response) {
                        if ($response.status) {
                            $('.sms-send-again').css('visibility', 'hidden')
                            currenttime = countdown;
                            startTimer();
                            // $('.step-phone').css('display','none')
                            $('.step-preloader').css('display','none');
                            $('.step-enter-sms').css('display','block');

                            console.log($response)
                            alert('Тестовый смс код: '+$response.sms)

                        } else {
                            alert($response.error );
                        }
                        sendSmsCodeButtonClicked = false;
                    },
                    error: function(e) {
                        alert('{{__('main.server_error')}}');
                        sendSmsCodeButtonClicked = false;

                    }
                });

            });


            $('.sms-send-again').click(function(e){
                e.preventDefault();
                phone = $('#phone').val();
                if(phone.length==0){
                    alert('{{__('main.phone_incorrect')}}');
                    return false;
                }

                $.ajax({
                    type: 'post',
                    url: '/ru/send-sms-code',
                    data: { '_token': _csrf_token,'phone':'998'+phone},
                    success: function($response) {
                        if ($response.status) {
                            $('.sms-send-again').css('visibility', 'hidden')
                            currenttime = countdown;
                            startTimer();
                        } else {
                            alert($response.error);
                        }
                    },
                    error: function(e) {
                        alert('{{__('main.server_error')}}');
                    }
                });

            });

            var currenttime = countdown;

            function timer() {
                currenttime -= 1;
                if (currenttime <= 0) {
                    clearInterval(interval);
                    $('.sms-send-again').css('visibility', 'visible')
                }
                $('#counter').text(currenttime);
            }

            function startTimer() {
                interval = setInterval(timer, 1000);

            }
        })

    </script>
@endsection


