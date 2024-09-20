{{-- Extends layout --}}
@extends('layouts.print')


{{-- Content --}}
@section('content')
    <style>
        body {
            margin: 0;
            padding: 0;
            font: 8pt "Calibri";
            background: #fff;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .container {

            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            padding-top: 100px;
        }
        table {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 20px 0;
        }
        th , td {
            /*border: 1px solid #c3e6cb;*/
            padding: 5px;
            text-align: start;
        }
        .border-top {
            border-top: 1px solid #454545;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        span {
            padding: 5px 15px;
        }
    </style>
    <body>

    <div class="page">
        <div class="container">
            <table class="table">
                <thead>
                <tr>
                    <th colspan="3"></th>
                    <th>Мемориальный ордер</th>
                    <td>{{$paymentOrder->num}}</td>
                    <th>Отв. системный</th>
                    <th>пользователь</th>
                    <th>B2</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>ID</th>
                    <td colspan="7">{{$paymentOrder->general_id}}</td>
                </tr>
                <tr>
                    <th>Дата</th>
                    <td colspan="5">{{$paymentOrder->vdate}}</td>
                    <th>Изг.</th>
                    <th>{{date('d.m.Y')}}</th>
                </tr>
                <tr>
                    <th>Наименование плательщика</th>
                    <td>{{$paymentOrder->name_dt}}</td>
                    <td colspan="6">{{$paymentOrder->inn_dt}}</td>

                </tr>
                <tr>
                    <th>Дебет счет плательщика</th>
                    <td colspan="6">{{$paymentOrder->acc_dt}}</td>
                    <th>ИНН</th>
                </tr>
                <tr>
                    <th>Наим. банка плательщика</th>
                    <td colspan="5">{{$paymentOrder->company->bank_name}}</td>
                    <td>Код банка</td>
                    <td>{{$paymentOrder->mfo_dt}}</td>
                </tr>
                <tr>
                    <th>Сумма</th>
                    <td colspan="7">{{number_format($paymentOrder->amount,2,'.',',')}}</td>
                </tr>
                <tr>
                    <th>Наименование получателя</th>
                    <td colspan="7">{{$paymentOrder->name_ct}}</td>
                </tr>
                <tr>
                    <th>Кредит счет получателя</th>
                    <td colspan="7">{{$paymentOrder->acc_tt}}</td>
                </tr>
                <tr>
                    <th>Наим. банка получателя</th>
                    <td colspan="5">{{$client['bank_name']}}</td>
                    <td>Код банка</td>
                    <td>{{$paymentOrder->mfo_ct}}</td>
                </tr>
                <tr>
                    <th>Сумма прописью</th>
                    <td colspan="7">{{num2str($paymentOrder->amount)}}</td>
                </tr>
                <tr>
                    <th>Детали платежа</th>
                    <td colspan="7" class="border-top">{{$paymentOrder->purp_code}} - {{$paymentOrder->purpose}}</td>
                    {{--<td colspan="7" class="border-top">00667За iBank-24-1000.00,  за документ №900401 S=3500000 Тариф-СХЕМА 2 тариф корпорейт</td>--}}
                </tr>
{{--
                <tr>
                    <th></th>
                    <td colspan="7">0,1%д/о</td>
                </tr>
--}}
                <tr>
                    <th class="text-center" >Руководитель</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-center" >Главный бухгалтер</th>
                </tr>
                <tr>
                    <td class="text-right" colspan="2"><span class="border-top">подпись</span></td>
                    <td class="text-right" colspan="4"><span class="border-top">подпись</span></td>
                </tr>
                <tr>
                    <td class="text-right" colspan="2"><span class="">Проверен</span></td>
                    <td class="text-right" colspan="4"><span class="">Одобрен</span></td>
                    <td class="text-right" colspan="2"><span class="">Проверен</span></td>
                </tr>
                <tr>
                    <th class="" >М.П.   БАНК</th>
                    <td class="" colspan="2"><span class="border-top" style="margin-left: 10px">Подпись</span></td>
                    <td class="text-right" colspan="3"><span class="border-top" style="margin-left: 20px">Подпись</span></td>
                    <td class="text-right" colspan="4"><span class="">{{date('d.m.Y')}}</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection


@section('js')
    <script>
/*        $(document).ready(function () {

        })*/
window.onafterprint = window.close;
window.print();


    </script>
@endsection
