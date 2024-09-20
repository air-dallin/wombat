<?php
    $newClaims = 0; //\App\Models\Claim::where(['status'=>0])->count();
?>

<style>
    .beep {
        position: relative;
    }
    .beep:after {
        content: '';
        position: absolute;
        top: 2px;
        right: 8px;
        width: 7px;
        height: 7px;
        background-color: #ee2c2c;
        border-radius: 50%;
        animation: pulsate 1s ease-out;
        animation-iteration-count: infinite;
        opacity: 1;
    }
    .beep.beep-sidebar:after {
        position: static;
        margin-left: 10px;
    }
</style>

		<!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">

                            </div>
                        </div>

                        <div class="navbar-nav header-right ">
                            <?php /*  <div class="header-bg mr-2">
                                <a href="#" class="nav-link message beep"><i class="far fa-envelope"></i></a>
                            </div>
                            <div class="dropdown d-inline header-bg">
                                {{--@if(app()->getLocale() == 'en')
                                    <a href="/lang/en" class="btn dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > Eng
                                    </a>
                                @endif
                                @if(app()->getLocale() == 'ru')
                                    <a href="/lang/ru" class="btn dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > Рус
                                    </a>
                                @endif
                                @if(app()->getLocale() == 'uz')
                                    <a href="/lang/uz" class="btn dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Uzb
                                    </a>
                                @endif--}}
                                <div class="dropdown-menu w-100 p-3" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 50px, 0px); top: 0; left: 0; will-change: transform; min-width: -webkit-fill-available; max-width: fit-content">
                                    {{-- @if(app()->getLocale() != 'en')
                                        <a href="/lang/en" class="dropdown-item d-flex justify-content-center mb-1">Eng</a>
                                    @endif
                                   @if(app()->getLocale() != 'ru')
                                        <a href="/lang/ru" class="dropdown-item d-flex justify-content-center mb-1">Рус</a>
                                    @endif
                                    @if(app()->getLocale() != 'uz')
                                        <a href="/lang/uz" class="dropdown-item d-flex justify-content-center mb-1">Uzb</a>
                                    @endif--}}
                                </div>
                            </div> */ ?>


                            <div class="dropdown header-bg">
                                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                    <div class="header-img">
                                        <img alt="image" src="{{  asset('images/avatar/man.svg') }}" class="rounded-circle mr-1">
                                    </div>
                                    {{--                <img src="{{ isset($user->image) ? \Illuminate\Support\Facades\Storage::url($user->image->small()) : asset('frontend/assets/img/profile-user.png') }}" class="rounded-circle mr-1"  alt="user">--}}
                                    {{ \Illuminate\Support\Facades\Auth::user()->info->getFullname() }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right w-100 p-3 ">
                                    <a href="{{localeRoute('logout')}}" class="dropdown-item has-icon text-danger">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('main.logout') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
