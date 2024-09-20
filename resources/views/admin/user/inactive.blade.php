{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    <style>
        .round{
            border-radius: 50%;
        }
    </style>

    <div class="container-fluid">
       <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">user</a></li>
            </ol>
        </div> */ ?>
        <!-- row -->

        @include('alert')

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('main.users')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                <tr>
                                    <?php /* <th class="width50">
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="checkAll" required="">
														<label class="custom-control-label" for="checkAll"></label>
													</div>
												</th> */ ?>
                                    <th><strong>{{__('main.image')}}</strong></th>
                                    <th class="sorting" data-sort="user_from"><strong>{{__('main.name')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('user_from') !!}</th>
                                    <?php /* <th><strong>{{__('main.role')}}</strong></th> */ ?>
                                    <th class="sorting" data-sort="email"><strong>{{__('main.email')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('email') !!}</th>
                                    <th class="sorting" data-sort="phone"><strong>{{__('main.phone')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('phone') !!}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @if($users)
                                    @foreach($users as $user)
                                        <tr>
                                            <?php /* <td>
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
														<label class="custom-control-label" for="customCheckBox2"></label>
													</div>
												</td> */ ?>
                                            <td>
                                                @if( isset($user->image))
                                                <div class="d-flex align-items-center"><img src="{{Storage::url($user->image->image) }} " class="round mr-2" width="100px" height="100px" alt="" /></div></td>
                                                @endif
                                            <td>{{ isset($user->info) ? $user->info->firstname . ' ' . $user->info->lastname : ''  }}	</td>
                                            <?php /*<td>{{ $user->getRole($user->role)  }}	</td> */ ?>
                                            <td>{{ $user->email }} {!! !is_null($user->email_verify_at) ? '<i class="fa fa-check">+</i>' : '' !!}	</td>
                                            <td>{{ $user->phone }} {!! !is_null($user->phone_verify_at) ? '<i class="fa fa-check">+</i>' : '' !!}		</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ localeRoute('admin.user.edit',$user) }}" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-edit"></i></a>
                                                    <?php /*<form method="post" action="{{ localeRoute('admin.user.destroy',$user) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                                    </form> */ ?>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>
                        </div>
                        {{ $users->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}

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
