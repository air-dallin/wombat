<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @yield('title') | Wombat </title>
    <link rel="stylesheet" href="{{ asset('profile/assets/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('profile/assets/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('profile/assets/css/output.css') }}" />
    <link rel="stylesheet" href="{{ asset('profile/assets/css/style.css') }}" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >--}}
{{--    <link rel="stylesheet" href="{{ asset('frontend/assets/css/app.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('frontend/assets/css/media.css') }}">--}}
    @yield('css')
    <script>
        var locale = '{{app()->getLocale() }}';
        var _csrf_token = '{{@csrf_token()}}';
    </script>

</head>
<body>
@yield('content')
{{--<div class="register">--}}
{{--    <div class="container">--}}
{{--        <div class="register-wrapper">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-6 col-12">--}}
{{--                    <a href="{{ url('/',app()->getLocale()) }}" class="logo-register">--}}
{{--                        <img src="{{ asset('frontend/assets/img/logo_register.png') }}" alt="Logo register" width="700px">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-md-6 col-12">--}}
{{--                   <div class="register-forms">--}}
{{--                       @yield('content')--}}
{{--                   </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" ></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" ></script>--}}

<script src="{{ asset('profile/assets/js/jquery-3.6.0.min.js') }}"></script>

<script src="{{ asset('profile/assets/js/aos.js') }}"></script>
<script src="{{ asset('profile/assets/js/slick.min.js') }}"></script>
<script>
    AOS.init();
</script>
<script src="{{ asset('profile/assets/js/main.js') }}"></script>
@yield('js')

<script>
    $(document).ready(function () {


        $('.regions').change(function () {
            region_id = $(this).val();
            $.ajax({
                type: 'post',
                url: '/ru/city/get-cities',
                data: { '_token': _csrf_token,'region_id':region_id },
                success: function($response) {
                    if ($response.status) {
                        $('#cities').html($response.data);
                    } else {
                        alert("{{__('main.not_found')}}")
                    }
                },
                error: function(e) {
                    alert("{{__('main.server_error')}}")
                }
            });
        });

        function checkPhoneExist(phone){
            var status = false;
            $.ajax({
                type: 'post',
                url: '/ru/check-phone',
                async: false,
                data: { '_token': _csrf_token,'phone':phone },
                success: function($response) {
                    status = $response.status;
                },
                error: function(e) {
                    alert("{{__('main.server_error')}}")
                    status = false;
                }
            });
            return status;
        }

    });

</script>

</body>
</html>
