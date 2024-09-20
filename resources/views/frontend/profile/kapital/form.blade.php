@extends('layouts.profile')
@section('title', __('main.kapital_bank_service'))
@section('content')

  @include('alert-profile')
  <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="{{localeRoute('frontend.profile.companies.kapital.update',$company)}}" method="POST">
            @csrf
            <div class="">
              <div class="row">
                <div class="col-6">
                  <input type="hidden" name="inn" id="inn" value="{{$company->inn}}">
                  <div class="grid grid-cols-1 gap-6 2xl:grid-cols-4">
                    <div class="flex flex-col gap-2" >
                      <label for="login" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.email')}}</label>
                      <input type="text" name="login" id="login" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white    @error('login') is-invalid @enderror" placeholder="{{__('main.login')}}" value="{{old('login',isset($company->kapital) ? $company->kapital->getLogin() :'')}}" required>
                      @error('login')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                    <div class="flex flex-col gap-2" >
                      <label for="password" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.password')}}</label>
                      <input type="password" name="password" id="password" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white    @error('password') is-invalid @enderror" placeholder="{{__('main.password')}}" value="{{old('password',isset($company->kapital) ? $company->kapital->getPassword() :'')}}" required>
                      @error('password')
                      <small class="invalid-feedback"> {{ $message }} </small>
                      @enderror
                    </div>
                      <div class="flex flex-col gap-2" >
                          <label for="date_from" class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.date_register_kapital')}}</label>
                          <input type="date" name="date_from" id="date_from" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white    @error('date_from') is-invalid @enderror" placeholder="{{__('main.date_register_kapital')}}" value="{{old('date_from',isset($company->kapital) ? $company->kapital->date_from :'')}}" required>
                          @error('date_from')
                          <small class="invalid-feedback"> {{ $message }} </small>
                          @enderror
                      </div>
                    <div class="flex flex-col gap-2" >
                      <div class="input-group-append">
                        <button class="mt-8 rounded-lg px-4 py-3.5 font-semibold text-white check_didox" type="button" style="width: 100%; background: #868686 !important;"><i class="fa fa-check "></i> {{__('main.check')}}</button>
                      </div>
                    </div>
                    <div class="flex flex-col gap-2" >
                      <button type="submit" class="mt-8 rounded-lg px-4 py-3.5 font-semibold text-white btn-create" style="background: orange">{{__('main.save')}}</button>
                    </div>
                  </div>
                </div>
              </div>
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

          $('.check_didox').click(function () {
              let inn = $('#inn').val();
              if (inn == '') {
                  alert('{{__('main.enter_inn')}}');
                  $('#inn').focus();
                  return false;
              }
              let login = $('#login').val();
              if (login == '') {
                  alert('{{__('main.enter_value')}}');
                  $('#login').focus();
                  return false;
              }
              let pw = $('#password').val();
              if (pw == '') {
                  alert('{{__('main.enter_password')}}');
                  $('#password').focus();
                  return false;
              }
              let date_from = $('#date_from').val();
              if (date_from == '') {
                  alert('{{__('main.enter_date')}}');
                  $('#date_from').focus();
                  return false;
              }
              if (clicked) return false;
              clicked = true;
              $.ajax({
                  type: 'post',
                  url: '/ru/profile/companies/kapital/check-kapital',
                  data: {'_token': _csrf_token, 'inn': inn, 'user_id': user_id, 'login': login, 'pw': pw,'date_from':date_from},
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
      });
  </script>

@endsection
