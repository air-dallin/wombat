@extends('layouts.register')
@section('title', 'Login')
@section('css')
  <style>
    .step-enter-sms {
      display: none;
    }
    .sms-send-again {
      visibility: hidden;
    }
    .focus\:border-success-300:focus {
      border-color: orange !important;
    }
  </style>
@endsection
@section('content')

  <section class="bg-white dark:bg-darkblack-500">
    <div class="flex flex-col lg:flex-row justify-between min-h-screen">
      <!-- Left -->
      <div class="lg:w-1/2 px-5 xl:pl-12 pt-10">
        <header>
          <a href="/" class="flex justify-center">
            <img src="{{ asset('profile/assets/images/logo/logo.png') }}" class="block dark:hidden" alt="Logo"/>
            <img src="{{ asset('profile/assets/images/logo/logo-white.svg') }}" class="hidden dark:block" alt="Logo"/>
          </a>
        </header>
        <div class="max-w-[450px] m-auto pt-24 pb-16">
          <header class="text-center mb-8">
            <h2
                class="text-bgray-900 dark:text-white text-4xl font-semibold font-poppins mb-2"
            >
              {{ __('main.login') }} в Wombat
            </h2>
            <p class="font-urbanis text-base font-medium text-bgray-600 dark:text-bgray-50">
              {{ __('main.send_spend_and_save_smarter') }}
            </p>
          </header>
{{--          <form action="">--}}
          <div class="form-group step-phone">
            <div class="mb-4">
              <label class="bg-white dark:bg-darkblack-500 text-base text-bgray-600" style="margin-bottom: 10px; display:block;">{{__('main.phone')}}</label>
              <div class="flex">
                <div id="country" class="rounded-lg border-0 bg-bgray-50 dark:bg-darkblack-500 dark:text-white  flex items-center p-2" style="border-top-right-radius: 0; border-bottom-right-radius: 0">+998</div>
                <input
                    type="text"
                    id="phone"
                    class="text-bgray-800 text-base border border-bgray-300 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base"
                    placeholder="( _ _ ) _ _ _ - _ _ - _ _"
                    value=""
                    maxlength="9"
                    style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-left: 0"
                />
              </div>
            </div>
            <div class="step-btns">
              <button style="background: orange" class="send-sms-code py-3.5 flex items-center justify-center text-white font-bold rounded-lg w-full" data-current="step1" data-step="step1" data-next-step="send_sms_code">{{__('main.next')}}</button>
            </div>
          </div>

          <div class="step-enter-sms">
            <h4 class="bg-white dark:bg-darkblack-500 text-base text-bgray-600" style="margin-bottom: 10px; display:block;">{{__('main.enter_code')}}</h4>
            <div class="form-group d-flex">
              <input type="text" class="sms-code text-bgray-800 text-base border border-bgray-300 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base" id="sms-code" maxlength="4" required>
            </div>
            <div class="mt-2">{{__('main.we_sended_code')}}</div>

            <div>{{__('main.get_new_code_after')}} <span id="counter">60</span> {{__('main.sec')}}</div>

            <a href="#" class="sms-send-again mt-1" style="color: blue">{{__('main.send_code_again')}}</a>

            <div class="step-btns flex items-center justify-between pt-5 dark:border-darkblack-400">
              <a href="#" class="back-step flex items-center justify-center gap-1.5 rounded-lg px-4 py-2.5 text-sm font-semibold" style="border: 1px solid #718096; color: #718096">
                <span>{{__('main.back')}}</span>
              </a>

              <button style="background: orange;" class="check_sms_code flex items-center justify-center gap-1.5 rounded-lg px-4 py-2.5 text-sm font-semibold text-white " >
                <span>{{__('main.login')}}</span>
              </button>
            </div>


          </div>








{{--          </form>--}}
          <p class="text-center text-bgray-900 dark:text-bgray-50 text-base font-medium pt-7">
            {{ __('Нет аккаунта') }}?
            <a href="{{ localeRoute('frontend.register.index') }}" class="font-semibold underline">{{ __('main.register') }}</a>
          </p>
          <p class="text-bgray-600 dark:text-white text-center text-sm mt-6">
            @ 2024 Wombat. {{ __('main.all_right_reserved') }}.
          </p>
        </div>
      </div>
      <!-- Right -->
      <div class="lg:w-1/2 lg:block hidden bg-[#F6FAFF] dark:bg-darkblack-600 p-20 relative">
        <ul>
          <li class="absolute top-10 left-8">
            <img src="{{ asset('profile/assets/images/shapes/square.svg') }}" alt=""/>
          </li>
          <li class="absolute right-12 top-14">
            <img src="{{ asset('profile/assets/images/shapes/vline.svg') }}" alt=""/>
          </li>
          <li class="absolute bottom-7 left-8">
            <img src="{{ asset('profile/assets/images/shapes/dotted.svg') }}" alt=""/>
          </li>
        </ul>
        <div class="">
          <img
              src="{{ asset('profile/assets/images/illustration/signin.svg') }}
            "
              alt=""
          />
        </div>
        <div>
          <div class="text-center max-w-lg px-1.5 m-auto">
            <h3
                class="text-bgray-900 dark:text-white font-semibold font-popins text-4xl mb-4"
            >
              {{ __('main.login_citate') }}
            </h3>
            <p class="text-bgray-600 dark:text-bgray-50 text-sm font-medium">
              {{ __('main.login_citate_text') }}
            </p>
          </div>
        </div>
      </div>
    </div>



  </section>
@endsection


@section('js')
  <script>
      var loc = '{{strpos(request()->url(),'wom.loc')>0?1:0}}'

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
                          if(loc>0) $('#sms-code').val($response.sms)

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


