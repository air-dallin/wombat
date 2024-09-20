<?php
use App\Models\User;
?>
{{-- Extends layout --}}
@extends('layout.default')


{{-- Content --}}
@section('content')


    <div class="container-fluid">
        @include('alert')
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">UserController</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($company)?$company->name :'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                                {{__('main.company')}}
                        </h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" id="company" enctype="multipart/form-data"
                            @isset($company)
                              action="{{localeRoute('admin.company.update',$company)}}"
                              @else
                              action="{{localeRoute('admin.company.store')}}"
                            @endisset
                        >
                        @csrf
                        @isset($company)
                            @method('PUT')
                        @endisset


                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group input-group mb-3">
                                        {{--
                                                                            <label for="inn" class="form-label control-label">{{__('main.inn')}}</label>
                                        --}}
                                        <input type="text" name="inn" id="inn" class="form-control @error('inn') placeholder="{{__('main.enter_inn')}}" is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('inn',isset($company) ? $company->inn :'')}}" required>
                                        @error('inn')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror

                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary get_company" type="button"><i class="fa fa-clock-o "></i> {{__('main.find')}}</button>
                                        </div>



                                    </div>

                                    <div class="form-group">
                                        <label for="" class="form-label control-label">{{__('main.company_name')}}</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="" value="{{old('name',isset($company) ? $company->name :'')}}" required>

                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.nds_code')}}</label>
                                        <input type="text" name="nds_code" class="form-control" placeholder="" value="{{old('nds_code',isset($company) ? $company->nds_code :'')}}" required>
                                        @error('nds_code')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.address')}}</label>
                                        <input type="text" name="address" class="form-control" placeholder="" value="{{old('address',isset($company) ? $company->address :'')}}" required>
                                        @error('address')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>


                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.bank_name')}}</label>
                                        <input type="text" name="bank_name" class="form-control" placeholder="" value="{{old('bank_name',isset($company) ? $company->bank_name :'')}}" required>
                                        @error('bank_name')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.bank_code')}}</label>
                                        <input type="text" name="bank_code" class="form-control" placeholder="" value="{{old('bank_code',isset($company) ? $company->bank_code :'')}}" required>
                                        @error('bank_code')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.mfo')}}</label>
                                        <input type="text" name="mfo" class="form-control" placeholder="" value="{{old('mfo',isset($company) ? $company->mfo :'')}}" required>
                                        @error('mfo')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.oked')}}</label>
                                        <input type="text" name="oked" class="form-control" placeholder="" value="{{old('oked',isset($company) ? $company->oked :'')}}" required>
                                        @error('oked')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>


                            <div class="form-group">
                                <label>{{__('main.status')}}</label>
                                <select class="form-control" name="status">
                                    <option value="0" @if(isset($company) && $company->status==0) selected @endif >Отключен</option>
                                    <option value="1" @if(isset($company) && $company->status==1) selected @endif >Активен</option>
                                </select>
                            </div>
                            <hr>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success btn-submit">{{__('main.save')}}</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <link href="{{asset('/css/image.css')}}" rel="stylesheet">
    <script src="{{asset('/js/image.js')}}"></script>

    <script>
        var hasRequired = false;
        var isNew = {{isset($company)?0:1}};

        $('.regions').change(function () {
            region_id = $(this).val();
            $.ajax({
                type: 'post',
                url: '/ru/admin/city/get-cities',
                data: { '_token': _csrf_token,'region_id':region_id },
                success: function($response) {
                    if ($response.status) {
                        $('#cities').html($response.data);
                    } else {
                        alert('Can`t get cities!');
                    }
                },
                error: function(e) {
                    alert('Server error or Internet connection failed!')
                }
            });
        });

        $('.btn-submit').click(function(){
            $('.invalid-feedback').css('display','none');
            var hasRequired = false;
            $('.required').each(function(){
                if($(this).val()==''){
                    $('.nav-link[href="#'+$(this).data('tab')+'"]').click();
                    $(this).parent().find('.invalid-feedback').css('display','block');
                    hasRequired = true;
                    $(this).focus();
                    return false;
                }
            });
            if(hasRequired) return false;

            if(isNew){
                if($('input#password').val()==''){
                    $('.nav-link[href="#password"]').click();
                    $('input#password').parent().find('.invalid-feedback').css('display','block');
                    $('input#password').focus();
                    hasRequired = true;
                }
            }
            if(hasRequired) return false;
            return true;
        });

        $('form#company').submit(function(e){
           if(hasRequired) return false;
           return true;
        });


        var clicked = false;
        var user_id = '{{$company->user_id}}';
        $('.get_company').click(function () {
            let inn = $('#inn').val();
            if(inn=='') return false;
            if(clicked) return false;
            clicked =true;
            $.ajax({
                type: 'post',
                url: '/ru/profile/companies/get-company-by-inn',
                data: {'_token': _csrf_token, 'inn': inn,'user_id':user_id},
                success: function ($response) {
                    if ($response.status) {
                        $('input[name=name]').val($response.data['name']);
                        $('input[name=address]').val($response.data['address']);
                        $('input[name=bank_name]').val($response.data['bank_name']);
                        $('input[name=bank_code]').val($response.data['bank_code']);
                        $('input[name=mfo]').val($response.data['mfo']);
                        $('input[name=oked]').val($response.data['oked']);
                        $('input[name=nds_code]').val($response.data['nds_code']);
                    } else {
                        alert($response.error);
                    }
                    clicked = false;
                },
                error: function (e) {
                    alert(e)
                    clicked = false;
                }
            });
        });
    </script>

@endpush
