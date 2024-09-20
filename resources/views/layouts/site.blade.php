<!DOCTYPE html>
<html lang="en">
<head>
    @meta_tags
    <link rel="stylesheet" href="{{ asset('profile/assets/modules/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('profile/assets/modules/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/libs/swiper/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/app.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/media.css') }}"/>
{{--    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>--}}
    @yield('css')

    <script src="{{ asset('profile/assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('profile/assets/modules/popper.js') }}"></script>
    <script src="{{ asset('profile/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/swiper/js/swiper-bundle.min.js') }}"></script>

    <script>
        let _csrf_token = '{{ csrf_token() }}';
        let locale = '{{ app()->getLocale() }}';
    </script>


</head>
<body>
    @include('frontend.sections.header')

        @yield('content')

    @include('frontend.sections.footer')

<script src="{{ asset('frontend/assets/js/app.js') }}"></script>
    @yield('js')
    @stack('scripts')

</body>
</html>
