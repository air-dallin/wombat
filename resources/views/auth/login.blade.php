@extends('layouts.register')
@section('title', __('main.login'))

@section('content')
  <section class="bg-white dark:bg-darkblack-500">
    <div class="flex flex-col lg:flex-row justify-center min-h-screen">
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
          </header>
          <form action="{{ localeRoute('admin.login') }}" method="POST">
            @csrf
            <div class="mb-4">
              <label class="bg-white dark:bg-darkblack-500 text-base text-bgray-600" style="margin-bottom: 10px; display:block;">{{__('main.phone')}}</label>
              <div class="flex">
                <div class="rounded-lg border-0 bg-bgray-50 dark:bg-darkblack-500 dark:text-white  flex items-center p-2" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 4H9L11 9L8.5 10.5C9.57096 12.6715 11.3285 14.429 13.5 15.5L15 13L20 15V19C20 19.5304 19.7893 20.0391 19.4142 20.4142C19.0391 20.7893 18.5304 21 18 21C14.0993 20.763 10.4202 19.1065 7.65683 16.3432C4.8935 13.5798 3.23705 9.90074 3 6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </div>
                <input
                    type="text"
                    name="phone"
                    class="@error('phone') is-invalid @enderror text-bgray-800 text-base border border-bgray-300 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white h-14 w-full focus:border-orange-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base"
                    value="{{ old('phone') }}"
                    style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-left: 0"
                    autofocus
                    autocomplete
                />
              </div>
              @error('phone')
              <span class="invalid-feedback" role="alert">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
              <label class="bg-white dark:bg-darkblack-500 text-base text-bgray-600" style="margin-bottom: 10px; display:block;">{{__('main.password')}}</label>
              <div class="flex">
                <div class="rounded-lg border-0 bg-bgray-50 dark:bg-darkblack-500 dark:text-white  flex items-center p-2" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 8H8M16 8C18.2091 8 20 9.79086 20 12V18C20 20.2091 18.2091 22 16 22H8C5.79086 22 4 20.2091 4 18V12C4 9.79086 5.79086 8 8 8M16 8V6C16 3.79086 14.2091 2 12 2C9.79086 2 8 3.79086 8 6V8M14 15C14 16.1046 13.1046 17 12 17C10.8954 17 10 16.1046 10 15C10 13.8954 10.8954 13 12 13C13.1046 13 14 13.8954 14 15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </div>
                <input
                    type="password"
                    name="password"
                    class="@error('password') is-invalid @enderror text-bgray-800 text-base border border-bgray-300 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white h-14 w-full focus:border-orange-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base"
                    value=""
                    autocomplete="current-password"
                    style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-left: 0"
                />
              </div>
              @error('password')<span class="invalid-feedback" role="alert">{{ $message }} </span>@enderror
            </div>
            <div class="flex justify-between mb-7">
              <div class="flex items-center space-x-3">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="w-5 h-5 dark:bg-darkblack-500 focus:ring-transparent rounded-full border border-bgray-300 " style="color: orange">
                <label for="remember" class="text-bgray-600 dark:text-white text-base font-semibold">{{ __('main.remember_me') }}</label>
              </div>
            </div>
            <button type="submit" style="background: orange" class="py-3.5 flex items-center justify-center text-white font-bold rounded-lg w-full">{{__('main.signin')}}</button>
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection
