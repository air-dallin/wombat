<section class="content">
    <div class="products">
        <div class="container">
            <div class="products-wrapper">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="products-info">
                            <div>
                                <h2>{{__('main.our_products')}}</h2>
                                <p>
                                    {{ __('main.our_products_desc') }}
                                </p>
                            </div>
                            <a href="{{ localeRoute('categories') }}">{{__('main.more_detailed')}}</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="products-items">
                            <!-- Swiper -->
                            <div class="swiper swiperProducts">
                                <div class="swiper-wrapper">

                                    @foreach($categories as $category)
                                    <div class="swiper-slide">
                                        <div class="products-item">
                                            <a href="{{ localeRoute('categories.category', $category->slug) }}">
                                            <div class="products-img">
                                                <img src=" {{ isset($category->image ) ? Storage::url($category->image->small()) : ''  }} " alt="#"/>
                                            </div>
                                            <div class="products-text">
                                                <strong>{{$category->getTitle()}}</strong>
                                                <small>{{__('main.read_more')}}</small>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach


                                </div>
                            </div>
                            <div class="products-btns">
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
