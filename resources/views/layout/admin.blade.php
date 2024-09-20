<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> @yield('title') | Wombat </title>
    {{--    <link rel="stylesheet" href="{{ asset('icons/font-awesome-old/css/font-awesome.min.css') }}">--}}
    {{--    <link rel="stylesheet" href="{{ asset('icons/simple-line-icons/css/simple-line-icons.css') }}">--}}
    {{--    <link rel="stylesheet" href='{{ asset('icons/flaticon/flaticon.css') }}'>--}}
    {{--    <link rel="stylesheet" href="{{ asset('css/style.css') }}">--}}
    {{--    <link rel="stylesheet" href="{{ asset('css/app.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('profile/assets/css/slick.css') }}"/>
    <link rel="stylesheet" href="{{ asset('profile/assets/css/aos.css') }}"/>
    <link rel="stylesheet" href="{{ asset('profile/assets/css/output.css') }}"/>
    <link rel="stylesheet" href="{{ asset('profile/assets/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('profile/assets/css/app.css') }}"/>
    <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
{{--    <?php--}}
{{--    if (empty($action)) $action = 'dashboard_1';--}}
{{--    ?>--}}


    {{--	@if(!empty(config('dz.public.pagelevel.css.'.$action)))--}}
    {{--		@foreach(config('dz.public.pagelevel.css.'.$action) as $style)--}}
    {{--				<link href="{{ asset($style) }}" rel="stylesheet" type="text/css"/>--}}
    {{--		@endforeach--}}
    {{--	@endif--}}

    {{-- Global Theme Styles (used by all pages) --}}
    {{--	@if(!empty(config('dz.public.global.css')))--}}
    {{--		@foreach(config('dz.public.global.css') as $style)--}}
    {{--			<link href="{{ asset($style) }}" rel="stylesheet" type="text/css"/>--}}
    {{--		@endforeach--}}
    {{--	@endif--}}

    <script>
        var _csrf_token = '{{ csrf_token() }}';
        var locale = '{{ app()->getLocale() }}';
        alert('dsd')
    </script>

</head>

<body>

<!-- layout start -->
<div class="layout-wrapper active w-full">
    <div class="relative flex w-full">
        @include('elements.sidebar')
        <div class="body-wrapper flex-1 overflow-x-hidden dark:bg-darkblack-500">
            @include('elements.header')
            <main class="w-full px-6 pb-6 pt-[100px] sm:pt-[156px] xl:px-12 xl:pb-12">
                @yield('content')
            </main>
        </div>
    </div>
</div>

@stack('scripts')

<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/deznav-init.js') }}"></script>
<script src="{{ asset('profile/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('profile/assets/js/aos.js') }}"></script>
<script src="{{ asset('profile/assets/js/slick.min.js') }}"></script>
<script>
    AOS.init();
</script>
<script src="{{ asset('profile/assets/js/quill.min.js') }}"></script>
<script src="{{ asset('profile/assets/js/main.js') }}"></script>
<script src="{{ asset('profile/assets/js/chart.js') }}"></script>

</body>
</html>
