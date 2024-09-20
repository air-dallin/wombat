<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Site | @yield('title')</title>
    {{--
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/media.css') }}"> --}}
    @yield('css')

    <style>
        @font-face {
            font-family: "DejaVu Sans";
            font-style: normal;
            font-weight: 400;
            src: url("/fonts/dejavu-sans/DejaVuSans.ttf");
            src:
                local("DejaVu Sans"),
                local("DejaVu Sans"),
                url("/fonts/dejavu-sans/DejaVuSans.ttf") format("truetype");
        }

        .card{
            border:none;
        }
        .card-header{
            padding: 0;
            margin-bottom: 0;
            background-color: rgba(0,0,0,0);
            border: none;
        }

        body {
            font-family: "DejaVu Sans" !important;
            font-size: 10px;
        }

        @media print {
           /*body.landscape{
                width: 297mm;
                height: 210mm;
            }

           body.portrait{
               width: 210mm;
               height: 297mm;
            } */

            * {
                page-break-after: auto;
            }
            .page {
                page-break-inside: auto;
                page-break-after: auto;
            }
        }

        @page {
            margin: 5mm 5mm 5mm 0mm;
            /*font-size:10pt;*/
        }

        @media print {
            .header, .footer {
                display: none;
            }
        }

</style>



</head>
<body class="landscape">
@yield('content')

{{--
<div class="register">
    <div class="container">
        <div class="register-wrapper">
            <div class="row">
                <div class="col-md-6 col-12">
                    <a href="{{ url('/',app()->getLocale()) }}" class="logo-register">
                        <img src="{{ asset('frontend/assets/img/logo_register.png') }}" alt="Logo register">
                    </a>
                </div>
                <div class="col-md-6 col-12">
                   <div class="register-forms">
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" ></script>
--}}
{{--@yield('js')--}}

<script>
    window.onafterprint = window.close;
    window.print();
</script>

</body>
</html>
