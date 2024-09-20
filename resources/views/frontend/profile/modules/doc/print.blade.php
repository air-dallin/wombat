{{-- Extends layout --}}
@extends('layouts.print')


{{-- Content --}}
@section('content')

    <style>


        .be__section {
            padding: 10px;
            background: #fff;
            border-radius: 11px;
            margin: auto;
            clear: both;
        }
        .be__section p {
            line-height: 1;
        }
        .be__doc {
            background: #fff;
            position: relative;
            color: #000;
            font-size: 11px;
        }
        .be__doc-id {
            width: 60%;
            margin-bottom: 10px;
        }
        .be__doc-id__text {
            border-bottom: 1px solid #000;
            font-size: 14px;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        td, th {
            padding: .05rem !important;
        }
        td.be__word_wrap {
            max-width: 120px;
            padding-right: 0.3rem !important;
            padding-left: 0.3rem !important;
            word-wrap: break-word;
        }
        .be__tac {
            text-align: center;
        }
        .be__tar {
            text-align: right;
        }
        .be__tal {
            text-align: left;
        }
        .be__float-left {
            float: left;
        }
        .be__float-right {
            float: right;
        }
        .be__d-inline-block {
            display: inline-block
        }
        .be__border-bottom {
            border-bottom: 1px solid #000
        }
        .be__title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px auto 20px;
            max-width: 75%;
            text-align: center
        }
        .be__subtitle {
            margin-left: 20px;
            margin-right: 20px;
        }
        .be__products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 11px;
        }
        table, th, td {
            padding: 2px;
            border: 1px solid;
        }
        .be__w-10 {
            width: 10%;
        }
        .be__w-30 {
            width: 30%;
        }
        .be__w-45 {
            width: 45%;
        }
        .be__w-50 {
            width: 50%;
        }
        .be__w-250 {
            width: 250px;
            margin: auto;
        }
        .be__v-align-top {
            vertical-align: top;
        }
        .be__v-align-bottom {
            vertical-align: bottom;
        }
        .be__pr-10px {
            padding-right: 10px !important;
        }
        .be__mb-0 {
            margin-bottom: 0 !important;
        }
        .be__mb-5px {
            margin-bottom: 5px;
        }
        .be__mt-0 {
            margin-top: 0;
        }
        .be__mt-5px {
            margin-top: 5px;
        }
        .be__mt-15px {
            margin-top: 15px;
        }
        .be__text-bold {
            font-weight: bold;
        }
        .mt-20 {
            margin-top: 20px;
        }

        .be__title.contract {
            margin: 10px auto 0;
        }
        .be__text {
            margin: 5px;
            font-size: 14px;
            word-wrap: break-word;
        }
        .be__word-wrap {
            white-space: pre-wrap;
            overflow-wrap: break-word;
            text-align: justify;
        }
        .float__left {
            float: left;
        }
        .float__right {
            float: right;
        }
        .bold {
            font-weight: bold;
        }
        .date__places {
            min-height: 80px;
        }
        .contract.mb-15, .mb-15 {
            margin-bottom: 15px;
        }
        .contract.mb-30, .mb-30 {
            margin-bottom: 30px;
        }
        .contract.mt-30, .mt-30 {
            margin-top: 30px;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .column {
            float: left;
            width: 50%;
        }
        .column .be__title {
            text-align: left;
            margin: 0 0 15px 0;
        }
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
        .client .be__text {
            margin-bottom: 10px;
        }
        .contract__doc {
            margin: 0.5cm 2cm;
        }
        .page-break-inside {
            page-break-inside: avoid
        }
        .word-line {
            white-space: pre-line;
            overflow-wrap: break-word;
        }

        .be__signed {
            margin-bottom: 10px;
        }
        .be__signed__title {
            margin-bottom: 15px;
            font-size: 18px;
        }
        .be__signed__wrap,
        .be__signed__wrap__new {
            margin: 0 -10px;
            width: 100%;
        }
        .be__signed__side {
            display: inline-block;
            padding: 8px;
            position: relative;
            width: 300px;
            margin-top: 15px;
        }
        .be__signed__num {
            position: absolute;
            left: 0;
            top: -28px;
            bottom: 0;
            color: #fdc2a8;
            font-size: 120px;
        }
        .be__signed__content {
            position: relative;
            min-height: 48px;
        }
        .be__signed__text--bold {
            text-transform: lowercase;
        }
        .be__signed__text--links {
            color: #2cabe3;
        }
        .be__signed__header {
            display: flex;
            justify-content: space-between;
        }
        .be__signed__status {
            font-size: 18px;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            padding: 20px 0;
        }
        .be__signed__status .gray {
            color: #babcbc;
        }
        .be__signed__status .orange {
            color: #f8ac59;
        }
        .be__signed__status .red {
            color: #ed5565;
        }
        .be__signed__status .green {
            color: #1ab394;
        }
        .be__signed__bottom {
            text-align: left;
        }
        .be__signed__wrap__new tr td:not(:first-child) {
            text-align: right;
        }
        .gray-block {
            background-color: rgba(186, 188, 188, 0.1);
            border: 5px #babcbc solid;
            border-radius: 10px;
        }
        .green-block {
            background-color: rgba(26, 179, 148, 0.1);
            border: 5px #1ab394 solid;
            border-radius: 10px;
        }
        .red-block {
            background-color: rgba(237, 85, 101, 0.1);
            border: 5px #ed5565 solid;
            border-radius: 10px;
        }
        .orange-block {
            background-color: rgba(248, 172, 89, 0.1);
            border: 5px #f8ac59 solid;
            border-radius: 10px;
        }
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
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
        }


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

        .be__sides {margin: 20px 0;}
        .be__sides__table_wrap:first-child {padding-left: 0; }
        .be__sides__table_wrap {padding: 0 10px; display: inline-block; width: 47%; vertical-align: top }
        .be__sides__table_wrap:last-child {padding-right: 0; }
        .be__sides__table {border: none; font-size: 12px; width: 100%; }
        .be__sides__table tr td {padding: 5px 0; vertical-align: top;}

        .be__doc-info__title{ font-size: 16px; font-weight: bold; margin: 30px auto 60px; max-width: 75%; text-align: center; }
        .be__doc-info__list {margin: 0 auto;padding: 0;list-style: none;max-width: 500px;width: 100%;}
        .be__doc-info__label {border-bottom: 1px solid #dadce0;padding: 5px;width: 100%;}
        .be__doc-info__value {border-bottom: 1px solid #dadce0;padding: 5px;white-space: nowrap;}

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
        .be__mp__row_title--va{ vertical-align: top;}

        table.be__signed__wrap td,
        table.be__doc-info__list td,
        table.be__sides__table td {
            border :none;
        }
        .be__signed__wrap, .be__doc-info__list{
            border:0px;
        }
    </style>




    <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5 flex justify-center " xmlns="http://www.w3.org/1999/html">
        <div class="container">
            <div class="w-full rounded-lg border border-bgray-300 text-base text-bgray-900 focus:border-none focus:ring-0 dark:border dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">

                <div class="be__section">
                    <div class="be__doc">
                        <div class="be__signed">
                            <table class="be__signed__wrap">
                                <tbody>
                                <tr>
                                    <td>


                                        <div class="be__signed__title">@isset($owner->serial)Документ подписан:@endisset</div>
                                        <div class="be__signed__side" style="margin-bottom: 15px">
                                            <div class="be__signed__num">1</div>
                                            <div class="be__signed__content">
                                                <div class="be__signed__text"><span class="be__signed__text--bold">Дата:</span> {{$owner->signing_time??''}}</div>
                                                <div class="be__signed__text"><span class="be__signed__text--bold">серийный номер:</span> {{$owner->serial??''}}</div>
                                                <div class="be__signed__text"><span class="be__signed__text--bold">Компания:</span>{{$owner->company??''}}</div>
                                                <div class="be__signed__text"><span class="be__signed__text--bold">ИНН:</span>{{$owner->tin??''}}</div>
                                                <div class="be__signed__text"><span class="be__signed__text--bold">ФИО:</span> {{$owner->fio??''}}</div>
                                                <div class="be__signed__text be__signed__text--links"><span class="be__signed__text--bold">email:</span> <a href="mailto:"></a></div>
                                            </div>
                                        </div>

                                    </td>
                                    <td style="display: block;">

                                        <div class="be__signed__title">@isset($client->serial)Документ подписан:@endisset</div>
                                        <div class="be__signed__side" style="margin-bottom: 15px">
                                            <div class="be__signed__num">2</div>
                                            <div class="be__signed__content">
                                                <div class="be__signed__text"><span class="be__signed__text--bold">Дата:</span> {{$client->signing_time??''}}</div>
                                                <div class="be__signed__text"><span class="be__signed__text--bold">серийный номер:</span> {{$client->serial??''}}</div>
                                                <div class="be__signed__text"><span class="be__signed__text--bold">Компания:</span>{{$client->company??''}}</div>
                                                <div class="be__signed__text"><span class="be__signed__text--bold">ИНН:</span>{{$client->tin??''}}</div>
                                                <div class="be__signed__text"><span class="be__signed__text--bold">ФИО:</span> {{$client->fio??''}}</div>
                                                <div class="be__signed__text be__signed__text--links"><span class="be__signed__text--bold">email:</span> <a href="mailto:"></a></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td align="right"><img src="{{$qrcode}}" width="120" height="120"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="be__doc-id">
                            <div class="be__doc-id__text">
                                <div class="be__doc-id__text"><strong>{{$doc->doc_id}}</strong></div>
                            </div>
                            <div class="be__doc-id__title">идентификатор электронного документа</div>
                        </div>

                        <div class="be__title">ПРОТОКОЛ ПОДПИСАНИЯ ДОКУМЕНТА</div>
                        <div class="be__sides doc000_1__sides">
                            <div class="be__sides__table_wrap">
                                <table class="be__sides__table">
                                    <tbody>
                                    <tr>
                                        <td class="be__w-30">Поставщик</td>
                                        <td><div class="be__border-bottom">{{$owner->company}}</div></td>
                                    </tr>
                                    <tr>
                                        <td class="be__w-30">ИНН</td>
                                        <td><div class="be__border-bottom">{{$owner->tin}}</div></td>
                                    </tr>
                                    <tr>
                                        <td class="be__w-30">Адрес</td>
                                        <td><div class="be__border-bottom">{{$owner->address}}</div></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="be__sides__table_wrap">
                                <table class="be__sides__table">
                                    <tbody>
                                    <tr>
                                        <td class="be__w-30">Покупатель:</td>
                                        <td>
                                            <div class="be__border-bottom">
                                                {{$client->company}}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="be__w-30">ИНН</td>
                                        <td>
                                            <div class="be__border-bottom">{{$client->tin}}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="be__w-30">Адрес</td>
                                        <td>
                                            <div class="be__border-bottom">{{$client->address}}</div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="be__doc-info">
                            <div class="be__doc-info__title">Информация о документе</div>
                            <table class="be__doc-info__list">
                                <tbody>
                                <tr class="be__doc-info__item">
                                    <td class="be__doc-info__label">Название документа</td>
                                    <td class="be__doc-info__value">Dogovor </td>
                                </tr>
                                <tr class="be__doc-info__item">
                                    <td class="be__doc-info__label">Номер и дата документа</td>
                                    <td class="be__doc-info__value">
                                        № {{$doc->number}} от {{$doc->date}}
                                    </td>
                                </tr>
                                <tr class="be__doc-info__item">
                                    <td class="be__doc-info__label">По договору</td>
                                    <td class="be__doc-info__value"> {{ !empty($doc->contract) ? $doc->contract->contract_number : ''}} от {{!empty($doc->contract) ? $doc->contract->contract_date : ''}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection
