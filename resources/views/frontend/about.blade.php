@extends('layouts.site')
@section('breadcrumbs', __('main.about'))
@section('content')

    <div class="about page">
        <div class="about-top">
            <div class="container">
                @include('frontend.sections.breadcrumbs')

{{--                <h2 class="title">{{__('main.about')}}</h2>--}}
                <div>
                    {!! __('main.about_company') !!}
                </div>
            </div>

        </div>

    </div>
@endsection
