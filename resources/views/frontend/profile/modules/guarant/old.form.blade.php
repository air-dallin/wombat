{{-- Extends layout --}}
@extends('layouts.profile')


{{-- Content --}}
@section('content')
<style>
    .remove_product{
        cursor: pointer;
    }
</style>
    @include('alert-profile')
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
                        <form method="POST" id="form_guarant"
                              @isset($guarant)
                                  action="{{localeRoute('frontend.profile.modules.guarant.update',$guarant)}}"
                              @else
                                  action="{{localeRoute('frontend.profile.modules.guarant.store')}}"
                            @endisset
                        >
                            @csrf

                            <input type="hidden" name="update_products" id="update_products" value="0">

                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.guarant_number')}}</label>
                                    <input type="text" name="guarant_number"
                                           class="form-control @error('guarant_number') is-invalid @enderror @error('guarant_number') is-invalid @enderror"
                                           placeholder=""
                                           value="{{old('guarant_number',isset($guarant) ? $guarant->guarant_number :$number)}}" required>
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
                                           value="{{old('guarant_date',isset($guarant) ? date('Y-m-d',strtotime($guarant->guarant_date)) :'')}}" required>
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
                                           value="{{old('guarant_date_expire',isset($guarant) ? date('Y-m-d',strtotime($guarant->guarant_date_expire)) :'')}}" required>
                                    @error('guarant_date_expire')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contract')}}</label>
                                <select class="form-control @error('contract_id') is-invalid @enderror" name="contract_id" id="contract_id" required>
                                    <option value="">{{__('main.choice_contract')}}</option>
                                    @foreach($contracts as $contract)
                                        <option value="{{$contract->id}}" data-date="{{ $contract->contract_date }}" @if(isset($guarant) && $guarant->contract_id==$contract->id) selected @endif >{{ $contract->contract_number . ' - ' . $contract->contract_date }}</option>
                                    @endforeach
                                </select>
                                @error('contract_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <div class="row">
                                <div class="col-6">
                                    <h3>{{__('main.your_info')}}</h3>

                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.company_inn')}}</label>
                                        <input type="text" name="company_inn"
                                               class="form-control @error('company_inn') is-invalid @enderror @error('company_inn') is-invalid @enderror"
                                               placeholder=""
                                               value="{{old('company_inn',isset($guarant) ? $guarant->company_inn :'')}}">
                                        @error('company_inn')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>{{__('main.company')}}</label>
                                        <select class="form-control" name="company_id" id="company_id" required>
                                            <option value="">{{__('main.choice_company')}}</option>
                                            @php
                                                $current_company = App\Models\Company::getCurrentCompanyId();
                                            @endphp
                                            @foreach($companies as $_company)
                                                <option value="{{$_company->id}}" @if( (isset($guarant) && $guarant->company_id==$_company->id) || $_company->id==$current_company) selected @endif >{{$_company->name}}</option>
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
                                        <label>{{__('main.company_invoice')}}</label>
                                        <select class="form-control @error('company_invoice_id') is-invalid @enderror"
                                                name="company_invoice_id" required>
                                            <option value="">{{__('main.choice_invoice')}}</option>
                                            @isset($guarant->company->invoices)
                                            @foreach($guarant->company->invoices as $invoice)
                                                <option value="{{$invoice->id}}"
                                                        @if(isset($guarant) && $guarant->company_invoice_id==$invoice->id) selected @endif >{{$invoice->bank_invoice}}</option>
                                            @endforeach
                                            @endisset
                                        </select>
                                        @error('company_invoice_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.mfo')}}</label>
                                        <input type="text" name="company_mfo" class="form-control" placeholder=""
                                               value="{{old('company_mfo',isset($guarant) ? $guarant->company_mfo :'')}}" required>
                                        @error('company_mfo')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.address')}}</label>
                                        <input type="text" name="company_address" class="form-control" placeholder=""
                                               value="{{old('company_address',isset($guarant) ? $guarant->company_address :'')}}"
                                               required>
                                        @error('company_address')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.director')}}</label>
                                        <input type="text" name="company_director" class="form-control" placeholder=""
                                               value="{{old('company_director',isset($guarant) ? $guarant->company_director :'')}}"
                                               required>
                                        @error('company_director')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.accountant')}}</label>
                                        <input type="text" name="company_accountant" class="form-control" placeholder=""
                                               value="{{old('company_accountant',isset($guarant) ? $guarant->company_accountant :'')}}"
                                               required>
                                        @error('company_accountant')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    </div>

                                </div>
{{-- партнерский блок --}}
                                <div class="col-6">

                                    <h3>{{__('main.partner_info')}}</h3>

                                    <label for="partner_inn" class="form-label control-label">{{__('main.enter_inn')}}</label>

                                    <div class="form-group input-group mb-3">

                                        <input type="text" name="partner_inn" id="partner_inn" class="form-control @error('partner_inn') placeholder="{{__('main.enter_inn')}}" is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('partner_inn',isset($guarant) ? $guarant->partner_inn :'')}}" required>
                                        @error('partner_inn')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror

                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary get_company" type="button"><i class="fa fa-clock-o "></i> {{__('main.find')}}</button>
                                        </div>



                                    </div>

                                <div class="form-group">
                                    <label>{{__('main.partner_company_name')}}</label>
                                    <input type="text" name="partner_company_name" class="form-control" placeholder=""
                                           value="{{old('partner_company_name',isset($guarant) ? $guarant->partner_company_name :'')}}" required>
                                    @error('partner_company_name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>{{__('main.partner_invoice')}}</label>
                                        <input type="text" name="partner_bank_code" class="form-control" placeholder=""
                                               value="{{old('partner_bank_code',isset($guarant) ? $guarant->partner_bank_code :'')}}" required>
                                        @error('partner_bank_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.mfo')}}</label>
                                        <input type="text" name="partner_mfo" class="form-control" placeholder=""
                                               value="{{old('partner_mfo',isset($guarant) ? $guarant->partner_mfo :'')}}" required>
                                        @error('partner_mfo')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="form-label">{{__('main.address')}}</label>
                                    <input type="text" name="partner_address" class="form-control" placeholder=""
                                           value="{{old('partner_address',isset($guarant) ? $guarant->partner_address :'')}}"
                                           required>
                                    @error('partner_address')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.director')}}</label>
                                        <input type="text" name="partner_director" class="form-control" placeholder=""
                                               value="{{old('partner_director',isset($guarant) ? $guarant->partner_director :'')}}"
                                               required>
                                        @error('partner_director')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">{{__('main.accountant')}}</label>
                                        <input type="text" name="partner_accountant" class="form-control" placeholder=""
                                               value="{{old('partner_accountant',isset($guarant) ? $guarant->partner_accountant :'')}}"
                                               required>
                                        @error('partner_accountant')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                </div>
                                </div>

                            </div>

                            <h3>{{__('main.guarant_partner')}}</h3>


                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="" class="form-label">{{__('main.fio')}}</label>
                                    <input type="text" name="guarant_fio" class="form-control" placeholder=""
                                           value="{{old('guarant_fio',isset($guarant) ? $guarant->guarant_fio :'')}}" >
                                    @error('guarant_fio')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                                    <div class="form-group col-4">
                                        <label for="" class="form-label">{{__('main.pinfl')}}</label>
                                        <input type="text" name="guarant_pinfl" class="form-control" placeholder=""
                                               value="{{old('guarant_pinfl',isset($guarant) ? $guarant->guarant_pinfl :'')}}" required>
                                        @error('guarant_pinfl')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="" class="form-label">{{__('main.mfo')}}</label>
                                        <input type="text" name="guarant_mfo" class="form-control" placeholder=""
                                               value="{{old('guarant_mfo',isset($guarant) ? $guarant->guarant_mfo :'')}}" required>
                                        @error('guarant_mfo')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                     <div class="form-group col-4">
                                        <label for="" class="form-label">{{__('main.position')}}</label>
                                        <input type="text" name="guarant_position" class="form-control" placeholder=""
                                               value="{{old('guarant_position',isset($guarant) ? $guarant->guarant_position :'')}}" required>
                                        @error('guarant_position')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.passport')}}</label>
                                    <input type="text" name="guarant_passport" class="form-control" placeholder=""
                                           value="{{old('guarant_passport',isset($guarant) ? $guarant->guarant_passport :'')}}" required>
                                    @error('guarant_passport')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.issue')}}</label>
                                    <input type="text" name="guarant_issue" class="form-control" placeholder=""
                                           value="{{old('guarant_issue',isset($guarant) ? $guarant->guarant_issue :'')}}" required>
                                    @error('guarant_issue')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.issue_date')}}</label>
                                    <input type="date" name="guarant_issue_date" class="form-control" placeholder=""
                                           value="{{old('guarant_issue_date',isset($guarant) ? date('Y-m-d',strtotime($guarant->guarant_issue_date)) :'')}}" required>
                                    @error('guarant_issue_date')
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
                                <button type="submit" class="btn-create">{{__('main.save')}}</button>
                            </div>--}}

                            <h3>{{__('main.products')}}</h3>
                            <!-- Button trigger modal -->
                            <div class="btn btn-success" id="add_product_items" data-toggle="modal" data-target="#exampleModalCenter">
                                <i class="fa fa-plus"></i> {{__('main.add')}}
                            </div>

                            <table class="table tab-bordered">
                                <tr>
                                <th>{{__('main.ikpu')}}</th>
                                <th>{{__('main.title')}}</th>
                                <th>{{__('main.amount')}}</th>
                                <th>{{__('main.quantity')}}</th>
                                <th>{{__('main.unit')}}</th>
                                <th></th>
                                </tr>
                                <tbody id="product_table">
                                @if( !empty($guarant) && isset($guarant->items))
                                    @foreach($guarant->items as $item)
                                        <tr>
                                            <td>{{$item->ikpu->code .' - ' . \Illuminate\Support\Str::limit($item->ikpu->title_ru,32)}}</td>
                                            <td>{{$item->title}}</td>
                                            <td>{{$item->amount}}</td>
                                            <td>{{$item->quantity}}</td>
                                            <td>{{isset($item->unit)?$item->unit->getTitle():__('main.not_set')}}</td>
                                            <td>
                                                <span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>
                                                <input type="hidden" name="product_items[]" value="{{$item->ikpu_id}}|{{$item->title}}|{{$item->unit_id}}|{{$item->quantity}}|{{$item->amount}}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>

                            @if(!isset($guarant) || $guarant->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING)
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn-create">{{__('main.save')}}</button>
                            </div>
                            @endif

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{__('main.add')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="product-inputs">
                    <div class="form-group">
                        <label>{{__('main.ikpu')}}</label>
                        <select class="form-control" id="product_ikpu">
                            <option value="">{{__('main.choice_ikpu')}}</option>
                            @isset($ikpu)
                                @foreach($ikpu as $item)
                                    <option value="{{$item->id}}">{{$item->code .' - ' . \Illuminate\Support\Str::limit($item->title_ru,32) }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">{{__('main.title')}}</label>
                        <input type="text" id="product_title" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">{{__('main.amount')}}</label>
                        <input type="text" id="product_amount" id="" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">{{__('main.quantity')}}</label>
                        <input type="text" id="product_quantity" id="" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>{{__('main.units')}}</label>
                        <select class="form-control"  id="product_unit">
                            <option value="">{{__('main.choice_unit')}}</option>
                            @isset($units)
                                @foreach($units as $unit)
                                    <option value="{{$unit->id}}">{{$unit->getTitle()}}</option>
                                @endforeach
                            @endisset
                        </select>

                    </div>


                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close_modal" data-dismiss="modal">{{__('main.close')}}</button>
                <button type="button" class="btn btn-success" id="add_product" _data-dismiss="_modal">{{__('main.add')}}</button>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function () {
            var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';

            $('#company_id').change(function () {
                let company_id = $(this).val();
                if (company_id == '') return false;
                $('select[name=company_invoice_id]').html('');
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/companies/get-info',
                    data: {'_token': _csrf_token, 'company_id': company_id},
                    success: function ($response) {
                        if ($response.status) {
                            $('input[name=company_inn]').val($response.data['inn']);
                            $('input[name=company_address]').val($response.data['address']);
                            $('input[name=partner_bank_name]').val($response.data['bank_name']);
                            $('input[name=company_mfo]').val($response.data['mfo']);
                            $('input[name=company_oked]').val($response.data['oked']);
                            $('input[name=company_director]').val($response.data['director']);
                            $('input[name=company_accountant]').val($response.data['accountant']);
                            $('select[name=company_invoice_id]').html($response.data['bank_code']);
                        } else {
                            alert($response.error);
                        }
                    },
                    error: function (e) {
                        alert(e)
                    }
                });

                getIkpu();

            });

            $('.btn-create').click(function(e){

                if($('table #product_table tr').length==0){
                    e.preventDefault();
                    alert('{{__('main.products_not_set')}}')
                    return false;
                }
                if(!$(e.target).parents('#form_guarant').isValid()) e.preventDefault();
                $('#form_guarant').submit();
            });

                var clicked = false;
                $('.get_company').click(function () {
                    let inn = $('#partner_inn').val();
                    if(inn=='') return false;
                    if(clicked) return false;
                    clicked =true;
                    $.ajax({
                        type: 'post',
                        url: '/ru/profile/companies/get-company-by-inn',
                        data: {'_token': _csrf_token, 'inn': inn,'user_id':user_id},
                        success: function ($response) {
                            if ($response.status) {
                                $('input[name=partner_company_name]').val($response.data['name']);
                                $('input[name=partner_address]').val($response.data['address']);
                                $('input[name=partner_bank_name]').val($response.data['bank_name']);
                                $('input[name=partner_bank_code]').val($response.data['bank_code']);
                                $('input[name=partner_mfo]').val($response.data['mfo']);
                                $('input[name=partner_oked]').val($response.data['oked']);
                                $('input[name=partner_director]').val($response.data['director']);
                                $('input[name=partner_accountant]').val($response.data['accountant']);
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
            @if(!isset($guarant))
            $('#company_id').change();
            @endif

            $('#add_product_items').click(function(){
                $('#product_ikpu').val('');
                $('#product_title').val('');
                $('#product_amount').val('');
                $('#product_quantity').val('');
                $('#product_unit').val('');
            });
            $('#add_product').click(function(e){

                ikpu = $('#product_ikpu').val();
                ikpuText = $('#product_ikpu option:selected').text();
                title = $('#product_title').val();
                amount = $('#product_amount').val();
                quantity = $('#product_quantity').val();
                unit = $('#product_unit').val();
                unitText = $('#product_unit option:selected').text();

                if(!$.isNumeric(ikpu)) {
                    alert('{{__('main.choice_ikpu')}}')
                    $('#product_ikpu').focus();
                    return false;
                }
                if(title.length<1) {
                    alert('{{__('main.choice_title')}}')
                    $('#product_title').focus();
                    return false;
                }
                if(!$.isNumeric(amount) || amount<1) {
                    alert('{{__('main.choice_amount')}}')
                    $('#product_amount').focus();
                    return false;
                }
                if(!$.isNumeric(quantity) || quantity<1) {
                    alert('{{__('main.choice_quantity')}}')
                    $('#product_quantity').focus();
                    return false;
                }
                if(!$.isNumeric(unit)) {
                    alert('{{__('main.choice_unit')}}')
                    $('#product_unit').focus();
                    return false;
                }
                input = '<input type="hidden" name="product_items[]" value="'+ikpu+'|'+title+'|'+unit+'|'+quantity+'|'+amount+'">'
                actions = '<td><span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>'+input+'</td>';

                $('table #product_table').append('<tr><td>'+ikpuText+'</td><td>'+title+'</td><td>'+amount+'</td><td>'+quantity+'</td><td>'+unitText+'</td>'+actions+'</tr>');
                $('#close_modal').click();
                $('#update_products').val(1);
            });
            $(document).on('click','.remove_product',function(){
               $(this).parent().parent().remove();
                $('#update_products').val(1);
            });

            $('#contract_id').change(function () {
                var contract_id =$('#contract_id option:selected').val();
                if(clicked) return false;
                clicked =true;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/modules/contract/get-company-info',
                    data: {'_token': _csrf_token, 'contract_id': contract_id,'user_id':user_id},
                    success: function ($response) {
                        if ($response.status) {
                            $('input[name=partner_inn]').val($response.data['inn']);
                            $('input[name=partner_company_name]').val($response.data['name']);
                            $('input[name=partner_address]').val($response.data['address']);
                            $('input[name=partner_bank_name]').val($response.data['bank_name']);
                            $('input[name=partner_bank_code]').val($response.data['bank_code']);
                            $('input[name=partner_mfo]').val($response.data['mfo']);
                            $('input[name=partner_oked]').val($response.data['oked']);
                            $('input[name=partner_director]').val($response.data['director']);
                            $('input[name=partner_accountant]').val($response.data['accountant']);
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

            function getIkpu(){
                let company_id = $('#company_id').val();
                if (company_id == '') return false;
                $('#product_ikpu').html('');
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/companies/get-ikpu',
                    data: {'_token': _csrf_token, 'company_id': company_id},
                    success: function ($response) {
                        if ($response.status) {
                            $('#product_ikpu').html($response.data);
                        } else {
                            alert($response.error);
                        }
                    },
                    error: function (e) {
                        alert(e)
                    }
                });
            }

            getIkpu();

        })

    </script>
@endsection
