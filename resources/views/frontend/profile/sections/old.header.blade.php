{{--<div class="navbar-bg"></div>--}}
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="form-inline mr-auto">
        <div class="header-bg">
            <a href="#" data-toggle="sidebar" class="nav-link"><i class="fas fa-bars"></i></a>
        </div>
    </div>
    <?php /* @if(in_array(\Illuminate\Support\Facades\Auth::user()->role,[
                    \App\Models\User::ROLE_FARMER,
                    \App\Models\User::ROLE_ADVISER_AKIS,
                    \App\Models\User::ROLE_ADVISER_UNIVERSITY,
                    \App\Models\User::ROLE_ADVISER_COMPANY,
                    \App\Models\User::ROLE_ADVISER_USER]))

            <?php
            if ($newMessage = App\Models\Ticket::newMessage()) {
                $route = localeRoute('frontend.profile.ticket.items', $newMessage->id);
            } else {
                $route      = '#';
                $newMessage = false;
            }
            ?>
        <div class="header-bg">
            <a href="{{$route}}" class="nav-link message @if($newMessage) beep @endif"><i class="far fa-envelope"></i></a>
        </div>
    @endif */ ?>

{{--
    @dump(session()->all())
--}}
    <div class="form-inline">
        {{--@if(\App\Models\Company::_checkTokenExpire())
        <div class="d-inline header-bg">
             <a href="#" class="btn-icon">{{__('main.update_token')}}</a>
        </div>
        @endif--}}
        <div class="header-bg">
            <i class="fas fa-briefcase"></i> {{__('main.selected_company')}}: <span id="selected_company"> {{session()->has('current_company')? session()->get('current_company'):__('main.all_companies')}} </span>
        </div>
    </div>

    <div class="dropdown d-inline header-bg">
        @if(app()->getLocale() == 'en')
            <a href="/lang/en" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Oz
            </a>
        @endif
        @if(app()->getLocale() == 'ru')
            <a href="/lang/ru" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Рус
            </a>
        @endif
        @if(app()->getLocale() == 'uz')
            <a href="/lang/uz" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Oz
            </a>
        @endif
        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 50px, 0px); top: 0; left: 0; will-change: transform; min-width: -webkit-fill-available; max-width: fit-content">
            @if(app()->getLocale() != 'en')
                <a href="/lang/en" class="dropdown-item d-flex justify-content-center">Oz</a>
            @endif
            @if(app()->getLocale() != 'ru')
                <a href="/lang/ru" class="dropdown-item d-flex justify-content-center">Рус</a>
            @endif
            @if(app()->getLocale() != 'uz')
                <a href="/lang/uz" class="dropdown-item d-flex justify-content-center">Uzb</a>
            @endif
        </div>
    </div>


    <div class="dropdown header-bg">
        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <div class="header-img">
                <img alt="image" src="{{ App\Models\User::getImage() }}" class="rounded-circle mr-1">
            </div>
            {{ \Illuminate\Support\Facades\Auth::user()->info->getFullname() }}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-title"> {{ auth()->user()->created_at->diffForHumans()}}</div>
            <a href="{{localeRoute('frontend.profile.info')}}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> {{ __('main.info') }}
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{localeRoute('logout')}}" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> {{ __('main.logout') }}
            </a>
        </div>
    </div>

</nav>

@php
$current_company_id = session()->has('current_company_id') ? session()->get('current_company_id') : 0;
@endphp

@section('js')
    <script>
        var current_company_id = '{{$current_company_id}}';
    </script>
@endsection
