@extends('layouts.profile')
@section('title', __('main.info'))

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header justify-content-between">
          <h3>{{__('main.info')}}</h3>
        </div>
        <div class="card-body">
          <div class="profile-user">
            {{--<div class="profile-user__img">
                <img src="{{ isset($user->image) ? Storage::url($user->image->small()) : asset('frontend/assets/img/profile-user.png') }}" alt="#">
            </div>--}}
            <div class="profile-user__info">
              <ul>
                <li>
                  <strong>{{__('main.fullname')}}</strong>
                  <p>{{$user->info->firstname}}</p>
                </li>
                <li>
                  <strong>{{__('main.phone')}}</strong>
                  <p>{{$user->phone}}</p>
                </li>
                {{-- <li>
                     <strong>{{ __('main.role') }}</strong>
                     <div class="badge bg-success">
                         --}}{{--            id: {{ auth()->user()->id }}<br>--}}{{--
                         {{auth()->user()->getRole(auth()->user()->role)}}</div>
                 </li>--}}
              </ul>
            </div>
          </div>
          {{-- <ul class="nav nav-pills" role="tablist">
               <li class="nav-item">
                   <a class="nav-link active show" id="user1-tab" data-toggle="tab" href="#user1" role="tab" aria-controls="user1" aria-selected="true">{{__('main.company_info')}}</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" id="user2-tab" data-toggle="tab" href="#user2" role="tab" aria-controls="user2" aria-selected="false">{{__('main.info')}}</a>
               </li>

           </ul>
           <div class="tab-content">
               <div class="tab-pane fade active show" id="user1" role="tabpanel" aria-labelledby="user1-tab">
                   <div class="main-information">
                       <div class="form-group__all">
                           <div class="form-group">
                               <label>{{__('main.farm_name')}}</label>
                               <input type="text" name="" value="{{$user->info->company }}" class="form-control" placeholder="" disabled>
                           </div>
                           <?php /*<div class="form-group">
                               <label>{{__('main.farm_sphere')}}</label>
                               <input type="text" name="" value="" class="form-control" placeholder="" disabled>
                           </div> */ ?>
                           <div class="form-group">
                               <label>{{__('main.position')}}</label>
                               <input type="text" name="position" value="{{$user->info->position}}" class="form-control" placeholder="" disabled>
                           </div>

                       </div>
                       <div class="form-group__all">
                           <div class="form-group">
                               <label>{{__('main.region')}}</label>
                               <input type="text" name="" value="{{$user->info->region->getTitle()}}" class="form-control" placeholder="Tashkent" disabled>
                           </div>
                           <div class="form-group">
                               <label>{{__('main.village')}}</label>
                               <input type="text" name="" value="{{$user->info->city->getTitle()}}" class="form-control" placeholder="Chilanzar" disabled>
                           </div>
                           <div class="form-group">
                               <label>{{__('main.address')}}</label>
                               <input type="text" name="" value="{{$user->info->address}}" class="form-control" placeholder="Katartal" disabled>
                           </div>
                       </div>
                   </div>
               </div>
--}}{{--                        @if(in_array($user->role,[\App\Models\User::ROLE_FARMER]))--}}{{--
               <div class="tab-pane fade" id="user2" role="tabpanel" aria-labelledby="user2-tab">
                   <div class="main-information">
                       <div class="form-group__all">
                           <div class="form-group">
                               <label>{{__('main.firstname')}}</label>
                               <input type="text" name="" value="{{$user->info->firstname }}" class="form-control" placeholder="Sergey" disabled>
                           </div>
                           <div class="form-group">
                               <label>{{__('main.middlename')}}</label>
                               <input type="text" name="" value="{{$user->info->middlename }}" class="form-control" placeholder="Ivasenko" disabled>
                           </div>
                           <div class="form-group">
                               <label>{{__('main.lastname')}}</label>
                               <input type="text" name="" value="{{$user->info->lastname }}" class="form-control" placeholder="Ivanovich" disabled>
                           </div>
                       </div>
                       <div class="form-group__all">
                           <?php
                           /*<div class="form-group">
                                                          <label>Phone number </label>
                                                          <input type="text" name="" value="{{$user->info->phone}}" class="form-control" placeholder="+998 99 999 - 99 - 99" disabled>
                                                      </div> */ ?>
                           <div class="form-group">
                               <label>{{__('main.gender')}}</label>
                               <input type="text" name="" value="{{$user->info->gender==1 ? 'Мужчина' : 'Женщина' }}" class="form-control" placeholder="Male" disabled>
                           </div>
                           <div class="form-group">
                               <label>{{__('main.birthdate')}}</label>
                               <input type="text" name="" value="{{date('Y-m-d',strtotime($user->info->bithdate))}}" class="form-control" placeholder="22.08.2000" disabled>
                           </div>
                           <?php
                           /*<div class="form-group">
                                                          <label>Email</label>
                                                          <input type="text" name="" value="" class="form-control" placeholder="Sergeevevgeniy@gmail.com" disabled>
                                                      </div> */ ?>
                       </div>

                   </div>
               </div>--}}
          {{--                        @endif--}}

        </div>
      </div>
    </div>
  </div>
@endsection

