    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/',app()->getLocale()) }}">{{__('main.home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumbs')</li>
        </ol>
        <h2 class="title">@yield('breadcrumbs')</h2>
    </nav>
