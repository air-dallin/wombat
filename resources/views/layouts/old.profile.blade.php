<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'Profile') | {{ '' }}</title>


    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('profile/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('profile/assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('icons/font-awesome-old/css/font-awesome.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('profile/assets/modules/select2/dist/css/select2.min.css') }}">
{{--
    <link rel="stylesheet" href="{{ asset('profile/assets/modules/summernote/summernote-bs4.css') }}">
--}}
    {{--    <link rel="stylesheet" href="{{ asset('profile/assets/modules/dropzonejs/dropzone.css') }}">--}}

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('profile/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('profile/assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('profile/assets/css/custom/custom.css') }}">
    <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">

    @yield('css')
    <script>
        let _csrf_token = '{{ csrf_token() }}';
        let locale = '{{ app()->getLocale() }}';
    </script>

    <style>
        .btn-create.active{
            background-color: #68bb2b;
        }
    </style>



    <!-- /END GA -->
</head>

<body>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        @include('frontend.profile.sections.header')
        @include('frontend.profile.sections.sidebar')
        <div class="main-content">
            <section class="section">
                @yield('content')
            </section>
        </div>
    </div>

</div>


<!-- General JS Scripts -->
<script src="{{ asset('profile/assets/modules/jquery.min.js') }}"></script>
<script src="{{ asset('profile/assets/modules/popper.js') }}"></script>
<script src="{{ asset('profile/assets/modules/tooltip.js') }}"></script>
<script src="{{ asset('profile/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('profile/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('profile/assets/js/stisla.js') }}"></script>

<!-- JS Libraies -->
<script src="{{ asset('profile/assets/modules/select2/dist/js/select2.min.js') }}"></script>
{{--
<script src="{{ asset('profile/assets/modules/summernote/summernote-bs4.min.js') }}"></script>
--}}
{{--<script src="{{ asset('profile/assets/modules/dropzonejs/min/dropzone.min.js') }}"></script>--}}

<!-- Page Specific JS File -->
<script src="{{ asset('profile/assets/js/page/bootstrap-modal.js') }}"></script>
<script src="{{ asset('profile/assets/js/page/inputmask.min.js') }}"></script>
{{--<script src="{{ asset('profile/assets/js/page/components-multiple-upload.js') }}"></script>--}}

<!-- Template JS File -->
<script src="{{ asset('profile/assets/js/scripts.js') }}"></script>
<script src="{{ asset('profile/assets/js/custom.js') }}"></script>
<script src="{{ asset('js/sorting.js') }}"></script>

@include('frontend.sections.notify')

@yield('js')
<script src="{{ asset('profile/assets/js/global.js') }}"></script>


</body>
</html>
