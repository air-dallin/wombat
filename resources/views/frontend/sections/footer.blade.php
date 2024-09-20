<footer class="footer">
    <div class="container">
        <div class="footer-wrapper">
            <div class="footer-logo">
               {{-- <a href="{{ localeRoute('home') }}">
                    <img src=" {{ asset('frontend/assets/img/logo_footer.png') }} " alt="#"/>
                </a>--}}
                <ul>
                   {{-- <li>
                        {{ __('main.footer_address') }}
                    </li>--}}
                    {{--<li>
                        <a href="tel:+998 (70) 202-40-10">+998 (00) 000-00-00</a>
                    </li>
                    <li>
                        <a href="tel:+998 (90) 124-66-84">+998 (00) 000-00-00</a>
                    </li>--}}
                </ul>
            </div>
            {{--<div>
                <ul>
                    <h4>{{__('main.navigation')}}</h4>
                    <li>
                        <a href="{{ localeRoute('about') }}">{{__('main.about')}}</a>
                    </li>
                 --}}{{--   <li>
                        <a href="{{  localeRoute('frontend.news') }}">{{__('main.news')}}</a>
                    </li>--}}{{--
                    <li>
                        <a href="{{  localeRoute('contacts') }}">{{__('main.contacts')}}</a>
                    </li>
                </ul>
            </div>

            <div>
                <ul>
                    <h4>{{__('main.information')}}</h4>
                    <li><a href="/{{app()->getLocale().'/policy'}}">{{__('main.policy')}}</a></li>
                    <li><a href="#">{{__('main.terms_of_use')}}</a></li>
                    <li><a href="/{{app()->getLocale().'/offer'}}">{{__('main.offer')}}</a></li>

                </ul>
            </div>--}}
        </div>
    </div>
</footer>
