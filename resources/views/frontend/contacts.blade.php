@extends('layouts.site')
@section('breadcrumbs', __('main.contacts'))
@section('content')

    <div class="contacts page">
        <div class="container">
            @include('frontend.sections.breadcrumbs')
            <div class="contacts-wrapper">
                {{--                <h2 class="title">{{__('main.our_contacts')}}</h2>--}}
                <div class="contacts-all">
                    <ul class="nav nav-tabs">
                        <li class="nav-item dropdown">
                            <div class="tab-navigation">
                                {{--<select
                                        class="selectpicker"
                                        id="select-box"
                                        data-live-search="true"
                                >
                                    @foreach(__('main.regions_list') as  $region)
                                        <option value="{{ $loop->index+1 }}" data-tokens="{{ $region }}">
                                            {{ $region }}
                                        </option>
                                    @endforeach
                                </select>--}}
                            </div>
                        </li>
                    </ul>
                    @isset($contacts)
                    @foreach($contacts as $contact)
                        <div id="tab-{{ $contact->id }}" class="tab-content">
                            <div class="contact-list">
                                <ul>
                                    <li>
                                        <small>{{__('main.email')}}:</small>
                                        <h3>{{$contact->getAddress() }}</h3>
                                    </li>
                                    <li>
                                        <small>{{__('main.phone_number')}}</small>
                                        <div>
                                            <a href="#">{{$contact->phone_1}}</a>
                                            <a href="#">{{$contact->phone_2}}</a>
                                        </div>
                                    </li>
                                    <li>
                                        <small>{{__('main.email')}}:</small>
                                        <a href="#">{{$contact->email}}</a>
                                    </li>
                                    <li>
                                        <small>{{__('main.socials')}}:</small>
                                        <div class="contact-socials">
                                            <a href="{{$contact->telegram}}">
                                                <i class="fab fa-telegram-plane" target="_blank"></i>
                                            </a>
                                            <a href="{{$contact->facebook}}">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                            <a href="{{$contact->youtube}}">
                                                <i class="fab fa-youtube"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="contacts-map">
                                {!! $contact->location  !!}
                            </div>
                        </div>
                    @endforeach
                    @endisset
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $(window).resize(function () {
                $('.contacts').addClass("contacts-page");
                if ($(this).innerWidth() < 768) {
                    $('.contacts').removeClass("contacts-page");
                }
            })
        });
    </script>

@endsection
