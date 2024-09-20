@extends('layouts.register')
@section('title', 'Login')
@section('content')

  <div class="register-bg login-forms">
    <div class="login-signin">
      <h3>{{__('main.login')}}</h3>
      <a href="{{ localeRoute('frontend.register.index') }}">{{__('main.registration')}}</a>
    </div>


    <div class="form-group step-phone">
      <label for="" class="form-label">{{__('main.phone')}}</label>

      <div class="input-group mb-4">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1">+998</span>
        </div>
        <input type="text" id="phone" class="form-control phone" placeholder="( _ _ ) _ _ _ - _ _ - _ _" value="" maxlength="9">
      </div>
      <div class="step-btns">
        <button class="send-sms-code" data-current="step1" data-step="step1" data-next-step="send_sms_code">{{__('main.next')}}</button>
      </div>
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
        <button><a href="#" class="back-step">{{__('main.back')}}</a></button>
        <button class="check_sms_code">{{__('main.login')}}</button>
      </div>
    </div>


  </div>
@endsection

@section('css')
  <style>
    .step-enter-sms {
      display: none;
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

          $('.back-step').click(function () {
              $('.step-enter-sms').css('display', 'none');
              $('.step-phone').css('display', 'block');
              clearInterval(interval);
          });

          $('.check_sms_code').click(function () {
              code = $('#sms-code').val();
              phone = $('#phone').val();
              if (code.length != 4) {
                  alert('{{__('main.code_incorrect')}}');
                  return false;
              }
              $.ajax({
                  type: 'post',
                  url: '/ru/check-sms-code',
                  data: {'_token': _csrf_token, 'code': code, 'phone': '998' + phone},
                  success: function ($response) {

                      if ($response.status) {
                          location.href = '/' + locale + '/profile/companies';
                      } else {
                          alert($response.error);
                      }
                  },
                  error: function (e) {
                      alert('{{__('main.server_error')}}');
                  }
              });

          });
          $('.send-sms-code').click(function (e) {
              e.preventDefault();

              if (sendSmsCodeButtonClicked) return false;

              sendSmsCodeButtonClicked = true

              phone = $('#phone').val();

              if (phone.length == 0) {
                  alert('{{__('main.phone_incorrect')}}');
                  $('#phone').focus();
                  return false;
              }

              $.ajax({
                  type: 'post',
                  url: '/ru/send-sms-code',
                  data: {'_token': _csrf_token, 'phone': '998' + phone},
                  success: function ($response) {
                      if ($response.status) {
                          $('.sms-send-again').css('visibility', 'hidden')
                          currenttime = countdown;
                          startTimer();
                          $('.step-phone').css('display', 'none')
                          $('.step-enter-sms').css('display', 'block');
                          console.log($response)

                          alert('Тестовый смс код: ' + $response.sms)

                      } else {
                          alert($response.error);
                      }
                      sendSmsCodeButtonClicked = false;
                  },
                  error: function (e) {
                      alert('{{__('main.server_error')}}');
                      sendSmsCodeButtonClicked = false;
                  }
              });

          });


          $('.sms-send-again').click(function (e) {
              e.preventDefault();
              phone = $('#phone').val();
              if (phone.length == 0) {
                  alert('{{__('main.phone_incorrect')}}');
                  return false;
              }

              $.ajax({
                  type: 'post',
                  url: '/ru/send-sms-code',
                  data: {'_token': _csrf_token, 'phone': '998' + phone, 'register': 1},
                  success: function ($response) {
                      if ($response.status) {
                          $('.sms-send-again').css('visibility', 'hidden')
                          currenttime = countdown;
                          startTimer();
                      } else {
                          alert($response.error);
                      }
                  },
                  error: function (e) {
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


