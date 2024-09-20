{{-- Extends layout --}}
@extends('layout.default')


{{-- Content --}}
@section('content')

    @include('alert')
    <div class="container-fluid">
        <?php /* <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($product)?$product->title_ru :'' }}</a></li>
            </ol>
        </div> */ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="w-100 d-flex justify-content-between">
                            <h4>{{__('main.products')}}</h4>
                            <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST"
                            @isset($product)
                              action="{{localeRoute('admin.modules.product.update',$product)}}"
                              @else
                              action="{{localeRoute('admin.modules.product.store')}}"
                            @endisset
                        >
                        @csrf
                            <input type="hidden" name="update_products" id="update_products" value="0">

                            <div class="form-group">
                                <label>{{__('main.company')}}</label>
                                <select class="form-control" name="company_id" id="company_id" required>
                                    <option value="">{{__('main.choice_company')}}</option>
                                    @isset($companies)
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" @if( (isset($product) && $product->company_id==$company->id) ) selected @endif >{{$company->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('company_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label>{{__('main.contract')}}</label>
                                <select class="form-control @error('contract_id') is-invalid @enderror" name="contract_id" id="contract_id">
                                    <option value="">{{__('main.choice_contract')}}</option>
                                    @foreach($contracts as $contract)
                                        <option value="{{$contract->id}}" @if(isset($product) && $product->contract_id==$contract->id) selected @endif >{{ $contract->contract_number . ' - ' . $contract->contract_date }}</option>
                                    @endforeach
                                </select>
                                @error('contract_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">{{__('main.date')}}</label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" placeholder="" value="{{old('date',isset($product) ? date('Y-m-d',strtotime($product->date)) :'')}}" required>
                                @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <div>
                                <label for="contragent" class="form-label">{{__('main.contragent')}}</label>
                            </div>
                            <div class="form-group input-group mb-3">
                                <input type="text" name="contragent" id="contragent" class="form-control @error('contragent') is-invalid @enderror" placeholder="{{__('main.enter_inn')}}" value="{{old('contragent',isset($product) ? $product->contragent :'')}}" required>
                                @error('contragent')
                                <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror

                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary get_company" type="button"><i class="fa fa-clock-o "></i> {{__('main.find')}}</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contragent_company')}}</label>
                                <input type="text" name="contragent_company" id="contragent_company" class="form-control @error('contragent_company') is-invalid @enderror" value="{{old('contragent_company',isset($product) ? $product->contragent_company :'')}}" required>
                                @error('contragent_company')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.contragent_invoice')}}</label>
                                <input type="text" name="contragent_bank_code" id="contragent_bank_code" class="form-control @error('contragent_bank_code') is-invalid @enderror" value="{{old('contragent_bank_code',isset($product) ? $product->contragent_bank_code :'')}}" required>
                                @error('contragent_bank_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{__('main.status')}}</label>
                                <select class="form-control" name="status">
                                    <option value="0" @if(isset($product) && $product->status==0) selected @endif >{{__('main.inactive')}}</option>
                                    <option value="2" @if(isset($product) && $product->status==1) selected @endif >{{__('main.draft')}}</option>
                                    <option value="1" @if(isset($product) && $product->status==1) selected @endif >{{__('main.active')}}</option>
                                </select>
                            </div>


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
                                    <th>{{__('main.warehouse')}}</th>
                                    <th>{{__('main.origin')}}</th>
                                    <th></th>
                                </tr>
                                <tbody id="product_table">
                                @if( !empty($product) && isset($product->items))
                                    @foreach($product->items as $item)
                                        @php
                                            $ndsTitle = $item->nds->getTitle();
                                            $ndsValue = preg_replace('/[^.0-9]/','',$ndsTitle);
                                            //dd($ndsValue);
                                            $summa = $item->amount * $item->quantity;
                                            $ndsSumma = $summa/100*$ndsValue;
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
                                            <td>@isset($item->warehouse) {{$item->warehouse->getTitle()}} @else {{''}} @endisset</td>
                                            <td>{{App\Models\Product::getOriginLabel($item->product_origin)}}</td>
                                            <td>
                                                <span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>
                                                <input type="hidden" name="product_items[]" value="{{$item->ikpu_id}}|{{$item->title}}|{{$item->barcode}}|{{$item->unit_id}}|{{$item->quantity}}|{{$item->amount}}|{{$item->nds_id}}|{{$warehouse_id}}|{{$item->product_origin}}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>

                            <div class="d-flex justify-content-end">
                                <div class="btn-create" id="btn_create">{{__('main.save')}}</div>
                            </div>

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
                                    <option value="{{$item->id}}" title="{{$item->title_ru}}">{{ $item->code .' - '. \Illuminate\Support\Str::limit( $item->title_ru,32) /*getTitle()*/}}</option>
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
                                @isset($units)
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->getTitle()}}</option>
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
                    <div class="form-group">
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

            $('#btn_create').click(function(e){
                if(!$.isNumeric($('#company_id').val())) {
                    alert('{{__('main.choice_company')}}')
                    $('#company_id').focus();
                    return false;
                }
                if(!$.isNumeric($('#contract_id').val())) {
                    alert('{{__('main.choice_contract')}}')
                    $('#contract_id').focus();
                    return false;
                }
                if($('#contragent').val().length<1) {
                    alert('{{__('main.choice_contragent')}}')
                    $('#contragent').focus();
                    return false;
                }
                if($('#date').val().length<1) {
                    alert('{{__('main.choice_date')}}')
                    $('#date').focus();
                    return false;
                }
                if($('#contragent_company').val().length<1) {
                    alert('{{__('main.choice_contragent_company')}}')
                    $('#contragent_company').focus();
                    return false;
                }
                if($('#contragent_bank_code').val().length<1) {
                    alert('{{__('main.choice_contragent_bank_code')}}')
                    $('#contragent_bank_code').focus();
                    return false;
                }

                if($('table #product_table tr').length==0){
                    alert('{{__('main.products_not_set')}}')
                    return false;
                }

                $('#create_product').submit();

            });

            $('#add_product_items').click(function(){
                $('#product_ikpu').val('');
                $('#product_title').val('');
                $('#product_unit').val('');
                $('#product_quantity').val('');
                $('#product_amount').val('');
                $('#product_barcode').val('');
                $('#product_nds').val('');
                $('#product_warehouse').val('');
                $('#product_origin').val('');
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
                warehouse = $('#product_warehouse').val()
                warehouseText = $('#product_warehouse option:selected').text();
                origin = $('#product_origin').val();
                originText = $('#product_origin option:selected').text();

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
                if(!$.isNumeric(warehouse)) {
                    alert('{{__('main.choice_warehouse')}}')
                    $('#product_warehouse').focus();
                    return false;
                }

                if(!$.isNumeric(origin)) {
                    alert('{{__('main.choice_product_origin')}}')
                    $('#product_origin').focus();
                    return false;
                }

                ndsValue = $('#product_nds option:selected').data('nds');

                summa = amount * quantity;
                ndsSumma = summa/100*ndsValue;
                total = summa + ndsSumma;

                input = '<input type="hidden" name="product_items[]" value="'+ikpu+'|'+title+'|'+barcode+'|'+unit+'|'+quantity+'|'+amount+'|'+nds+'|'+warehouse+'|'+origin+'">'
                actions = '<td><span class="remove_product" title="{{__('main.delete')}}"><i class="fa fa-trash"></i></span>'+input+'</td>';

                $('table #product_table').append('<tr><td>'+ikpuText+'</td><td>'+title+'</td><td>'+barcode+'</td>'+'</td><td>'+unitText+'</td>'+'</td><td>'+quantity+'</td><td>'+amount+'</td><td>'+summa+'</td><td>'+ndsText+'</td><td>'+ndsSumma+'</td><td>'+total+'</td><td>'+warehouseText+'</td><td>'+originText+'</td>'+actions+'</tr>');
                $('#close_modal').click();
                $('#update_products').val(1);
            });

            $(document).on('click','.remove_product',function(){
                $(this).parent().parent().remove();
                $('#update_products').val(1);
            });

            var user_id = '{{Illuminate\Support\Facades\Auth::id()}}';
            var clicked = false;
            $('.get_company').click(function () {
                let inn = $('#contragent').val();
                if(inn=='') return false;
                if(clicked) return false;
                clicked =true;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/companies/get-company-by-inn',
                    data: {'_token': _csrf_token, 'inn': inn,'user_id':user_id},
                    success: function ($response) {
                        if ($response.status) {
                            $('input[name=contragent_company]').val($response.data['name']);
                            //$('input[name=address]').val($response.data['address']);
                            //$('input[name=bank_name]').val($response.data['bank_name']);
                            $('input[name=contragent_bank_code]').val($response.data['bank_code']);
                            //$('input[name=mfo]').val($response.data['mfo']);
                            //$('input[name=oked]').val($response.data['oked']);
                            //$('input[name=nds_code]').val($response.data['nds_code']);
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




            $('#company_id').change(function () {
                let company_id = $(this).val();
                if(company_id=='') return false;
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/modules/contract/get-contracts',
                    data: {'_token': _csrf_token, 'company_id': company_id},
                    success: function ($response) {
                        if ($response.status) {
                            $('#contract_id').html($response.data);
                        } else {
                            alert($response.error);
                        }
                    },
                    error: function (e) {
                        alert(e)
                    }
                });
            });
            @isset($product)
            $('#company_id').change();
            @endisset
        })

    </script>
@endsection

