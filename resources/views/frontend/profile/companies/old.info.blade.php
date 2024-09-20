@extends('layouts.profile')
@section('title', __('main.companies'))
{{--@section('breadcrumbs', __('main.companies'))--}}
@section('css')
    <style>
        .card.active{
            background-color:#deffd7;
        }
    </style>
@endsection

@section('content')

    @include('alert-profile')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    @include('frontend.profile.sections.company_menu')
                </div>
                <div class="card-header justify-content-between">
                        <h3>{{ __('main.info') }}</h3>
                        <div>
                            {{--<a href="{{ localeRoute('frontend.profile.companies.edit',$company) }}" class="btn-create">{{__('main.company_info')}}</a>--}}
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
