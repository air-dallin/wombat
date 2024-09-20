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

        .be__sides {margin: 20px 0;}
        .be__sides__table_wrap:first-child {padding-left: 0; }
        .be__sides__table_wrap {padding: 0 10px; display: inline-block; width: 47%; vertical-align: top }
        .be__sides__table_wrap:last-child {padding-right: 0; }
        .be__sides__table {border: none; font-size: 12px; width: 100%; }
        .be__sides__table tr td {padding: 5px 0; vertical-align: top;}

        .be__doc_totalsum {margin-bottom: 10px; font-size: 12px; }
        .be__factura-type {padding: 15px 30px; border: 1px solid #dadce0; display: inline-block; margin-bottom: 15px;}
        .be__factura-type > p {margin: 0}
        .be__one-sided-doc-type {padding: 15px 30px; border: 1px solid #dadce0; display: inline-block; margin-top: 75px; margin-left: 100px; margin-bottom: 15px;}

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

        .be__mp{margin-top: 20px; text-align: center}
        .be__mp__side {width: 48%; display: inline-block; text-align: left; vertical-align: top;}
        .be__mp__side--left {padding-right: 20px;}
        .be__mp__side--right .be__mp__row_text {width: 45%;}
        .be__mp__row {align-items: center; }
        .be__mp__row_text {line-height: 26px; min-height: 26px; width: 45%; margin-right: 10px; border-bottom: 2px solid #000; display: inline-block; }
        .be__mp__row_text--desc {border:none; line-height: normal; vertical-align: top;}
        .be__mp__row_title {padding-top: 7px; min-height: 26px; width: 25%; display: inline-block;}
        .be__mp__row--last {display: block; margin-top: 30px }
        .be__mp__row_title--full {width: 100%; border-bottom: 2px solid #000; display: block }
        .be__mp__row_text--full {width: 100%; border: none; text-align: center; display: block}
        .be__mp__row_title--mp{ width: 50%}
        @media print {
            @page {
                size: landscape;
            }
        }
    </style>
        <div class="row landscape">
            <div class="col-12">
                <div class="card">
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
                                            <strong>ID документа (Didox.uz): {{$product->didox_id}}</strong>
                                        </div>
                                        <div>
                                            <strong>ID документа (Rouming.uz): {{$product->factura_id}}</strong>
                                        </div>
                                    </div>
                                    <div class="be__doc-id__title">идентификатор электронного документа</div>
                                </div>                        <div class="be__factura-type">
                                    <p>Стандартный</p>
                                </div>
                                <div class="be__title">
                                    Счет-фактура<br>№ {{$product->number}} от {{date('Y-m-d',strtotime($product->date))}}<br>к договору № {{$product->contract->contract_number}} от {{$product->contract->contract_date}}</div>
                                <div class="be__sides">
                                    <div class="be__sides__table_wrap">
                                        <table class="be__sides__table">
                                            <tbody>
                                            <tr>
                                                <td class="be__w-45">
                                                    Поставщик:
                                                </td>
                                                <td>
                                                    <div class="be__border-bottom">
                                                        {{$owner->name}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">Адрес:</td>
                                                <td>
                                                    <div class="be__border-bottom">{{$owner->address}}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">
                                                    Идентификационный номер поставщика
                                                    &nbsp;(ИНН):
                                                </td>
                                                <td>
                                                    <div class="be__border-bottom">{{$owner->tin}}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">Регистрационный код плательщика НДС:</td>
                                                <td>
                                                    <div class="be__border-bottom">
                                                        @if(!empty($owner->vatregcode))
                                                        {{$owner->vatregcode}}
                                                        (сертификат активный)
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">Р/С:</td>
                                                <td>
                                                    <div class="be__border-bottom">{{$owner->account}}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">МФО:</td>
                                                <td>
                                                    <div class="be__border-bottom">{{$owner->bankid}}</div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="be__sides__table_wrap">
                                        <table class="be__sides__table">
                                            <tbody>
                                            <tr>
                                                <td class="be__w-45">Покупатель:</td>
                                                <td>
                                                    <div class="be__border-bottom">
                                                        {{$client->name}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">Адрес:</td>
                                                <td>
                                                    <div class="be__border-bottom">{{$client->address}}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">Идентификационный номер покупателя (ИНН):</td>
                                                <td>
                                                    <div class="be__border-bottom">{{$client->tin}}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">Регистрационный код плательщика НДС:</td>
                                                <td>
                                                    <div class="be__border-bottom">
                                                        {{$client->vatregcode}} &nbsp;
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">Р/С:</td>
                                                <td>
                                                    <div class="be__border-bottom">{{$client->account}}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="be__w-45">МФО:</td>
                                                <td>
                                                    <div class="be__border-bottom">{{$client->bankid}}</div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <table class="be__products-table" border="1">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">№</th>
                                        <th rowspan="2">Наименование товаров (услуг)</th>
                                        <th rowspan="2" class="be__w-30">Идентификационный код и название по Единому электронному национальному каталогу товаров (услуг)</th>
                                        <th rowspan="2">Единица измерения</th>
                                        <th rowspan="2">Количество</th>
                                        <th rowspan="2">Цена</th>
                                        <th rowspan="2">Стоимость<br>поставки</th>
                                        <th colspan="2">НДС</th>
                                        <th rowspan="2">Стоимость поставки<br>с учетом НДС</th>
                                        <th rowspan="2">Происхождение товара</th>
                                    </tr>
                                    <tr>
                                        <th>Ставка</th>
                                        <th>Сумма</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="be__tac">1</td>
                                        <td class="be__tac">2</td>
                                        <td class="be__tac">3</td>
                                        <td class="be__tac">4</td>
                                        <td class="be__tac">5</td>
                                        <td class="be__tac">6</td>
                                        <td class="be__tac">7</td>
                                        <td class="be__tac">8</td>
                                        <td class="be__tac">9</td>
                                        <td class="be__tac">10</td>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php
                                        /** @var ProductItem $item */
                                        $sumAll = 0;
                                        $ndsAll = 0;
                                        $totalAll = 0;
                                    @endphp
                                    @if(!empty($product->items))


                                        @foreach($product->items as $n=>$item)
                                            @php
                                                $sum = (int)$item->quantity * (float)$item->amount;
                                                $nds = (float)$item->nds->slug * $sum/100;
                                                $sumAll += $sum;
                                                $ndsAll += $nds;
                                                $total = $sumAll+$ndsAll;
                                                $totalAll+= $total;
                                            @endphp

                                            <tr>
                                            <td class="be__tac">{{++$n}}</td>
                                            <td class="be__word_wrap">{{$item->title}}</td>
                                            <td class="be__word_wrap">
                                                {{$item->ikpu->code .' - ' . $item->ikpu->getTitle()}}
                                            </td>
                                            <td>
                                                {{!empty($item->package)?$item->package->getTitle():''}}
                                            </td>
                                            <td class="be__tac">
                                                {{$item->quantity}}
                                            </td>
                                            <td class="be__tar">
                                                {{$item->amount}}
                                            </td>
                                            <td class="be__tar">{{$sum}}</td>
                                            <td class="be__tac">{{$item->nds->getTitle()}}</td>
                                            <td class="be__tar">{{$nds}}</td>
                                            <td class="be__tar">{{$nds+$sum}}</td>
                                            <td>{{$item->getProductOrigin()}}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td class="be__tar bold" colspan="2">Итого</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="be__tar bold">{{$sumAll}}</td>
                                        <td class="be__tar bold" colspan="2">{{$ndsAll}}</td>
                                        <td class="be__tar bold">{{$total}}</td>
                                        <td class="be__tar"></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="be__doc_totalsum">
                                    Всего к оплате:
                                    {{num2str($total)}}
                                    . в т. ч. НДС: {{$ndsAll}}
                                    .
                                </div>

                                <div class="be__mp">
                                    <div class="be__mp__side be__mp__side--left">
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title">Руководитель</div>
                                            <div class="be__mp__row_text"></div>
                                            <div class="be__mp__row_title">{{$owner->director}}</div>
                                        </div>
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title">Главный бухгалтер:</div>
                                            <div class="be__mp__row_text"></div>
                                            <div class="be__mp__row_title">{{$owner->accountant}}</div>
                                        </div>
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title  be__mp__row_title--mp">М.П.: (при наличии печати)</div>
                                        </div>
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title">Товар отпустил</div>
                                            <div class="be__mp__row_text"></div>
                                            <div class="be__mp__row_title"></div>
                                        </div>
                                    </div>
                                    <div class="be__mp__side be__mp__side--right">
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title">Получил:</div>
                                            <div class="be__mp__row_text"></div>
                                        </div>
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title"></div>
                                            <div class="be__mp__row_text  be__mp__row_text--desc" style="line-height: normal">(подпись покупателя или уполномоченного представителя)</div>
                                        </div>
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title">Доверенность:</div>
                                            <div class="be__mp__row_text be__tac">
                                                &nbsp;
                                            </div>
                                        </div>
                                        <div class="be__mp__row  be__mp__row--last">
                                            <div class="be__mp__row_title  be__mp__row_title--full be__tac">

                                            </div>
                                            <div class="be__mp__row_text  be__mp__row_text--full">ФИО получателя</div>
                                        </div>
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title">Руководитель</div>
                                            <div class="be__mp__row_text"></div>
                                            <div class="be__mp__row_title">{{$client->director}}</div>
                                        </div>
                                        <div class="be__mp__row">
                                            <div class="be__mp__row_title">Главный бухгалтер:</div>
                                            <div class="be__mp__row_text"></div>
                                            <div class="be__mp__row_title">{{$client->accountant}}</div>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($signature))
                                <table class="be__signed__wrap__new">
                                    <tbody><tr>
                                        <td>
                                            <div class="be__signed__side gray-block">
                                                <div class="be__signed__content">
                                                    <div class="be__signed__header">
                                                        <div class="be__signed__text">{{$signature->serial}}</div>
                                                        <div class="be__signed__text">{{$signature->signingTime}}</div>
                                                    </div>
                                                    <div class="be__signed__status">
                                                                                                                <span class="gray">
                                        Отправлено
                                    </span>
                                                    </div>
                                                    <div class="be__signed__bottom">
                                                        <div class="be__signed__text">
                                                            {{$signature->taxId}}
                                                        </div>
                                                        <div class="be__signed__text">{{$signature->firstName .  ' ' . $signature->lastName}}</div>
                                                        <div class="be__signed__text">{{$signature->company}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
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

