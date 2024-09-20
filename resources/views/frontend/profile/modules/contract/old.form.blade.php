{{-- Extends layout --}}
@extends('layouts.profile')


{{-- Content --}}
@section('content')



    @include('alert-profile')
    <div class="container-fluid">
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($contract)?$contract->title_ru :'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="w-100 d-flex justify-content-between">
                            <h4>{{__('main.contract')}}</h4>
                            <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST"
                            @isset($contract)
                              action="{{localeRoute('frontend.profile.modules.contract.update',$contract)}}"
                              @else
                              action="{{localeRoute('frontend.profile.modules.contract.store')}}"
                            @endisset
                        >
                        @csrf


                            <input type="hidden" name="update_products" id="update_products" value="0">

                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.contract_number')}}</label>
                                    <input type="text" name="contract_number" class="form-control @error('contract_number') is-invalid @enderror @error('contract_number') is-invalid @enderror" placeholder="" value="{{old('contract_number',isset($contract) ? $contract->contract_number : $new_number)}}" required>
                                    @error('contract_number')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.contract_date')}}</label>
                                    <input type="date" name="contract_date" class="form-control @error('contract_date') is-invalid @enderror" placeholder="" value="{{old('contract_date',isset($contract) ? $contract->contract_date :date('Y-m-d'))}}"  required>
                                    @error('contract_date')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="" class="form-label">{{__('main.contract_expire')}}</label>
                                    <input type="date" name="contract_expire" class="form-control @error('contract_expire') is-invalid @enderror" placeholder="" value="{{old('contract_expire',isset($contract) ? $contract->contract_expire :date('Y-m-d'))}}"  required>
                                    @error('contract_expire')
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
                                               value="{{old('company_inn',isset($contract) ? $contract->company_inn :'')}}">
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
                                                <option value="{{$_company->id}}" @if( (isset($contract) && $contract->company_id==$_company->id) || $_company->id==$current_company) selected @endif >{{$_company->name}}</option>
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
                                                @isset($contract->company->invoices)
                                                    @foreach($contract->company->invoices as $invoice)
                                                        <option value="{{$invoice->id}}"
                                                                @if(isset($contract) && $contract->company_invoice_id==$invoice->id) selected @endif >{{$invoice->bank_invoice}}</option>
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
                                                   value="{{old('company_mfo',isset($contract) ? $contract->company_mfo :'')}}" required>
                                            @error('company_mfo')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.address')}}</label>
                                        <input type="text" name="company_address" class="form-control" placeholder=""
                                               value="{{old('company_address',isset($contract) ? $contract->company_address :'')}}"
                                               required>
                                        @error('company_address')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="" class="form-label">{{__('main.director')}}</label>
                                            <input type="text" name="company_director" class="form-control" placeholder=""
                                                   value="{{old('company_director',isset($contract) ? $contract->company_director :'')}}"
                                                   required>
                                            @error('company_director')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="" class="form-label">{{__('main.accountant')}}</label>
                                            <input type="text" name="company_accountant" class="form-control" placeholder=""
                                                   value="{{old('company_accountant',isset($contract) ? $contract->company_accountant :'')}}"
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

                                        <input type="text" name="partner_inn" id="partner_inn" class="form-control @error('partner_inn') placeholder="{{__('main.enter_inn')}}" is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('partner_inn',isset($contract) ? $contract->partner_inn :'')}}" required>
                                        @error('partner_inn')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror

                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary get_company" type="button"><i class="fa fa-clock-o "></i> {{__('main.find')}}</button>
                                        </div>



                                    </div>

                                    <div class="form-group">
                                        <label>{{__('main.partner_company_name')}}</label>
                                        <input type="text" name="partner_company_name" id="partner_company_name" class="form-control" placeholder=""
                                               value="{{old('partner_company_name',isset($contract) ? $contract->partner_company_name :'')}}" required>
                                        @error('partner_company_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label>{{__('main.partner_invoice')}}</label>
                                            <input type="text" name="partner_bank_code" id="partner_bank_code" class="form-control" placeholder=""
                                                   value="{{old('partner_bank_code',isset($contract) ? $contract->partner_bank_code :'')}}" required>
                                            @error('partner_bank_code')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="" class="form-label">{{__('main.mfo')}}</label>
                                            <input type="text" name="partner_mfo" class="form-control" placeholder=""
                                                   value="{{old('partner_mfo',isset($contract) ? $contract->partner_mfo :'')}}" required>
                                            @error('partner_mfo')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="form-label">{{__('main.address')}}</label>
                                        <input type="text" name="partner_address" class="form-control" placeholder=""
                                               value="{{old('partner_address',isset($contract) ? $contract->partner_address :'')}}"
                                               required>
                                        @error('partner_address')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="" class="form-label">{{__('main.director')}}</label>
                                            <input type="text" name="partner_director" class="form-control" placeholder=""
                                                   value="{{old('partner_director',isset($contract) ? $contract->partner_director :'')}}"
                                                   required>
                                            @error('partner_director')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="" class="form-label">{{__('main.accountant')}}</label>
                                            <input type="text" name="partner_accountant" class="form-control" placeholder=""
                                                   value="{{old('partner_accountant',isset($contract) ? $contract->partner_accountant :'')}}"
                                                   required>
                                            @error('partner_accountant')
                                            <small class="invalid-feedback"> {{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>


                           {{-- <div class="form-group">
                                <label>{{__('main.company')}}</label>
                                <select class="form-control" name="company_id" id="company_id" required>
                                    <option value="">{{__('main.choice_company')}}</option>
                                    @php
                                        $current_company = App\Models\Company::getCurrentCompanyId();
                                    @endphp
                                    @foreach($companies as $company)
                                        <option value="{{$company->id}}" @if( (isset($contract) && $contract->company_id==$company->id) || $company->id==$current_company) selected @endif >{{$company->name}}</option>
                                    @endforeach
                                </select>
                                @error('casse_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>--}}




                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.contract_place')}}</label>
                                <input type="text" name="contract_place" class="form-control @error('contract_place') is-invalid @enderror" placeholder="" value="{{old('contract_place',isset($contract) ? $contract->contract_place :'')}}"  required>
                                @error('contract_place')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            {{--<div>
                                <label for="contragent" class="form-label">{{__('main.contragent')}}</label>
                            </div>
                            <div class="form-group input-group mb-3">
                                <input type="text" name="contragent" id="contragent" class="form-control @error('contragent') is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('contragent',isset($contract) ? $contract->contragent :'')}}" required>
                                @error('contragent')
                                <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror

                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary get_company" type="button"><i class="fa fa-clock-o "></i> {{__('main.find')}}</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contragent_company')}}</label>
                                <input type="text" name="contragent_company" id="contragent_company" class="form-control @error('contragent_company') is-invalid @enderror" value="{{old('contragent_company',isset($contract) ? $contract->contragent_company :'')}}" required>
                                @error('contragent_company')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contragent_invoice')}}</label>
                                <input type="text" name="contragent_bank_code" id="contragent_bank_code" class="form-control @error('contragent_bank_code') is-invalid @enderror" value="{{old('contragent_bank_code',isset($contract) ? $contract->contragent_bank_code :'')}}" required>
                                @error('contragent_bank_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.amount')}}</label>
                                <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="" value="{{old('amount',isset($contract) ? $contract->amount :'')}}" required>
                                @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>--}}
                            <div class="form-group">
                                <label>{{__('main.contract_name')}}</label>
                                <input type="text" name="contract_name" id="contract_name" class="form-control @error('contract_name') is-invalid @enderror" value="{{old('contract_name',isset($contract) ? $contract->contract_name :'')}}" required>
                                @error('contract_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contract_text')}}</label>
                                <textarea class="form-control" name="contract_text" rows="6" required>{{old('contract_text',isset($contract) ? $contract->contract_text :'')}}</textarea>
                                @error('contract_text')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                          {{--  <div class="form-group">
                                <label>{{__('main.status')}}</label>
                                <select class="form-control" name="status">
                                    <option value="0" @if(isset($contract) && $contract->status==0) selected @endif >{{__('main.inactive')}}</option>
                                    <option value="2" @if(isset($contract) && $contract->status==2) selected @endif >{{__('main.draft')}}</option>
                                    <option value="99" @if(isset($contract) && $contract->status==99) selected @endif >{{__('main.send')}}</option>
                                </select>
                            </div>--}}

                            <h3>{{__('main.products')}}</h3>
                            <!-- Button trigger modal -->
                            <div class="btn btn-success" id="add_product_items" data-toggle="modal" data-target="#exampleModalCenter">
                                <i class="fa fa-plus"></i> {{__('main.add')}}
                            </div>

                            <table class="table tab-bordered table-responsive">
                                <tr>
                                    <th>{{__('main.ikpu')}}</th>
                                    <th>{{__('main.description')}}</th>
                                    <th>{{__('main.barcode')}}</th>
                                    <th>{{__('main.unit')}}</th>
                                    <th>{{__('main.quantity')}}</th>
                                    <th>{{__('main.amount')}}</th>
                                    <th>{{__('main.summa')}}</th>
                                    <th>{{__('main.nds')}}</th>
                                    <th>{{__('main.nds_summa')}}</th>
                                    <th>{{__('main.total')}}</th>
                              {{--      <th>{{__('main.warehouse')}}</th>
                                    <th>{{__('main.origin')}}</th>--}}
                                    <th></th>
                                </tr>
                                <tbody id="product_table">
                                @if( !empty($contract) && isset($contract->items))
                                    @foreach($contract->items as $item)
                                        @php
                                            /** @var $item */
                                            $ndsTitle = $item->nds->getTitle();
                                            $ndsValue = preg_replace('/[^.0-9]/','',$ndsTitle);
                                            $summa = $item->amount * $item->quantity;
                                            $ndsSumma = is_numeric($ndsValue) ? $summa/100*$ndsValue : 0;
                                            $total = $summa + $ndsSumma;
                                            $warehouse_id = isset($item->warehouse) ? $item->warehouse->id : 0;
                                        @endphp
                                        <tr>
                                            <td>{{$item->ikpu->code .' - ' . \Illuminate\Support\Str::limit($item->ikpu->title_ru,32)}}</td>
                                            <td>{{$item->title}}</td>
                                            <td>{{$item->barcode}}</td>
                                            <td>{{$item->package->getTitle()}}</td>
                                            <td>{{$item->quantity}}</td>
                                            <td>{{$item->amount}}</td>
                                            <td>{{$summa}}</td>
                                            <td>{{$ndsTitle}}</td>
                                            <td>{{$ndsSumma}}</td>
                                            <td>{{$total}}</td>
                                           {{-- <td>@isset($item->warehouse) {{$item->warehouse->getTitle()}} @else {{__('main.not_set')}}@endisset</td>
                                            <td>{{App\Models\Product::getOriginLabel($item->product_origin)}}</td>--}}
                                            <td>
                                                <span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>
                                                <input type="hidden" name="product_items[]" value="{{$item->ikpu_id}}|{{$item->title}}|{{$item->barcode}}|{{$item->unit_id}}|{{$item->quantity}}|{{$item->amount}}|{{$item->nds_id}}"> {{--|{{$warehouse_id}}|{{$item->product_origin}}"> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>

                            @if(!isset($contract) || $contract->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING)
                            <div class="d-flex justify-content-end">

                                <button type="submit" class="btn-create">{{__('main.save')}}</button>
                                <button type="submit" class="btn-create bg-success">{{__('main.sign')}}</button>
                                <button type="submit" class="btn-create bg-danger">{{__('main.reject')}}</button>

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
<div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <select class="form-control" id="product_ikpu" style="width: 100% !important;">
                            <option value="">{{__('main.choice_ikpu')}}</option>
                            @isset($ikpu)
                                @foreach($ikpu as $item)
                                    <option value="{{$item->id}}" title="{{$item->title_ru}}">{{ $item->code .' - '. \Illuminate\Support\Str::limit( $item->title_ru,32) }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">{{__('main.description')}}</label>
                        <input type="text" id="product_title" class="form-control" placeholder="">
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="" class="form-label">{{__('main.barcode')}}</label>
                            <input type="text" id="product_barcode" class="form-control" placeholder="">
                        </div>
                        <div class="form-group col-6">
                            <label>{{__('main.units')}}</label>
                            <select class="form-control"  id="product_unit">
                                <option value="">{{__('main.choice_unit')}}</option>
                                @isset($packages)
                                    @foreach($packages as $package)
                                        <option value="{{$package->id}}">{{$package->getTitle() . ' (' . $package->code .')'}}</option>
                                    @endforeach
                                @endisset
                            </select>

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-6">
                            <label for="" class="form-label">{{__('main.quantity')}}</label>
                            <input type="text" id="product_quantity" class="form-control" placeholder="">
                        </div>
                        <div class="form-group col-6">
                            <label for="" class="form-label">{{__('main.amount')}}</label>
                            <input type="text" id="product_amount" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('main.nds')}}</label>
                        <select class="form-control" id="product_nds">
                            <option value="">{{__('main.choice_nds')}}</option>
                            @isset($nds)
                                @foreach($nds as $item)
                                    <option value="{{$item->id}}" data-nds="{{preg_replace('/[^.0-9]/','',$item->getTitle())}}">{{$item->getTitle()}}</option>
                                @endforeach
                            @endisset
                        </select>

                    </div>
                   {{-- <div class="form-group">
                        <label>{{__('main.warehouse')}}</label>
                        <select class="form-control"  id="product_warehouse">
                            <option value="">{{__('main.choice_warehouse')}}</option>
                            @isset($warehouse)
                                @foreach($warehouse as $item)
                                    <option value="{{$item->id}}">{{$item->getTitle()}}</option>
                                @endforeach
                            @endisset
                        </select>

                    </div>
                    <div class="form-group">
                        <label>{{__('main.product_origin')}}</label>
                        <select class="form-control"  id="product_origin">
                            <option value="">{{__('main.choice_product_origin')}}</option>
                            @isset($product_origin)
                                @foreach($product_origin as $item)
                                    <option value="{{$item['id']}}">{{$item['title']}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>--}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close_modal" data-dismiss="modal">{{__('main.close')}}</button>
                <button type="button" class="btn btn-success" id="add_product" _data-dismiss="_modal">{{__('main.add')}}</button>
            </div>
        </div>
    </div>
</div>


@include('frontend.sections.notify')
@section('js')
    <script>
        $(document).ready(function () {
            var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';
            var clicked = false;
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
                if(!$.isNumeric($('#company_id').val())) {
                    alert('{{__('main.choice_company')}}')
                    $('#company_id').focus();
                    return false;
                }
                if($('#partner_inn').val().length<1) {
                    alert('{{__('main.choice_contragent')}}')
                    $('#partner_inn').focus();
                    return false;
                }
                if($('#date').val().length<1) {
                    alert('{{__('main.choice_date')}}')
                    $('#date').focus();
                    return false;
                }
                if($('#partner_company_name').val().length<1) {
                    alert('{{__('main.choice_contragent_company')}}')
                    $('#partner_company_name').focus();
                    return false;
                }
                if($('#partner_bank_code').val().length<1) {
                    alert('{{__('main.choice_contragent_bank_code')}}')
                    $('#partner_bank_code').focus();
                    return false;
                }

                if($('table #product_table tr').length==0){
                    e.preventDefault();
                    alert('{{__('main.products_not_set')}}')
                    return false;
                }
                //if(!$(e.target).parents('#form_contract').isValid()) e.preventDefault();
                $('#form_contract').submit();
            });

            $('.get_company').click(function () {
                let inn = $('#partner_inn').val();
                if(inn=='') return false;
                if(clicked) return false;
                clicked =true;
                notify('{{__('main.wait')}}','primary',10000);
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
                            notify('{{__('main.success')}}','success',3000);
                        } else {
                            notify($response.error,'danger',10000);
                        }
                        clicked = false;
                    },
                    error: function (e) {
                        alert(e)
                        clicked = false;
                    }
                });
            });

            $('#add_product_items').click(function(){
                $('#product_ikpu').val('');
                $('#product_title').val('');
                $('#product_unit').val('');
                $('#product_quantity').val('');
                $('#product_amount').val('');
                $('#product_barcode').val('');
                $('#product_nds').val('');
                /*$('#product_warehouse').val('');
                $('#product_origin').val('');*/
            });
            $('#add_product').click(function(e){

                ikpu = $('#product_ikpu').val();
                ikpuText = $('#product_ikpu option:selected').text();
                title = $('#product_title').val();
                barcode = $('#product_barcode').val();
                unit = $('#product_unit').val();
                quantity = $('#product_quantity').val();
                amount = $('#product_amount').val();
                unitText = $('#product_unit option:selected').text();
                nds = $('#product_nds').val();
                ndsText = $('#product_nds option:selected').text();
                /*warehouse = $('#product_warehouse').val()
                warehouseText = $('#product_warehouse option:selected').text();
                origin = $('#product_origin').val();
                originText = $('#product_origin option:selected').text();*/

                if(!$.isNumeric(ikpu)) {
                    alert('{{__('main.choice_ikpu')}}')
                    $('#product_ikpu').focus();
                    return false;
                }
                if(title.length<1) {
                    alert('{{__('main.choice_description')}}')
                    $('#product_title').focus();
                    return false;
                }
                if(!$.isNumeric(unit)) {
                    alert('{{__('main.choice_unit')}}')
                    $('#product_unit').focus();
                    return false;
                }
                if(!$.isNumeric(quantity) || quantity<1) {
                    alert('{{__('main.choice_quantity')}}')
                    $('#product_quantity').focus();
                    return false;
                }
                if(!$.isNumeric(amount) || amount<1) {
                    alert('{{__('main.choice_amount')}}')
                    $('#product_amount').focus();
                    return false;
                }
                if(!$.isNumeric(nds)) {
                    alert('{{__('main.choice_nds')}}')
                    $('#product_nds').focus();
                    return false;
                }
            /*    if(!$.isNumeric(warehouse)) {
                    alert('{ {__('main.choice_warehouse')}}')
                    $('#product_warehouse').focus();
                    return false;
                }

                if(!$.isNumeric(origin)) {
                    alert('{ {__('main.choice_product_origin')}}')
                    $('#product_origin').focus();
                    return false;
                }*/

                ndsValue = $('#product_nds option:selected').data('nds');

                summa = amount * quantity;
                ndsSumma = summa/100*ndsValue;
                total = summa + ndsSumma;

                input = '<input type="hidden" name="product_items[]" value="'+ikpu+'|'+title+'|'+barcode+'|'+unit+'|'+quantity+'|'+amount+'|'+nds+'">' /* |'+warehouse+'|'+origin+'">'*/
                actions = '<td><span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>'+input+'</td>';

                $('table #product_table').append('<tr><td>'+ikpuText+'</td><td>'+title+'</td><td>'+barcode+'</td>'+'</td><td>'+unitText+'</td>'+'</td><td>'+quantity+'</td><td>'+amount+'</td><td>'+summa+'</td><td>'+ndsText+'</td><td>'+ndsSumma+'</td><td>'+total+'</td>' /*<td>'+warehouseText+'</td><td>'+originText+'</td>'*/ +actions+'</tr>');
                $('#close_modal').click();
                $('#update_products').val(1);
            });

            $(document).on('click','.remove_product',function(){
                $(this).parent().parent().remove();
                $('#update_products').val(1);
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
                            if($response.error!=undefined) alert($response.error);
                        }
                        if($response.data==undefined) notify('{{__('main.ikpu_not_set')}}','danger',5000);
                    },
                    error: function (e) {
                        alert(e)
                    }
                });
            }
            getIkpu();
            $('#product_ikpu').select2({
                maximumSelectionLength: Infinity,
            });
            @if(isset($contract))
            $('#company_id option[value={{$contract->company_id}}]').attr('selected', 'selected').change();
            @endif
        })

    </script>
@endsection

