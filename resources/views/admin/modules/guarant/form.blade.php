{{-- Extends layout --}}
@extends('layout.default')


{{-- Content --}}
@section('content')

    @include('alert')
    <div class="container-fluid">
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($guarant)?$guarant->title_ru :'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="w-100 d-flex justify-content-between">
                            <h4>{{__('main.guarant')}}</h4>
                            <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                        </div>
                    </div>


                    <div class="card-body">
                        <form method="POST"
                              @isset($guarant)
                                  action="{{localeRoute('admin.modules.guarant.update',$guarant)}}"
                              @else
                                  action="{{localeRoute('admin.modules.guarant.store')}}"
                            @endisset
                        >
                            @csrf

                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.guarant_number')}}</label>
                                    <input type="text" name="guarant_number"
                                           class="form-control @error('guarant_number') is-invalid @enderror @error('guarant_number') is-invalid @enderror"
                                           placeholder=""
                                           value="{{old('guarant_number',isset($guarant) ? $guarant->guarant_number :'')}}">
                                    @error('guarant_number')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.guarant_date')}}</label>
                                    <input type="date" name="guarant_date"
                                           class="form-control @error('guarant_date') is-invalid @enderror"
                                           placeholder=""
                                           value="{{old('guarant_date',isset($guarant) ? $guarant->guarant_date :'')}}">
                                    @error('guarant_date')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.guarant_date_expire')}}</label>
                                    <input type="date" name="guarant_date_expire"
                                           class="form-control @error('guarant_date_expire') is-invalid @enderror"
                                           placeholder=""
                                           value="{{old('guarant_date_expire',isset($guarant) ? $guarant->guarant_date_expire :'')}}">
                                    @error('guarant_date_expire')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.contract_number')}}</label>
                                    <input type="text" name="contract_number"
                                           class="form-control @error('contract_number') is-invalid @enderror @error('contract_number') is-invalid @enderror"
                                           placeholder=""
                                           value="{{old('contract_number',isset($contract) ? $contract->contract_number :'')}}"
                                           required>
                                    @error('contract_number')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.contract_date')}}</label>
                                    <input type="date" name="contract_date"
                                           class="form-control @error('contract_date') is-invalid @enderror"
                                           placeholder=""
                                           value="{{old('contract_date',isset($contract) ? $contract->contract_date :'')}}"
                                           required>
                                    @error('contract_date')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-6">
                                    <h3>{{__('main.your_info')}}</h3>

                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.company_inn')}}</label>
                                        <input type="text" name="company_inn"
                                               class="form-control @error('company_inn') is-invalid @enderror @error('company_inn') is-invalid @enderror"
                                               placeholder=""
                                               value="{{old('company_inn',isset($company) ? $company->inn :'')}}">
                                        @error('company_inn')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    {{--<h3>{{__('main.company')}}</h3>--}}

                                    <div class="form-group">
                                        <label>{{__('main.company')}}</label>
                                        <select class="form-control" name="company_id" id="company_id" required>
                                            <option value="">{{__('main.choice_company')}}</option>
                                            @php
                                                $current_company = App\Models\Company::getCurrentCompanyId();
                                            @endphp
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}" @if( (isset($expense_order) && $expense_order->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="row">
                                    <div class="form-group col-6">
                                        <label>{{__('main.invoice')}}</label>
                                        <select class="form-control @error('bank_code') is-invalid @enderror"
                                                name="bank_code">
                                            <option>{{__('main.choice_invoice')}}</option>
                                            @foreach($invoices as $invoice)
                                                <option value="{{$invoice->id}}"
                                                        @if(isset($guarant) && $guarant->invoice_id==$invoice->id) selected @endif >{{$invoice->getTitle()}}</option>
                                            @endforeach

                                            {{--@isset($company_invoices)
                                                @foreach($company_invoices as $invoice)
                                                    <option value="{{$invoice->id}}" @if(isset($guarant) && $guarant->company_invoice_id==$invoice->id) selected @endif >{{$invoice->bank_invoice}}</option>
                                                @endforeach
                                            @endisset--}}

                                        </select>
                                        @error('bank_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.mfo')}}</label>
                                        <input type="text" name="mfo" class="form-control" placeholder=""
                                               value="{{old('mfo',isset($company) ? $company->mfo :'')}}" required>
                                        @error('mfo')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.address')}}</label>
                                        <input type="text" name="address" class="form-control" placeholder=""
                                               value="{{old('address',isset($company) ? $company->address :'')}}"
                                               required>
                                        @error('address')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.director')}}</label>
                                        <input type="text" name="director" class="form-control" placeholder=""
                                               value="{{old('director',isset($company) ? $company->director :'')}}"
                                               required>
                                        @error('director')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.accountant')}}</label>
                                        <input type="text" name="accountant" class="form-control" placeholder=""
                                               value="{{old('accountant',isset($company) ? $company->accountant :'')}}"
                                               required>
                                        @error('accountant')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    </div>

                                </div>

                                <div class="col-6">
                                    <h3>{{__('main.partner_info')}}</h3>

                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.partner_inn')}}</label>
                                        <input type="text" name="partner_inn"
                                               class="form-control @error('partner_inn') is-invalid @enderror @error('partner_inn') is-invalid @enderror"
                                               placeholder=""
                                               value="{{old('partner_inn',isset($guarant) ? $guarant->partner_inn :'')}}">
                                        @error('partner_inn')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>


                               {{-- <h3>{{__('main.partner_company')}}</h3> --}}

                                <div class="form-group">
                                    <label>{{__('main.partner_company_name')}}</label>
                                    <input type="text" name="partner_company_name" class="form-control" placeholder=""
                                           value="{{old('partner_company_name',isset($partner_company_name) ? $partner_company_name->name :'')}}" required>
                                    @error('partner_company_name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>{{__('main.partner_invoice')}}</label>
                                        <input type="text" name="patner_company" class="form-control" placeholder=""
                                               value="{{old('patner_company',isset($patner_company) ? $patner_company->bank_code :'')}}" required>
                                        @error('bank_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.mfo')}}</label>
                                        <input type="text" name="mfo" class="form-control" placeholder=""
                                               value="{{old('mfo',isset($company) ? $company->mfo :'')}}" required>
                                        @error('mfo')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="form-label">{{__('main.address')}}</label>
                                    <input type="text" name="address" class="form-control" placeholder=""
                                           value="{{old('address',isset($company) ? $company->address :'')}}"
                                           required>
                                    @error('address')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.director')}}</label>
                                        <input type="text" name="director" class="form-control" placeholder=""
                                               value="{{old('director',isset($company) ? $company->director :'')}}"
                                               required>
                                        @error('director')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.accountant')}}</label>
                                        <input type="text" name="accountant" class="form-control" placeholder=""
                                               value="{{old('accountant',isset($company) ? $company->accountant :'')}}"
                                               required>
                                        @error('accountant')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                </div>
                                </div>





                            </div>

                            <div class="row">
                                    <div class="form-group col-4">
                                        <label for="" class="form-label">{{__('main.pinfl')}}</label>
                                        <input type="text" name="pinfl" class="form-control" placeholder=""
                                               value="{{old('pinfl',isset($company) ? $company->pinfl :'')}}" required>
                                        @error('pinfl')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="" class="form-label">{{__('main.mfo')}}</label>
                                        <input type="text" name="mfo" class="form-control" placeholder=""
                                               value="{{old('mfo',isset($company) ? $company->mfo :'')}}" required>
                                        @error('mfo')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                     <div class="form-group col-4">
                                        <label for="" class="form-label">{{__('main.position')}}</label>
                                        <input type="text" name="position" class="form-control" placeholder=""
                                               value="{{old('position',isset($company) ? $company->position :'')}}" required>
                                        @error('position')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.passport')}}</label>
                                    <input type="text" name="passport" class="form-control" placeholder=""
                                           value="{{old('passport',isset($company) ? $company->passport :'')}}" required>
                                    @error('passport')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.issue')}}</label>
                                    <input type="text" name="issue" class="form-control" placeholder=""
                                           value="{{old('issue',isset($company) ? $company->issue :'')}}" required>
                                    @error('issue')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.issue_date')}}</label>
                                    <input type="date" name="issue_date" class="form-control" placeholder=""
                                           value="{{old('issue_date',isset($company) ? date('Y-m-d',strtotime($company->issue_date)) :'')}}" required>
                                    @error('issue_date')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group">
                                <label>{{__('main.status')}}</label>
                                <select class="form-control" name="status">
                                    <option value="0"
                                            @if(isset($guarant) && $guarant->status==0) selected @endif >{{__('main.inactive')}}</option>
                                    <option value="2"
                                            @if(isset($guarant) && $guarant->status==2) selected @endif >{{__('main.draft')}}</option>
                                    <option value="1"
                                            @if(isset($guarant) && $guarant->status==1) selected @endif >{{__('main.active')}}</option>
                                </select>
                            </div>

                           {{-- <div class="d-flex justify-content-end">
                                <button type="submit" class="btn-create btn btn-success">{{__('main.save')}}</button>
                            </div>--}}

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection


@section('js')
    <script>
        $(document).ready(function () {
            $('#company_id').change(function () {
                let company_id = $(this).val();
                if (company_id == '') return false;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/modules/guarant/invoices',
                    data: {'_token': _csrf_token, 'company_id': company_id},
                    success: function ($response) {
                        if ($response.status) {
                            $('#company_invoice_id').html($response.data);
                        } else {
                            alert($response.error);
                        }
                    },
                    error: function (e) {
                        alert(e)
                    }
                });
            });

        })

    </script>
@endsection
