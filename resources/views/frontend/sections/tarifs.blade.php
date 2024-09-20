<section class="content">
    <div class="services">
        <div class="container">
            <h2 class="title">{{__('main.users')}}</h2>
            <div class="services-wrapper">
                <div class="swiper swiperServices">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="services-item">
                                <a href="/{{app()->getLocale() . '/users/advisers' }}">
                                    <div class="services-title">
                                        <i class="icon-advisers"></i>
                                    </div>
                                    <div class="services-desc">
                                        <h5>{{__('main.advisers')}}</h5>
                                        <p>
                                            {!! __('main.description') !!}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="services-item">
                                <a href="/{{app()->getLocale() . '/users/researchers' }}">
                                    <div class="services-title">
                                        <i class="icon-researchers"></i>
                                    </div>
                                    <div class="services-desc">
                                        <h5>{{__('main.companies')}}</h5>
                                       <p>
                                           {!! __('main.description') !!}
                                       </p>

                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
