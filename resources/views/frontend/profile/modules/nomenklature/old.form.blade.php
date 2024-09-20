{{-- Extends layout --}}
@extends('layouts.profile')


{{-- Content --}}
@section('content')


    <div class="container-fluid">
        @include('alert-profile')
        <?php /*<div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($nomenklature)?$nomenklature->title_ru :'' }}</a></li>
            </ol>
        </div> --*/ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('main.nomenklatures')}}</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data"
                            @isset($nomenklature)
                              action="{{localeRoute('frontend.profile.modules.nomenklature.update',$nomenklature)}}"
                              @else
                              action="{{localeRoute('frontend.profile.modules.nomenklature.store')}}"
                            @endisset
                        >
                        @csrf
                      {{--  @isset($nomenklature)
                            @method('PUT')
                        @endisset--}}

                            <div class="form-group">
                                <label>{{__('main.company')}}</label>
                                <select class="form-control" name="company_id" id="company_id" required>
                                    <option value="">{{__('main.choice_company')}}</option>
                                    @php
                                        $current_company = App\Models\Company::getCurrentCompanyId();
                                    @endphp
                                    @foreach($companies as $company)
                                        <option value="{{$company->id}}" @if( (isset($nomenklature) && $nomenklature->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group ">
                                <label class="form-label">{{__('main.ikpu')}}</label>
                                <select class="form-control" name="ikpu_id" id="ikpu_id" required>
                                    @isset($ikpu)
                                    @foreach($ikpu as $item)
                                        <option value="{{ $item->ikpu_id }}"
                                                @if(isset($nomenklature) && $nomenklature->ikpu_id==$item->id) selected @endif >{{ $item->code .' - ' . \Illuminate\Support\Str::limit($item->getTitle(),64) }}
                                        </option>
                                    @endforeach
                                    @endisset
                                </select>
                                @error('ikpu_id')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.units')}}</label>
                                <select class="form-control"  name="unit_id" required>
                                    <option value="">{{__('main.choice_unit')}}</option>
                                    @isset($units)
                                        @foreach($units as $unit)
                                            <option value="{{$unit->id}}">{{$unit->getTitle()}}</option>
                                        @endforeach
                                    @endisset
                                </select>

                            </div>
                            <div class="form-group">
                                <label>{{__('main.quantity')}}</label>
                                <input type="text" class="form-control" name="quantity" value="{{ old('quantity',isset($nomenklature)?$nomenklature->quantity:'') }}" required>
                                @error('quantity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        <!-- Nav tabs -->
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#ru">RU</a>
                                </li> <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#en">UZ lat</a>
                                </li>
                                <li class="nav-item active">
                                    <a class="nav-link" data-toggle="tab" href="#uz">UZ</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="ru" role="tabpanel">
                                    <div class="pt-4">
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <label>{{__('main.title_ru')}}</label>
                                                <input type="text" class="form-control" name="title_ru" value="{{ old('title_ru',isset($nomenklature)?$nomenklature->title_ru:'') }}" required>
                                                @error('title_ru')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="uz">
                                    <div class="pt-4">
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <label>{{__('main.title_uz')}}</label>
                                                <input type="text" class="form-control" name="title_uz" value="{{ old('title_uz',isset($nomenklature)?$nomenklature->title_uz:'') }}">
                                                @error('title_uz')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="en">
                                    <div class="pt-4">
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <label>{{__('main.title_en')}}</label>
                                                <input type="text" class="form-control" name="title_en" value="{{ old('title_en',isset($nomenklature)?$nomenklature->title_en:'') }}">
                                                @error('title_en')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>

                       {{-- <div class="form-group col-6">
                            <label class="form-label">{{__('main.category')}}</label>
                            <select class="form-control" name="category_id">

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if(isset($nomenklature) && $nomenklature->category_id==$category->id) selected @endif >{{ $category->title_ru }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label class="form-label">{{__('main.unit')}}</label>
                            <select class="form-control" name="unit_id">

                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}"
                                            @if(isset($nomenklature) && $nomenklature->unit_id==$unit->id) selected @endif >{{ $unit->title_ru }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-6">
                            <label class="form-label">{{__('main.nds')}}</label>
                            <select class="form-control" name="nds_id">

                                @foreach($nds as $nds_item)
                                    <option value="{{ $nds_item->id }}"
                                            @if(isset($nomenklature) && $nomenklature->nds_id==$nds_item->id) selected @endif >{{ $nds_item->title_ru }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nds_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                            <div class="form-group">
                                <label>{{__('main.article')}}</label>
                                <input type="text" class="form-control" name="article" value="{{ old('article',isset($nomenklature)?$nomenklature->article:'') }}" required>
                                @error('article')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>--}}

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">{{__('main.save')}}</button>
                        </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function (){

            $('#company_id').change(function () {
                let company_id = $(this).val();
                if (company_id == '') return false;
                $('select[name=ikpu_id]').html('');
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/companies/get-ikpu',
                    data: {'_token': _csrf_token, 'company_id': company_id},
                    success: function ($response) {
                        if ($response.status) {
                            $('select[name=ikpu_id]').html($response.data);
                        } else {
                            alert($response.error);
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
