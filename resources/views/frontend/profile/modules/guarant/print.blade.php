{{-- Extends layout --}}
@extends('layouts.print')

{{-- Content --}}
@section('content')

    <style>
        .be__section {padding: 10px; background: #fff; border-radius: 11px; margin: auto; clear: both;}
        .be__doc {background: #fff; position: relative; color: #000; font-size: 11px; }
        .be__doc-id {width: 60%; margin-bottom: 10px; }
        .be__doc-id__text {border-bottom: 1px solid #000; font-size: 14px;}
        .page-break {page-break-after: always;}
        table {border-collapse: collapse; border-spacing: 0; }
        td, th{padding: .05rem !important;}
        td.be__word_wrap {max-width: 120px; padding-right: 0.3rem !important; padding-left: 0.3rem !important; word-wrap: break-word;}
        .be__tac {text-align: center; }
        .be__tar {text-align: right; }
        .be__tal {text-align: left; }
        .be__float-left {float: left; }
        .be__float-right {float: right; }
        .be__d-inline-block { display: inline-block }
        .be__border-bottom{ border-bottom: 1px solid #000}
        .be__title{ font-size: 16px; font-weight: bold; margin: 10px auto 20px; max-width: 75%; text-align: center}
        .be__subtitle { margin-left: 20px; margin-right: 20px; }
        .be__products-table {width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px; }
        .be__w-10{ width: 10%;}
        .be__w-30{ width: 30%;}
        .be__w-45{ width: 45%;}
        .be__w-50{ width: 50%;}
        .be__w-250{ width: 250px; margin: auto;}
        .be__v-align-top { vertical-align: top; }
        .be__v-align-bottom { vertical-align: bottom; }
        .be__pr-10px { padding-right: 10px !important; }
        .be__mb-0 { margin-bottom: 0 !important; }
        .be__mb-5px { margin-bottom: 5px; }
        .be__mt-0 { margin-top: 0; }
        .be__mt-5px { margin-top: 5px; }
        .be__mt-15px { margin-top: 15px; }
        .be__text-bold { font-weight: bold; }
        .mt-20{ margin-top: 20px;}
        .be__fs-12 { font-size: 12px; }
        .be__text-color-red { color: red; }
        .be__doc006 { font-size: 12px;}
        .be__doc006__row {display: block; font-size: 12px; margin-bottom: 5px;}
        .be__doc006__label { display: inline-block; padding-left: 20px; width: 32%; text-align: right; font-weight: bold; vertical-align: top}
        .be__doc006__text { display: inline-block; width: 60%; vertical-align: top  }
        .be__doc006__title { font-size: 16px; font-weight: bold; padding: 30px 0; text-align: center; }
        .be__doc006__sign { margin-bottom: 15px; }
        .be__doc006__span { display: inline-block; margin-right: 10px; vertical-align: top }
        .bold{ font-weight: bold; }
        .be__doc006__organization{ margin-bottom: 30px;}
        .be__alligned__right {text-align: right}
        .be__signed {margin-bottom: 10px; }
        .be__signed__title {margin-bottom: 15px; font-size: 18px;}
        .be__signed__wrap,
        .be__signed__wrap__new {margin: 0 -10px; width: 100%; }
        .be__signed__side {display: inline-block; padding: 8px; position: relative; width: 300px; margin-top: 15px; }
        .be__signed__num {position: absolute; left: 0; top: -28px; bottom: 0; color: #fdc2a8; font-size: 120px; }
        .be__signed__content {position: relative; min-height: 48px; }
        .be__signed__text--bold {text-transform: lowercase; }
        .be__signed__text--links {color: #2cabe3; }
        .be__signed__header { display: flex; justify-content: space-between; }
        .be__signed__status { font-size: 18px; text-transform: uppercase; font-weight: bold; text-align: center; padding: 20px 0; }
        .be__signed__status .gray { color: #babcbc; }
        .be__signed__status .orange { color: #f8ac59; }
        .be__signed__status .red { color: #ed5565; }
        .be__signed__status .green { color: #1ab394; }
        .be__signed__bottom { text-align: left; }
        .be__signed__wrap__new tr td:not(:first-child) { text-align: right; }
        .gray-block { background-color: rgba(186, 188, 188, 0.1); border: 5px #babcbc solid; border-radius: 10px; }
        .green-block { background-color: rgba(26, 179, 148, 0.1); border: 5px #1ab394 solid; border-radius: 10px; }
        .red-block { background-color: rgba(237, 85, 101, 0.1); border: 5px #ed5565 solid; border-radius: 10px; }
        .orange-block { background-color: rgba(248, 172, 89, 0.1); border: 5px #f8ac59 solid; border-radius: 10px; }
        .be__reject-status-message {
            background: #e22c67;
            padding: 12px 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        .be__reject-status-message table {
            width: 100%;
        }
        .be__reject-status-message__regular {
            color: #fff;
            text-align: center;
        }
    </style>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    {{--<div class="card-header">
                        <div class="w-100 d-flex justify-content-between">
                            <h3>{{__('main.guarant')}}</h3>
                            <a class="btn btn-outline-primary" href="{{ url()->previous() }}">{{__('main.back')}}</a>
                        </div>
                        <div class="w-100">
                            <a href="{{localeRoute('frontend.profile.modules.guarant.exportPdf',$guarant->id)}}" type="button" class="btn btn-link" target="_blank">{{__('main.download_pdf')}}</a>
                            <a href="{{localeRoute('frontend.profile.modules.guarant.download',$guarant->id)}}" target="_blank" class="btn btn-link">{{__('main.download_archiev')}}</a>
                            <a href="{{localeRoute('frontend.profile.modules.guarant.print',$guarant->id)}}" type="button" class="btn btn-link" target="_blank">{{__('main.print')}}</a>

                        @if($guarant->status!=\App\Services\DidoxService::STATUS_DELETED)
                                @if($guarant->isNotSigned())
                                    <div class="btn btn-success" data-toggle="modal" data-target="#signDocumentPopup">{{__('main.sign')}}</div>
                                    <div id="document-json" style="display:none">{{$document}}</div>
                                @elseif(!empty($guarant->response_sign))
                                    @if($guarant->doc_status!=\App\Services\DidoxService::STATUS_SIGNED)
                                        <div class="btn btn-danger reject" data-toggle="modal" data-target="#rejectDocumentPopup">{{__('main.reject')}}</div>
                                    @endif
                                    <div class="badge badge-success">{{__('main.signed')}}</div>
                                @endif

                                <a href="{{localeRoute('frontend.profile.modules.guarant.check-status',$guarant)}}" class="btn btn-primary btn-icon"><i class="fa fa-download"></i></a>

                            @else
                                <span class="badge badge-danger">{{__('main.deleted')}}</span>
                            @endif
                        </div>
                    </div>--}}

                    <div class="card-body">
                        <div class="be__section">
                            <div class="be__doc">
                                <div class="be__signed">
                                    <div style="text-align: right">
                                        <img src="{{$qrcode}}" width="120" height="120">
                                    </div>
                                </div>
                                <div class="be__doc-id">
                                    <div class="be__doc-id__text">
                                        <div>
                                            <strong>ID документа (Didox.uz): {{$guarant->didox_id}}</strong>
                                        </div>
                                        <div>
                                            <strong>ID документа (Rouming.uz): {{$guarant->guarant_id}}</strong>
                                        </div>
                                    </div>
                                    <div class="be__doc-id__title">идентификатор электронного документа</div>
                                </div>        <div class="be__doc006">
                                    <div class="be__title">Доверенность {{$guarant->guarant_number}}</div>
                                    <div class="be__doc006__organization">

                                        <div class="be__doc006__row">
                                            <div class="be__doc006__label">Дата выдачи:</div>
                                            <div class="be__doc006__text">{{$guarant->guarant_date}}</div>
                                        </div>
                                        <div class="be__doc006__row">
                                            <div class="be__doc006__label">Доверенность действительна до:</div>
                                            <div class="be__doc006__text">{{$guarant->guarant_date_expire}}</div>
                                        </div>
                                        <div class="be__doc006__row">
                                            <div class="be__doc006__label">Наименование предприятия:</div>
                                            <div class="be__doc006__text">
                                                {{$client->name}}
                                            </div>
                                        </div>
                                        <div class="be__doc006__row">
                                            <div class="be__doc006__label">Адрес:</div>
                                            <div class="be__doc006__text">{{$client->address}}</div>
                                        </div>
                                        <div class="be__doc006__row">
                                            <div class="be__doc006__label">ИНН/ПИНФЛ:</div>
                                            <div class="be__doc006__text">{{$owner->tin}}</div>
                                        </div>
                                        <div class="be__doc006__row">
                                            <div class="be__doc006__label">Доверенность выдана:</div>
                                            <div class="be__doc006__text">
                                                <span class="be__doc006__span"><span class="be__doc006__span bold">ФИО:</span>{{$agent->fio}}</span>
                                                <span class="be__doc006__span"><span class="be__doc006__span bold">ИНН/ПИНФЛ:</span>{{$agent->tin}}</span>
                                            </div>
                                        </div>
                                        <div class="be__doc006__row">
                                            <div class="be__doc006__label">На получение от:</div>
                                            <div class="be__doc006__text">
                                                {{$owner->name}}
                                            </div>
                                        </div>
                                        <div class="be__doc006__row">
                                            <div class="be__doc006__label">Материальных ценностей по договору:</div>
                                            <div class="be__doc006__text">№ {{@$guarant->contract->contract_number}} от {{@$guarant->contract->contract_date}} </div>
                                        </div>
                                    </div>
                                    <div class="be__doc006__title">Перечень товарно-материальных ценностей, подлежащих получению</div>
                                    <div class="table-wrapper">
                                        <table class="be__products-table" border="1">
                                            <thead>
                                            <tr>
                                                <th>Номер по порядку</th>
                                                <th class="be__w-30">
                                                    Идентификационный код и название по Единому электронному национальному каталогу товаров (услуг)
                                                </th>
                                                <th>Наименование товаров</th>
                                                <th>Единица измерения</th>
                                                <th>Количество</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="be__tac">1</td>
                                                <td class="be__tac">2</td>
                                                <td class="be__tac">3</td>
                                                <td class="be__tac">4</td>
                                                <td class="be__tac">5</td>
                                            </tr>
                                            @if(!empty($guarant->items))
                                                @php
                                                    /** @var GuarantItems $item */
                                                    $sumAll = 0;
                                                    $ndsAll = 0;
                                                    $totalAll = 0;
                                                @endphp

                                                @foreach($guarant->items as $n=>$item)
                                                    @php
                                                        /*$sum = (int)$item->quantity * (float)$item->amount;
                                                        $nds = (float)$item->nds->slug* $sum/100;
                                                        $sumAll += $sum;
                                                        $ndsAll += $nds;
                                                        $total = $sumAll+$ndsAll;
                                                        $totalAll+= $total;*/
                                                    @endphp
                                                    <tr>
                                                        <td class="be__tac">1</td>
                                                        <td class="be__tac be__word_wrap">
                                                            {{$item->ikpu->code .' - ' . $item->ikpu->getTitle()}}
                                                        </td>

                                                        <td class="be__tac be__word_wrap">{{$item->title}}</td>
                                                        <td class="be__tac">@isset($item->unit) {{ $item->unit->getTitle() }} @else штук @endisset</td>
                                                        <td class="be__tac">
                                                            {{$item->quantity}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="be__doc006__sign" style="margin-top: 50px"><span class="be__doc006__bold">Подпись получившего:</span>____________________ удостоверяем</div>
                                    <div class="be__doc006__sign"><span class="be__doc006__bold">Руководитель:</span>____________________{{$client->director}}</div>
                                    <div class="be__doc006__sign"><span class="be__doc006__bold">Глав. бух.:</span>____________________ </div>
                                </div>

                                @if($signature)
                                <table class="be__signed__wrap__new">
                                    <tbody>
                                    @isset($owner->serial)
                                    <tr>
                                        <td>
                                            <div class="be__signed__side gray-block">
                                                <div class="be__signed__content">
                                                    <div class="be__signed__header">
                                                        <div class="be__signed__text">{{$owner->serial}}</div>
                                                        <div class="be__signed__text">{{$owner->signing_time}}</div>
                                                    </div>
                                                    <div class="be__signed__status">
                                                    <span class="gray">
                                                        Отправлено доверенному лицу
                                                    </span>
                                                    </div>
                                                    <div class="be__signed__bottom">
                                                        <div class="be__signed__text">
                                                            {{$owner->tin}}
                                                        </div>
                                                        <div class="be__signed__text">{{$owner->fio}}</div>
                                                        <div class="be__signed__text">{{$owner->company}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    @endisset
                                    @isset($agent->serial)
                                    <tr>
                                        <td>
                                            <div class="be__signed__side contract mb-15 green-block">

                                                <div class="be__signed__content">
                                                    <div class="be__signed__header">
                                                        <div class="be__signed__text">{{$agent->serial}}</div>
                                                        <div class="be__signed__text">{{$agent->signing_time}}</div>
                                                    </div>
                                                    <div class="be__signed__status">
                                                    <span class="green">
                                                        Подтверждён дов.лицом
                                                    </span>
                                                    </div>
                                                    <div class="be__signed__bottom">
                                                        <div class="be__signed__text">
                                                            {{$agent->tin}}
                                                        </div>
                                                        <div class="be__signed__text">{{$agent->fio}}</div>
                                                        <div class="be__signed__text"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endisset
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

@endsection
