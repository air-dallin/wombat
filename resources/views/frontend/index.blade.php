@extends('layouts.site')
@section('content')

    <main class="main">
        @include('frontend.sections.banner')
        @include('frontend.sections.products')
        @include('frontend.sections.tarifs')
        @include('frontend.sections.news')
        @include('frontend.sections.customer')
    </main>

@endsection

