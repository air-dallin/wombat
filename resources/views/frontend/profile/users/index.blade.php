@extends('layouts.profile')
@section('title', __('main.users'))
{{--@section('breadcrumbs', __('main.users'))--}}
@section('content')

  @include('alert-profile')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header justify-content-between">
          <h3>{{ __('main.users') }}</h3>
          <div>
            {{--                            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role,[\App\Models\User::ROLE_COMPANY ]))--}}
            <a href="{{ localeRoute('frontend.profile.users.create') }}" class="btn-create bg-success">{{__('main.create_user')}}</a>
            {{--                            @endif--}}
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-responsive-md">
              <thead>
              <tr>
                <th> ID</th>
                <th>{{ __('main.image') }}</th>
                <th class="sorting" data-sort="status">{{__('main.status')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('status') !!}</th>
                <th class="sorting" data-sort="user_from">{{__('main.user_from')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('user_from') !!}</th>
                <th class="sorting" data-sort="region">{{__('main.region')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('region') !!}</th>
                <th class="sorting" data-sort="created_at">{{__('main.created_at')}} {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                <th>{{ __('main.actions') }}</th>
              </tr>
              </thead>
              <tbody>
              @foreach($users as $user)
                <tr>
                  <td>{{ $user->id }}</td>
                  <td>
                    <img class="img-user" src="{{ App\Models\User::getImage($user) }}">
                  </td>
                  <td>{!!$user->getStatusLabel() !!}</td>
                  <td>{{$user->info->getFullname()}}</td>
                  <td>{{ isset($user->info->region) ? $user->info->region->getTitle() : 'not_set'}}</td>
                  <td>{{date('Y-m-d H:i',strtotime($user->created_at))}}</td>
                  <td>
                    <a href="{{localeRoute('frontend.profile.users.edit',$user)}}" class="btn btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          {{ $users->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
        </div>
      </div>
    </div>

  </div>

@endsection

@push('scripts')
  <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
  <script src="{{ asset('js/sorting.js') }}"></script>
@endpush

