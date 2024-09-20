{{-- Extends layout --}}
@extends('layouts.print')


{{-- Content --}}
@section('content')

    <style>
        .be__section {padding: 10px; background: #fff; border-radius: 11px; margin: auto; clear: both;}
        .be__doc {background: #fff; position: relative; color: #000; font-size: 11px; }
        .be__doc-id { margin-bottom: 10px; }
        .be__doc-id__text {font-size: 14px;}
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
        .be__v-align-middle { vertical-align: middle !important;}
        .be__pr-10px { padding-right: 10px !important; }
        .be__mb-0 { margin-bottom: 0 !important; }
        .be__mb-5px { margin-bottom: 5px; }
        .be__mb-15px { margin-bottom: 15px; }
        .be__mt-0 { margin-top: 0; }
        .be__mt-5px { margin-top: 5px; }
        .be__mt-15px { margin-top: 15px; }
        .be__text-bold { font-weight: bold; }
        .mt-20{ margin-top: 20px;}
        .be__fs-12 { font-size: 12px; }
        .be__text-color-red { color: red; }
        .p-3{padding: 3px;}
    </style>
    <style>
        .be__act__doc_title {font-size: 16px; text-align: center; margin: 20px auto; font-weight: bold; line-height: normal;}
        .be__act__text {text-align: justify; margin-top: 10px; font-size: 14px; margin-bottom: 10px; }
        .be__act__last { padding: 10px 50px;}
        .be__act__last_left { width: 45%; display: inline-block; margin: auto;}
        .be__act__last_right { width: 50%; display: inline-block }
        .be__act__side_title { text-align: center; margin-bottom: 20px; }
        .be__act__side_text { height: 30px; border-bottom: solid 2px #000; margin-bottom: 20px; }
        .be__act__side_mp { font-size: 14px; }
        .be__w-250{ width: 250px; margin: auto;}
    </style>
    <style>
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
    </style>
    <style>
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
    <style>
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
        .be__mp__row_title--va{ vertical-align: top;}
    </style>




    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between">
                        <h3>АКТ ВЫПОЛНЕННЫХ РАБОТ<br>№ {{$act->number}} от {{$act->date}}</h3>

                    </div>

                </div>

                <div class="card-body --page">
                    <div class="be__section">
                        <div class="be__doc contract__doc">
                            <div class="be__signed float__right" style="position:fixed; top: 1cm; right:1cm">
                                <div style="text-align: right">
                                    <img src="{{$qrcode}}" width="120" height="120">
                                </div>
                            </div>
                            <div class="be__doc-id">
                                <div class="be__doc-id__text"><div>
                                    </div>
                                    <div>
                                        <strong>ID документа (Rouming.uz): {{ isset($act) ? $act->act_id : ''}}</strong>
                                    </div>
                                </div>
                                <div class="be__doc-id__title">идентификатор электронного документа</div>
                            </div>

                            <div class="be__act">
                                <div class="be__act__doc_title">АКТ ВЫПОЛНЕННЫХ РАБОТ<br>№ {{$act->number}} от
                                    {{$act->date}}<br>по договору № {{$act->contract->contract_number}} от {{$act->contract->contract_date}}</div>
                                <div class="be__tar">{{$act->date}}</div>
                                <div class="be__act__text">Мы, нижеподписавшиеся, {{$client->company}} именуемое в дальнейшем Исполнитель, с одной стороны и
                                    {{$owner->company}} именуемое в дальнейшем Заказчик, с другой стороны составили настоящий Акт о том, что работы выполнены в соответствии с условиями Заказчика в полном объеме.
                                </div>

                            <table class="be__products-table" border="1">
                                <thead>
                                <tr>
                                    <th rowspan="2">№</th>
                                    <th rowspan="2">Идентификационный код и название по Единому электронному национальному каталогу <br> товаров (услуг)</th>
                                    <th rowspan="2">Наименование товаров <br> (услуг)</th>
                                    <th rowspan="2">Штрих-код товара/<br> услуги</th>
                                    <th rowspan="2">Единицы <br> измерения</th>
                                    <th rowspan="2">Кол- <br> во</th>
                                    <th rowspan="2">Цена</th>
                                    <th rowspan="2">Стоимость<br>поставки</th>
                                    <th rowspan="1" colspan="2">НДС</th>
                                    <th rowspan="2">Стоимость поставки с учётом НДС</th>
                                </tr>

                                <tr>
                                    <th rowspan="1">ставка</th>
                                    <th rowspan="1">сумма</th>
                                </tr>
                                </thead>
                                <tbody class="bold text-center">
                                @php
                                    /** @var \App\Models\ActItems $item */
                                    $sumAll = 0;
                                    $ndsAll = 0;
                                    $totalAll = 0;
                                    $total = 0;
                                    $sum=0;
                                @endphp
                            @if(!empty($act->items))


                                @foreach($act->items as $n=>$item)
                                    @php
                                        $sum = (int)$item->quantity * (float)$item->amount;
                                        $nds = (float)$item->nds->slug* $sum/100;
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
                                        <td></td>
                                        <td>
                                            @if(!empty($item->package))
                                                {{$item->package->getTitle()}}
                                            @endif
                                        </td>
                                        <td class="be__tac">
                                            {{$item->quantity}}
                                        </td>
                                        <td class="be__tar">
                                            {{$item->amount}}
                                        </td>
                                        <td class="be__tar">{{$sum}}</td>
                                        <td class="be__tac">{{$item->nds->getTitle()}}</td>
                                        <td class="be__tar"></td>
                                        <td class="be__tar">{{$nds}}</td>
                                        {{--                    <td class="be__tar">{{$nds+$sum}}</td>--}}
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td class="be__tar bold" colspan="2">Итого</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="be__tar bold"></td>
                                <td class="be__tar">{{$sumAll}}</td>
                                <td class="be__tar bold" colspan="2">{{$ndsAll}}</td>
                                <td class="be__tar bold">{{$total}}</td>
                            </tr>
                            </tbody>
                            </table>


                            <div class="be__act__text">
                                <p>Стороны претензий друг к другу не имеют.</p>
                                <div>
                                    Стоимость принятой работы по акту составляет:
                                    {{num2str($total)}}, ({{$total}}) Без НДС
                                </div>
                            </div>



                            <div class="be__text bold contract mb-15">Общая сумма договора составляет </div>
                            <p class="be__text be__word-wrap mt-30 mb-30">Договор описание</p>

{{--
                            <div class="page --page-break"></div>
--}}



                            <div style="clear: both"></div>
                            <div class="be__act__last">
                                <div class="be__act__last_left">
                                    <div class="be__w-250">
                                        <div class="be__act__side_title">Исполнитель:</div>
                                        <div class="be__act__side_text"></div>
                                        <div class="be__act__side_mp">М.П.:</div>
                                    </div>
                                </div>
                                <div class="be__act__last_right">
                                    <div class="be__w-250">
                                        <div class="be__act__side_title">Заказчик:</div>
                                        <div class="be__act__side_text"></div>
                                        <div class="be__act__side_mp">М.П.:</div>
                                    </div>
                                </div>
                            </div>




                            @if($signature)
                                <table class="be__signed__wrap__new">
                                    <tbody>

                                    <tr>
                                        @isset($owner->serial)
                                            <td>
                                                <div class="be__signed__side gray-block">
                                                    <div class="be__signed__content">
                                                        <div class="be__signed__header">
                                                            <div class="be__signed__text">{{$owner->serial}}</div>
                                                            <div class="be__signed__text">{{$owner->signing_time}}</div>
                                                        </div>
                                                        <div class="be__signed__status">
                             <span class="gray">
                                Отправлено
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


                                            <td style="display: block;"></td>

                                        @endisset
                                        @isset($client->serial)
                                            <td style="display: block;">
                                                <div class="be__signed__side green-block">
                                                    <div class="be__signed__content">
                                                        <div class="be__signed__header">
                                                            <div class="be__signed__text">{{$client->serial}}</div>
                                                            <div class="be__signed__text">{{$client->signing_time}}</div>
                                                        </div>
                                                        <div class="be__signed__status">
                                                    <span class="green">
                                                        Подтверждён
                                                    </span>
                                                        </div>
                                                        <div class="be__signed__bottom">
                                                            <div class="be__signed__text">
                                                                {{$client->tin}}
                                                            </div>
                                                            <div class="be__signed__text">{{$client->fio}}</div>
                                                            <div class="be__signed__text">{{$client->company}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                    </tr>
                                    </tbody>
                                </table>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
@endsection
