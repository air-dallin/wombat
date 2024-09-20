@empty($openai)
@extends('layouts.profile')
@endempty
@section('title')
    <h3 class="text-xl font-bold text-bgray-900 dark:text-white sm:text-2xl">{{__('main.guarant')}}: {{$guarant->guarant_number}} от {{$guarant->guarant_date}}</h3>
@endsection

@empty($openai)

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
@endempty
{{-- Content --}}
@section('content')

    @empty($openai)
    @include('alert-profile')

    <div class="flex h-[56px] w-full space-x-4 mb-8">

        @if($guarant->status!=\App\Services\DidoxService::STATUS_DELETED)
            <div class="flex items-center sm:w-20 lg:w-88">
                <a href="{{localeRoute('frontend.profile.modules.guarant.check-status',$guarant)}}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-warning-300 text-white transition duration-300 ease-in-out hover:bg-success-400" title="{{__('main.check_status')}}" style="max-width: 200px;">
                    {{__('main.check_status')}}
                </a>
                <span class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-white p-4 text-base text-bgray-600 transition duration-300 ease-in-out hover:border-success-300 dark:bg-darkblack-600 dark:text-white" style="margin-left: 15px"> {!! \App\Services\DidoxService::getStatusLabel($guarant->doc_status) !!}{{ \App\Services\DidoxService::getStatus($guarant->doc_status) }}</span>
            </div>

            <div class="relative h-full">
                <div class="flex h-full  space-x-4">

                    @if($guarant->isNotSigned())
                        <div class="modal-open cursor-pointer flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-success-300 text-white transition duration-300 ease-in-out hover:bg-success-400" data-target="#multi-step-modal" style="min-width: 200px"> {{__('main.sign')}}</div>
                        <div id="document-json" style="display:none">{{$document}}</div>
                    @elseif(!empty($guarant->response_sign))
                        {{--@if($guarant->doc_status!=\App\Services\DidoxService::STATUS_SIGNED)--}}
                        @if($guarant->owner==\App\Services\DidoxService::OWNER_TYPE_INCOMING)
                            <div class="cursor-pointer flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-error-300 text-white transition duration-300 ease-in-out hover:bg-error-400" data-target="#multi-step-modal2" onclick="openModal('multi-step-modal2')" style="min-width: 200px"> {{__('main.refuse')}}</div>
                        @else
                            <div class="cursor-pointer flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-error-300 text-white transition duration-300 ease-in-out hover:bg-error-400" data-target="#multi-step-modal2" onclick="openModal('multi-step-modal2')" style="min-width: 200px"> {{__('main.cancel')}}</div>
                        @endif
                        {{--@endif--}}
                    @endif
                        <button onclick="openModal('chat_modal')" class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-white p-4 text-base text-bgray-600 transition duration-300 ease-in-out hover:border-success-300 dark:bg-darkblack-600 dark:text-white">
                            <svg fill="#A0AEC0" width="24" height="24" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.2819 9.8211a5.9847 5.9847 0 0 0-.5157-4.9108 6.0462 6.0462 0 0 0-6.5098-2.9A6.0651 6.0651 0 0 0 4.9807 4.1818a5.9847 5.9847 0 0 0-3.9977 2.9 6.0462 6.0462 0 0 0 .7427 7.0966 5.98 5.98 0 0 0 .511 4.9107 6.051 6.051 0 0 0 6.5146 2.9001A5.9847 5.9847 0 0 0 13.2599 24a6.0557 6.0557 0 0 0 5.7718-4.2058 5.9894 5.9894 0 0 0 3.9977-2.9001 6.0557 6.0557 0 0 0-.7475-7.0729zm-9.022 12.6081a4.4755 4.4755 0 0 1-2.8764-1.0408l.1419-.0804 4.7783-2.7582a.7948.7948 0 0 0 .3927-.6813v-6.7369l2.02 1.1686a.071.071 0 0 1 .038.052v5.5826a4.504 4.504 0 0 1-4.4945 4.4944zm-9.6607-4.1254a4.4708 4.4708 0 0 1-.5346-3.0137l.142.0852 4.783 2.7582a.7712.7712 0 0 0 .7806 0l5.8428-3.3685v2.3324a.0804.0804 0 0 1-.0332.0615L9.74 19.9502a4.4992 4.4992 0 0 1-6.1408-1.6464zM2.3408 7.8956a4.485 4.485 0 0 1 2.3655-1.9728V11.6a.7664.7664 0 0 0 .3879.6765l5.8144 3.3543-2.0201 1.1685a.0757.0757 0 0 1-.071 0l-4.8303-2.7865A4.504 4.504 0 0 1 2.3408 7.872zm16.5963 3.8558L13.1038 8.364 15.1192 7.2a.0757.0757 0 0 1 .071 0l4.8303 2.7913a4.4944 4.4944 0 0 1-.6765 8.1042v-5.6772a.79.79 0 0 0-.407-.667zm2.0107-3.0231l-.142-.0852-4.7735-2.7818a.7759.7759 0 0 0-.7854 0L9.409 9.2297V6.8974a.0662.0662 0 0 1 .0284-.0615l4.8303-2.7866a4.4992 4.4992 0 0 1 6.6802 4.66zM8.3065 12.863l-2.02-1.1638a.0804.0804 0 0 1-.038-.0567V6.0742a4.4992 4.4992 0 0 1 7.3757-3.4537l-.142.0805L8.704 5.459a.7948.7948 0 0 0-.3927.6813zm1.0976-2.3654l2.602-1.4998 2.6069 1.4998v2.9994l-2.5974 1.4997-2.6067-1.4997Z"/>
                            </svg>
                            <span>WombatAI</span>
                        </button>
                    <a href="{{localeRoute('frontend.profile.modules.guarant.print',$guarant->id)}}" class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-white p-4 text-base text-bgray-600 transition duration-300 ease-in-out hover:border-success-300 dark:bg-darkblack-600 dark:text-white" target="_blank">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 17H19C19.5304 17 20.0391 16.7893 20.4142 16.4142C20.7893 16.0391 21 15.5304 21 15V11C21 10.4696 20.7893 9.96086 20.4142 9.58579C20.0391 9.21071 19.5304 9 19 9H5C4.46957 9 3.96086 9.21071 3.58579 9.58579C3.21071 9.96086 3 10.4696 3 11V15C3 15.5304 3.21071 16.0391 3.58579 16.4142C3.96086 16.7893 4.46957 17 5 17H7" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M17 9V5C17 4.46957 16.7893 3.96086 16.4142 3.58579C16.0391 3.21071 15.5304 3 15 3H9C8.46957 3 7.96086 3.21071 7.58579 3.58579C7.21071 3.96086 7 4.46957 7 5V9" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15 13H9C7.89543 13 7 13.8954 7 15V19C7 20.1046 7.89543 21 9 21H15C16.1046 21 17 20.1046 17 19V15C17 13.8954 16.1046 13 15 13Z" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span>{{ __('main.print') }}</span>
                    </a>
                    <a href="{{localeRoute('frontend.profile.modules.guarant.download',$guarant->id)}}" target="_blank" class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-white p-4 text-base text-bgray-600 transition duration-300 ease-in-out hover:border-success-300 dark:bg-darkblack-600 dark:text-white" style="min-width: 118px;">
                        <span>{{ __('main.download') }}</span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6L8 10L12 6" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                    {{--              <a href="{{}}" target="_blank" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500" style="border: 1px solid #718096; color: #718096; min-width: 200px;">{{__('main.download_archiev')}}</a>--}}
                    {{--              <a href="{{localeRoute('frontend.profile.modules.guarant.exportPdf',$guarant->id)}}" type="button" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500" target="_blank" style="border: 1px solid #718096; color: #718096; min-width: 200px;">{{__('main.download_pdf')}}</a>--}}
                    {{--              <a href="{{localeRoute('frontend.profile.modules.guarant.print',$guarant->id)}}" type="button" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500" target="_blank" style="border: 1px solid #718096; color: #718096; min-width: 200px;">{{__('main.print')}}</a>--}}
                    {{--      @if($guarant->status!=\App\Services\DidoxService::STATUS_DELETED)
                            @if($guarant->isNotSigned())
                              <div class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-success-300 text-white transition duration-300 ease-in-out hover:bg-success-400 sign-btn" data-toggle="modal" data-target="#signDocumentPopup" style="min-width: 200px;">{{__('main.sign')}}</div>
                              <div id="document-json" style="display:none">{{$document}}</div>
                            @elseif(!empty($guarant->response_sign))
                              <div class="btn btn-danger reject" data-toggle="modal" data-target="#rejectDocumentPopup">{{__('main.reject')}}</div>
                              <div class="badge badge-success">{{__('main.signed')}}</div>
                            @endif

                            <a href="{{localeRoute('frontend.profile.modules.guarant.check-status',$guarant)}}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500 btn-icon" title="{{__('main.update_from_didox')}}" style="border: 1px solid #718096; color: #718096; min-width: 100px;"><i class="fa fa-download"></i></a>
                          @else
                            <span class="badge badge-danger">{{__('main.deleted')}}</span>
                          @endif--}}
                </div>
            </div>
        @else
            <div class="sm:w-20 lg:w-88">
                {!! \App\Services\DidoxService::getStatusLabel($guarant->doc_status) !!}{{ \App\Services\DidoxService::getStatus($guarant->doc_status) }}</span>
            </div>

        @endif
    </div>




    {{--   <div class="row">
        <div class="col-12">
            <div class="card">

             {{--   <div"card-header">
                    <div class="w-100 d-flex justify-content-between">
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
--}}{{--
                                @if($guarant->doc_status!=\App\Services\DidoxService::STATUS_SIGNED)
--}}{{--
                                    @if($guarant->owner==\App\Services\DidoxService::OWNER_TYPE_INCOMING)
                                        <div class="btn btn-danger reject" data-toggle="modal" data-target="#rejectDocumentPopup">{{__('main.reject')}}</div>
                                    @else
                                        <div class="btn btn-danger reject" data-toggle="modal" data-target="#rejectDocumentPopup">{{__('main.cancel')}}</div>
                                    @endif
                                --}}{{--@endif--}}{{--
--}}{{--
                                <div class="badge badge-success">{{__('main.signed')}}</div>
--}}{{--
                            @endif

                            <a href="{{localeRoute('frontend.profile.modules.guarant.check-status',$guarant)}}" class="btn btn-primary btn-icon" title="{{__('main.update_from_didox')}}"><i class="fa fa-download"></i></a>

                        @else
                            <span class="badge badge-danger">{{__('main.deleted')}}</span>
                        @endif
                    </div>
                </div>--}}

{{--
                    <div class="card-body">
--}}

    @endempty
                    <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5 flex justify-center " xmlns="http://www.w3.org/1999/html">
                        <div class="container">
                            <div class="w-full rounded-lg border border-bgray-300 text-base text-bgray-900 focus:border-none focus:ring-0 dark:border dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">

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
                                </div>
                                <div class="be__doc006">
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
                                                        <td class="be__tac">@isset($item->unit) {{$item->unit->getTitle()}} @else штук @endisset</td>
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

@endsection

@empty($openai)

<style>
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
</style>
<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>


<!-- Modal sign -->
<div class="modal fixed inset-0 z-50 h-full overflow-y-auto flex items-center justify-center hidden" id="multi-step-modal">
    <div class="modal-overlay absolute inset-0 bg-gray-500 opacity-75 dark:bg-bgray-900 dark:opacity-50"></div>
    <div class="modal-content md:w-full max-w-3xl px-4">
        <!-- My Content -->
        <div class="max-w-[750px] rounded-lg bg-white dark:bg-darkblack-600 p-6 transition-all relative">
            <header>
                <div>
                    <h3 class="font-bold text-bgray-900 dark:text-white text-2xl mb-1">
                        {{__('main.sign_document')}}
                    </h3>
                </div>
                <div class="absolute top-0 right-0 pt-5 pr-5">
                    <button type="button" id="step-1-cancel" class="rounded-md bg-white dark:bg-darkblack-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <!-- Cross Icon -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 6L18 18M6 18L18 6L6 18Z" stroke="#747681" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </header>
            <div class="pt-4">
                <div class="modal-body">
                    <form name="eimzo">
                        <div class="flex flex-col mb-3 gap-2">
                            <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.choice_eimzo_key')}}</label>
                            <select name="key" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " onchange="cbChanged(this)">
                                <option value="" vo="" id=""></option>
                            </select><br>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div><b>{{__('main.company')}}:</b> <span id="eimzo_company"></span></div>
                                <div><b>{{__('main.inn')}}:</b> <span id="eimzo_tin"></span></div>
                                <div><b>{{__('main.pinfl')}}:</b> <span id="eimzo_pinfl"></span></div>
                                <div><b>{{__('main.date_from')}}:</b> <span id="eimzo_date_from"></span></div>
                                <div><b>{{__('main.date_to')}}:</b> <span id="eimzo_date_to"></span></div>
                            </div>
                        </div>
                        <div style="display: none;">
                            <input type="text" name="document_id" value="{{$guarant->id}}">
                            <input type="text" name="object" value="Guarant">
                            <input type="text" name="company_id" id="company_id" value="{{$guarant->company_id}}">
                            <input type="text" id="hex" value="">
                            <input type="text" id="message" value="">
                            <input type="text" id="serial" value="">
                            <input type="text" id="status" value="{{$guarant->doc_status}}">
                            <input type="text" id="inn" value="{{base64_encode($guarant->company->inn)}}">
                            <input type="text" id="tokenExpire" value="{{$guarant->company->checkTokenExpire()}}">
                            <textarea id="tst" style="height: 144px;"></textarea>
                            <textarea name="data" id="data" style="height: 144px;"></textarea><br><label id="keyId"></label><br><textarea name="pkcs7" style="height: 144px;"></textarea><br>
                            <textarea id="res" style="height: 144px;"></textarea>

                        </div>
                    </form>
                </div>
                <div class="py-2 flex justify-end items-center space-x-4">
                    {{--              <button class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" data-dismiss="modal"  style="border: 1px solid #718096; color: #718096;">{{__('main.close')}}</button>--}}
{{--
                    <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" id="close_modal" data-dismiss="modal" style="border: 1px solid #718096; color: #718096">{{__('main.close')}}</button>
--}}
                    <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" id="sign-document" onclick="sign()" _data-dismiss="_modal" style="background: orange">{{__('main.next')}}</button>
                    <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" id="complete" onclick="addTimestamp()" style="display: none;" _data-dismiss="_modal" style="background: orange">{{__('main.sign_document')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal reject -->
<div class="modal fixed inset-0 z-50 h-full overflow-y-auto flex items-center justify-center hidden" id="multi-step-modal2">
    <div class="modal-overlay absolute inset-0 bg-gray-500 opacity-75 dark:bg-bgray-900 dark:opacity-50"></div>
    <div class="modal-content md:w-full max-w-3xl px-4">
        <!-- My Content -->
        <div class="max-w-[750px] rounded-lg bg-white dark:bg-darkblack-600 p-6 transition-all relative">
            <header>
                <div>
                    <h3 class="font-bold text-bgray-900 dark:text-white text-2xl mb-1">
                        {{__('main.reject')}}
                    </h3>
                </div>
                <div class="absolute top-0 right-0 pt-5 pr-5">
                    <button type="button" id="step-1-cancel" class="rounded-md bg-white dark:bg-darkblack-500 focus:outline-none" onclick="closeModal('multi-step-modal2')">
                        <span class="sr-only">Close</span>
                        <!-- Cross Icon -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 6L18 18M6 18L18 6L6 18Z" stroke="#747681" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </header>
            <div class="pt-4">
                <div class="modal-body">
                    <form name="reject">
                        <label class="form-label">{{__('main.comment')}}</label>
                        <textarea name="comment" id="comment" style="height: 144px; width: 100%;"></textarea>
                        <div style="display: none;">
                            <textarea id="sign" style="height: 144px;">{{$sign}}</textarea>
                        </div>
                    </form>
                </div>
                <div class="py-2 flex justify-end items-center space-x-4">
                    {{--              <button class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" data-dismiss="modal"  style="border: 1px solid #718096; color: #718096;">{{__('main.close')}}</button>--}}
{{--
                    <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" id="close_modal" data-dismiss="modal" style="border: 1px solid #718096; color: #718096" onclick="closeModal('multi-step-modal2')">{{__('main.close')}}</button>
--}}
                    <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" id="reject" _data-dismiss="_modal" style="background: red">{{__('main.reject')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.profile.sections.eimzo')
@include('frontend.profile.sections.eimzo')
@include('frontend.profile.sections.openai_chat',['chatItems'=>$chatItems])
@endempty


