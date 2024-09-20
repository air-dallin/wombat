<?php

use App\Models\User;

?>
@extends('layout.default')
@section('title')
  @isset($user)
    @if ($user->role==User::ROLE_CLIENT)
      {{__('main.client')}}
    @elseif($user->role==User::ROLE_MODERATOR)
      {{__('main.employee')}}
    @endif
  @else
    {{__('main.create_employee')}}
  @endif
@endsection
@section('content')
  @include('alert')
  <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="POST" id="user" enctype="multipart/form-data"
                @isset($user)
                  action="{{localeRoute('admin.user.update',$user)}}"
                @else
                  action="{{localeRoute('admin.user.store')}}"
              @endisset
          >
            @csrf
            @isset($user)
              @method('PUT')
            @endisset
            <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">

                <div class="flex flex-col gap-2">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.firstname')}}</label>
                    <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="firstname" value="{{ old('firstname',isset($user)?$user->info->firstname:'') }}" required>
                    @error('firstname')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.lastname')}}</label>
                    <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="lastname" value="{{ old('lastname',isset($user)?$user->info->lastname:'') }}" required>
                    @error('lastname')
                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                </div>


              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.phone')}}</label> <?php
                                                    // =isset($user) && $user->phone_verified_at!=null ? '<span class="badge badge-success">Подтвержден</span>' :'<span class="badge badge-danger">Не подтвержден</span>' ?>
                <input type="number" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="phone" value="{{ old('phone',isset($user)?$user->phone:'') }}" required>
                @error('phone')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.password')}}</label> <?php
                                                    // =isset($user) && $user->phone_verified_at!=null ? '<span class="badge badge-success">Подтвержден</span>' :'<span class="badge badge-danger">Не подтвержден</span>' ?>
                <input type="password" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="password" value="{{ old('password',isset($user)?$user->password:'') }}" required>
                @error('password')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.status')}}</label>
                <select class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="status">
                  <option value="0" @if(isset($user) && $user->status==0) selected @endif >Отключен</option>
                  <option value="1" @if(isset($user) && $user->status==1) selected @endif >Активен</option>
                </select>
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

@push('scripts')
  <link href="{{asset('/css/image.css')}}" rel="stylesheet">
  <script src="{{asset('/js/image.js')}}"></script>

  <script>
      var hasRequired = false;
      var isNew = {{isset($user)?0:1}};

      $('.regions').change(function () {
          region_id = $(this).val();
          $.ajax({
              type: 'post',
              url: '/ru/admin/city/get-cities',
              data: {'_token': _csrf_token, 'region_id': region_id},
              success: function ($response) {
                  if ($response.status) {
                      $('#cities').html($response.data);
                  } else {
                      alert('Can`t get cities!');
                  }
              },
              error: function (e) {
                  alert('Server error or Internet connection failed!')
              }
          });
      });

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
