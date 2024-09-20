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
                </div>
                <div class="card-body">

                    <div class="card-body">


                     <div class="table-responsive">

                        <table class="table table-responsive-md">
                            <thead>
                            <tr>
                                <th>{{__('main.company')}}</th>
                                <th>{{__('main.service')}}</th>
                                <th>{{ __('main.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($company)

                                <tr>
                                    <td rowspan="2">{{$company->name}}</td>
                                    <td>Didox</td>
                                    <td>
                                        <a href="{{localeRoute('frontend.profile.companies.didox.edit',$company)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dibank</td>
                                    <td>
                                        <a href="{{localeRoute('frontend.profile.companies.dibank.edit',$dibank)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>

                            @endif
                            </tbody>
                        </table>
                    </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

