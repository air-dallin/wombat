@extends('layouts.profile')
@section('title', __('main.company_ikpus'))


@section('content')
    @include('alert-profile')

    <div class="row">

        <div class="col-12">

            <div class="card">
                <div class="card-header justify-content-between">
                    @include('frontend.profile.sections.company_menu')
                </div>
                <div class="card-header justify-content-between">
                    <h3>{{ __('main.company_ikpus') }}</h3>
                    <div>

                        {{--                            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role,[\App\Models\User::ROLE_COMPANY ]))--}}
                            <a href="{{ localeRoute('frontend.profile.company_ikpu.create') }}" class="btn-create bg-success">{{__('main.choice')}}</a>
{{--                            @endif--}}
                    </div>
                </div>
                <div class="card-body">

                     <div class="table-responsive">

                        <table class="table table-responsive-md">
                            <thead>
                            <tr>
                                <th class="sorting" data-sort="company_id">{{__('main.company_name')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('company_id') !!}</th>
                                <th class="sorting" data-sort="ikpu_id">{{__('main.ikpu')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('ikpu_id') !!}</th>
                                <th class="sorting" data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                                <th>{{ __('main.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companyIkpus as $ikpu)
                                <tr>
                                    <td>{{$ikpu->company->name}}</td>
                                    <td>{{$ikpu->getTitle()}}</td>
                                    <td>{{date('Y-m-d H:i',strtotime($ikpu->created_at))}}</td>
                                    <td>
                                        <a href="{{localeRoute('frontend.profile.company_ikpu.edit',$ikpu)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                                        <form action="{{localeRoute('frontend.profile.company_ikpu.destroy',$ikpu)}}" method="POST">
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

