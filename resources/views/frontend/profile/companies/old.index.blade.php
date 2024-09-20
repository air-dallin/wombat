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
                        <h3>{{ __('main.companies') }}</h3>
                        <div>
{{--                            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role,[\App\Models\User::ROLE_COMPANY ]))--}}
                                <a href="{{ localeRoute('frontend.profile.companies.create') }}" class="btn-create bg-success">{{__('main.create')}}</a>
{{--                            @endif--}}
                        </div>
                    </div>
                <div class="card-body">

                        <div class="row">
                            @isset($companies)
                            @foreach($companies as $company)
                            <div class="col-sm-4">
                                <div class="card @if(!empty($current_company_id) && $current_company_id==$company->id) active @endif }}" id="company_{{$company->id}}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$company->name}}</h5>
                                        <p class="card-text">{{__('main.inn')}}: {{$company->inn}}</p>

                                        <div class="row justify-content-between">
                                            <a href="#" class="btn btn-primary choice_company" data-id="{{$company->id}}">{{__('main.choice')}}</a>
                                            <a href="{{localeRoute('frontend.profile.companies.info',$company)}}" class="btn btn-primary"><i class="fa fa-edit"></i> {{__('main.edit')}}</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            {{ $companies->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
                            @endisset
                        </div>


                        <?php /*
                     <div class="table-responsive">

                        <table class="table table-responsive-md">
                            <thead>
                            <tr>
{{--
                                <th>{{ __('main.image') }}</th>
--}}
                                <th class="sorting" data-sort="status">{{__('main.status')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('status') !!}</th>
                                <th class="sorting" data-sort="name">{{__('main.company_name')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('name') !!}</th>
{{--
                                <th class="sorting" data-sort="region">{{__('main.region')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('region') !!}</th>
                                <th class="sorting" data-sort="city">{{__('main.city')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('city') !!}</th>
--}}
                                <th class="sorting" data-sort="address">{{__('main.address')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('address') !!}</th>
                                <th class="sorting" data-sort="inn">{{__('main.inn')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('inn') !!}</th>
                                <th class="sorting" data-sort="nds_code">{{__('main.nds_code')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('nds_code') !!}</th>
                                <th class="sorting" data-sort="bank_code">{{__('main.bank_code')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('bank_code') !!}</th>
                                <th class="sorting" data-sort="mfo">{{__('main.mfo')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('mfo') !!}</th>
                                <th class="sorting" data-sort="oked">{{__('main.oked')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('oked') !!}</th>
                                <th class="sorting" data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                                <th>{{ __('main.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr>

                                    {{--<td>
                                        <img class="img-сcompany" src="{{ App\Models\User::getImage($company) }}">
                                    </td>--}}

                                    <td>{!! $company->getStatusLabel()!!}</td>
{{--
                                    <td>{{$company->user->getFullname()}}</td>
--}}
                                    <td>{{$company->name}}</td>
{{--
                                    <td>{{$company->region->getTitle()}}</td>
                                    <td>{{$company->city->getTitle()}}</td>
--}}
                                    <td>{{$company->address}}</td>
                                    <td>{{$company->inn }}</td>
                                    <td>{{$company->nds_code }}</td>
                                    <td>{{$company->bank_code }}</td>
                                    <td>{{$company->mfo }}</td>
                                    <td>{{$company->oked }}</td>

{{--
                                    <td>{{ isset($company->info->region) ? $company->info->region->getTitle() : 'not_set'}}</td>
--}}
                                    <td>{{date('Y-m-d H:i',strtotime($company->created_at))}}</td>
                                    <td>
                                        <a href="{{localeRoute('frontend.profile.companies.edit',$company)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                                        <form action="{{localeRoute('frontend.profile.companies.destroy',$company)}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp remove-item"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>*/ ?>
                </div>
            </div>
        </div>

    </div>

@endsection

{{--@push('scripts')
    <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sorting.js') }}"></script>
@endpush--}}

@section('js')
<script>
    $(document).ready(function(){
        $('.choice_company').click(function(){
            $('.card').removeClass('active');
            var company_id = $(this).data('id');
            var company = $('.card#company_'+company_id+' .card-title').text();
            $('.card#company_'+company_id).addClass('active');
            $('#selected_company').text(company);

            $.ajax({
                type: 'post',
                url: '/ru/profile/companies/set-active',
                data: {'_token': _csrf_token, 'current_company_id': company_id,'current_company':company},
                success: function ($response) {
                    if($response.status) {

                    }
                },
                error: function (e) {
                    alert(e)
                }
            });

        });
    });
</script>
@endsection
