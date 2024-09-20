{{-- Extends layout --}}
@extends('layouts.print')


{{-- Content --}}
@section('content')
    <style>
        .be__section {padding: 10px; background: #fff; border-radius: 11px; margin: auto; clear: both;}
        .be__section p {line-height: 1;}
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
    </style>
    <style>
        .be__title.contract {margin: 10px auto 0;}
        .be__text {margin: 5px; font-size: 14px; word-wrap: break-word;}
        .be__word-wrap {white-space: pre-wrap; overflow-wrap: break-word; text-align: justify;}
        .float__left {float: left;}
        .float__right {float: right;}
        .bold {font-weight: bold;}
        .date__places {min-height: 80px;}
        .contract.mb-15, .mb-15 {margin-bottom: 15px;}
        .contract.mb-30, .mb-30 {margin-bottom: 30px;}
        .contract.mt-30, .mt-30 {margin-top: 30px;}
        .text-center {text-align: center;}
        .text-left {text-align: left;}
        .column {float: left;width: 50%;}
        .column .be__title {text-align: left; margin: 0 0 15px 0;}
        .row:after {content: ""; display: table; clear: both;}
        .client .be__text {margin-bottom: 10px;}
        .contract__doc {margin: 0.5cm 2cm;}
        .page-break-inside {page-break-inside: avoid}
        .word-line { white-space: pre-line; overflow-wrap: break-word; }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between">
                        <h3>Договор (ГНИ): {{$contract->contract_number}} от {{$contract->contract_date}}</h3>

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
                                        <strong>ID документа (Rouming.uz): {{ isset($contract) ? $contract->contract_id : ''}}</strong>
                                    </div>
                                </div>
                                <div class="be__doc-id__title">идентификатор электронного документа</div>
                            </div>
                            <div class="be__title contract">
                                2
                                <p class="be__text">Договор №{{isset($contract) ? $contract->contract_number : $new_number}}</p>
                            </div>
                            <div class="be__text bold date__places">
                                <p class="float__left">
                                    {{isset($contract) ? $contract->contract_place :''}}<br>
                                    (место заключения договора)
                                </p>
                                <p class="float__right">
                                    {{isset($contract) ? $contract->contract_date :date('Y-m-d')}} <br>
                                    (дата заключения договора) <br>
                                    {{isset($contract) ? $contract->contract_expire :date('Y-m-d')}} <br>
                                    (договор действителен до)
                                </p>
                            </div>
                            <div class="be__text">
                                <p>{{$owner->name}} (именуемое в дальнейшем – Исполнитель) в лице директора <b>{{$owner->fio}}</b>, с одной стороны, и {{$client->name}} (именуемое в дальнейшем – Заказчик) в лице директора <b>{{$client->fio}}</b>, с другой стороны, вместе именуемые Стороны, а по отдельности - Сторона, заключили настоящий договор о следующем:</p>
                            </div>
                            <div class="be__title contract mb-15">
                                1. Договор заголовок
                            </div>
                            <div class="be__text bold contract">
                                По настоящему договору Заказчик оплачивает и принимает, а Исполнитель поставляет товар(услуг) на следующих условиях:
                            </div>

                            <table class="be__products-table" border="1">
                                <thead>
                                <tr>
                                    <th rowspan="2">№</th>
                                    <th rowspan="2">Наименование товаров <br> (услуг)</th>
                                    <th rowspan="2">Идентификационный код и название по Единому электронному национальному каталогу <br> товаров (услуг)</th>
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

                            @if(!empty($contract->items))
                                @php
                                    /** @var ProductItem $item */
                                    $sumAll = 0;
                                    $ndsAll = 0;
                                    $totalAll = 0;
                                    $total = 0;
                                    $sum=0;
                                @endphp

                                @foreach($contract->items as $n=>$item)
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
                                        <td>Наименование товаров <br> (услуг)</td>
                                        {{--                    <td class="be__word_wrap">{{$item->title}}</td>--}}
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


                            <div class="be__text bold contract mb-15">Общая сумма договора составляет {{num2str($total)}}, ({{$total}})</div>
                            <p class="be__text be__word-wrap mt-30 mb-30">Договор описание</p>

                            <div class="page --page-break"></div>



                            <div class="row bold contract mb-15 mt-30">
                                <div class="be__title contract mb-15 w-100">2. Юридические адреса и реквизиты сторон</div>
                                <div class="column">
                                    <div class="be__title contract mb-15">
                                        Исполнитель
                                    </div>
                                    <div class="client page-break-inside">
                                        <div class="be__text">
                                            Наименование: {{ $owner->name }}
                                        </div>
                                        <div class="be__text">
                                            Адрес: {{$owner->address}}
                                        </div>
                                        <div class="be__text">
                                            Телефон: {{$owner->phone}}
                                        </div>
                                        <div class="be__text">
                                            Факс:
                                        </div>
                                        <div class="be__text">
                                            ИНН: {{$owner->tin}}
                                        </div>
                                        <div class="be__text">
                                            ОКЭД: {{$owner->oked}}
                                        </div>
                                        <div class="be__text">
                                            Р/С: {{$owner->account}}
                                        </div>
                                        <div class="be__text">
                                            Банк: {{$owner->bank_name}}
                                        </div>
                                        <div class="be__text">
                                            МФО: {{$owner->bankid}}
                                        </div>
                                    </div>
                                </div>
                                <div class="column">
                                    {{--   @php
                                           $company = App\Models\Company::where(['id'=>$contract->company_id])->first();
                                       @endphp--}}
                                    <div class="be__title contract mb-15">Заказчик</div>
                                    <div class="client page-break-inside">
                                        <div class="be__text">Наименование:
                                            {{$client->name}}
                                        </div>
                                        <div class="be__text">Адрес:
                                            {{$client->address}}
                                        </div>
                                        <div class="be__text">
                                            Телефон: {{$client->phone}}
                                        </div>
                                        <div class="be__text">
                                            Факс:
                                        </div>
                                        <div class="be__text">ИНН:
                                            {{$client->tin}}
                                        </div>
                                        <div class="be__text">ОКЭД:
                                            {{$client->oked}}
                                        </div>
                                        <div class="be__text">Р/С:
                                            {{$client->account}}
                                        </div>
                                        <div class="be__text">Банк:
                                            {{$client->bank_name}}
                                        </div>
                                        <div class="be__text">МФО:
                                            {{$client->bankid}}
                                        </div>
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



