@extends('layouts.profile')
@section('title', __('main.warehouses'))
{{--@section('breadcrumbs', __('main.company_warehouses'))--}}
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
                        <h3>{{ __('main.warehouses') }}</h3>
                        <div>
                            {{--                            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role,[\App\Models\User::ROLE_COMPANY ]))--}}
                                <a href="{{ localeRoute('frontend.profile.company_warehouse.create') }}" class="btn-create bg-success">{{__('main.create')}}</a>
{{--                            @endif--}}
                        </div>
                    </div>
                <div class="card-body">

                     <div class="table-responsive">

                        <table class="table table-responsive-md">
                            <thead>
                            <tr>
                                <th class="sorting" data-sort="status">{{__('main.status')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('status') !!}</th>
                                <th class="sorting" data-sort="name">{{__('main.company_name')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('name') !!}</th>
                                <th class="sorting" data-sort="title">{{__('main.title')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('title') !!}</th>
                                <th class="sorting" data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                                <th>{{ __('main.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companyWarehouses as $warehouse)
                                <tr>
                                    <td>{!! $warehouse->getStatusLabel()!!}</td>
                                    <td>{{$warehouse->company->name}}</td>
                                    <td>{{$warehouse->getTitle()}}</td>
                                    <td>{{date('Y-m-d H:i',strtotime($warehouse->created_at))}}</td>
                                    <td>
                                        <a href="{{localeRoute('frontend.profile.company_warehouse.edit',$warehouse)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                                        <form action="{{localeRoute('frontend.profile.company_warehouse.destroy',$warehouse)}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp remove-item"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sorting.js') }}"></script>
@endpush

