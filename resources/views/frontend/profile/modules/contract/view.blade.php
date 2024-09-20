@empty($openai)
    @extends('layouts.profile')
@endempty
@section('title')
  <h3 class="text-xl font-bold text-bgray-900 dark:text-white sm:text-2xl">Договор (ГНИ): {{$contract->contract_number}} от {{$contract->contract_date}}</h3>
@endsection

@empty($openai)

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
</style>

@endempty

@section('content')


@empty($openai)

  @include('alert-profile')
  <div class="flex h-[56px] w-full space-x-4 mb-8">

    @if($contract->status!=\App\Services\DidoxService::STATUS_DELETED)
      <div class="flex items-center sm:w-20 lg:w-88">
        <a href="{{localeRoute('frontend.profile.modules.contract.check-status',$contract)}}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-warning-300 text-white transition duration-300 ease-in-out hover:bg-success-400" title="{{__('main.check_status')}}" style="max-width: 200px;">
          {{__('main.check_status')}}
        </a>
        <span class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-white p-4 text-base text-bgray-600 transition duration-300 ease-in-out hover:border-success-300 dark:bg-darkblack-600 dark:text-white" style="margin-left: 15px"> {!! \App\Services\DidoxService::getStatusLabel($contract->doc_status) !!}{{ \App\Services\DidoxService::getStatus($contract->doc_status) }}</span>
      </div>

      <div class="relative h-full">
        <div class="flex h-full  space-x-4">
          @if($contract->isNotSigned())
            <div class="modal-open cursor-pointer flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-success-300 text-white transition duration-300 ease-in-out hover:bg-success-400" data-target="#multi-step-modal" style="min-width: 200px"> {{__('main.sign')}}</div>
            <div id="document-json" style="display:none">{{$document}}</div>

          @elseif(!empty($contract->response_sign) && !empty($sign))
                {{--@if($contract->doc_status!=\App\Services\DidoxService::STATUS_SIGNED)--}}
                    @if($contract->owner==\App\Services\DidoxService::OWNER_TYPE_INCOMING)
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
          <a href="{{localeRoute('frontend.profile.modules.contract.print',$contract->id)}}" class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-white p-4 text-base text-bgray-600 transition duration-300 ease-in-out hover:border-success-300 dark:bg-darkblack-600 dark:text-white" target="_blank">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M17 17H19C19.5304 17 20.0391 16.7893 20.4142 16.4142C20.7893 16.0391 21 15.5304 21 15V11C21 10.4696 20.7893 9.96086 20.4142 9.58579C20.0391 9.21071 19.5304 9 19 9H5C4.46957 9 3.96086 9.21071 3.58579 9.58579C3.21071 9.96086 3 10.4696 3 11V15C3 15.5304 3.21071 16.0391 3.58579 16.4142C3.96086 16.7893 4.46957 17 5 17H7" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              <path d="M17 9V5C17 4.46957 16.7893 3.96086 16.4142 3.58579C16.0391 3.21071 15.5304 3 15 3H9C8.46957 3 7.96086 3.21071 7.58579 3.58579C7.21071 3.96086 7 4.46957 7 5V9" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              <path d="M15 13H9C7.89543 13 7 13.8954 7 15V19C7 20.1046 7.89543 21 9 21H15C16.1046 21 17 20.1046 17 19V15C17 13.8954 16.1046 13 15 13Z" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <span>{{ __('main.print') }}</span>
          </a>
          <a href="{{localeRoute('frontend.profile.modules.contract.download',$contract->id)}}" target="_blank" class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-white p-4 text-base text-bgray-600 transition duration-300 ease-in-out hover:border-success-300 dark:bg-darkblack-600 dark:text-white" style="min-width: 118px;">
            <span>{{ __('main.download') }}</span>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M4 6L8 10L12 6" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>

            </svg>
          </a>
          {{--              <a href="{{localeRoute('frontend.profile.modules.contract.download',$contract->id)}}" target="_blank" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500" style="border: 1px solid #718096; color: #718096; min-width: 200px;">{{__('main.download_archiev')}}</a>--}}
          {{--              <a href="{{localeRoute('frontend.profile.modules.contract.exportPdf',$contract->id)}}" type="button" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500" target="_blank" style="border: 1px solid #718096; color: #718096; min-width: 200px;">{{__('main.download_pdf')}}</a>--}}
          {{--              <a href="{{localeRoute('frontend.profile.modules.contract.print',$contract->id)}}" type="button" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500" target="_blank" style="border: 1px solid #718096; color: #718096; min-width: 200px;">{{__('main.print')}}</a>--}}
          {{--      @if($contract->status!=\App\Services\DidoxService::STATUS_DELETED)
                  @if($contract->isNotSigned())
                    <div class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300  bg-success-300 text-white transition duration-300 ease-in-out hover:bg-success-400 sign-btn" data-toggle="modal" data-target="#signDocumentPopup" style="min-width: 200px;">{{__('main.sign')}}</div>
                    <div id="document-json" style="display:none">{{$document}}</div>
                  @elseif(!empty($contract->response_sign))
                    <div class="btn btn-danger reject" data-toggle="modal" data-target="#rejectDocumentPopup">{{__('main.reject')}}</div>
                    <div class="badge badge-success">{{__('main.signed')}}</div>
                  @endif

                  <a href="{{localeRoute('frontend.profile.modules.contract.check-status',$contract)}}" class="flex h-full w-full items-center justify-center rounded-lg border border-bgray-300 bg-bgray-100 dark:border-darkblack-500 dark:bg-darkblack-500 btn-icon" title="{{__('main.update_from_didox')}}" style="border: 1px solid #718096; color: #718096; min-width: 100px;"><i class="fa fa-download"></i></a>
                @else
                  <span class="badge badge-danger">{{__('main.deleted')}}</span>
                @endif--}}
        </div>
      </div>
    @else
      <div class="sm:w-20 lg:w-88">
        {!! \App\Services\DidoxService::getStatusLabel($contract->doc_status) !!}{{ \App\Services\DidoxService::getStatus($contract->doc_status) }}</span>
      </div>

    @endif
  </div>

@endempty


  <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5 flex justify-center " xmlns="http://www.w3.org/1999/html">
    <div class="container">
      <div class="w-full rounded-lg border border-bgray-300 text-base text-bgray-900 focus:border-none focus:ring-0 dark:border dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">


        <div class="be__section">
          <div class="be__doc contract__doc">
              <div class="be__signed float__right" style="position:relative">
                  <div style="text-align: right">
                      <img src="{{$qrcode}}" width="100" height="100">
                  </div>
              </div>
            <div class="be__doc-id">
              <div class="be__doc-id__text">
                <div>
                </div>
                <div>
                  <strong>ID документа (Rouming.uz): {{ isset($contract) ? $contract->contract_id : ''}}</strong>
                </div>
              </div>
              <div class="be__doc-id__title">идентификатор электронного документа</div>
            </div>
            <div class="be__title contract">
              <p class="be__text">Договор №{{$contract->contract_number}}</p>
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

            <div class="page-break"></div>

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


@empty($openai)

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
              <input type="text" name="document_id" value="{{$contract->id}}">
              <input type="text" name="object" value="Contract">
              <input type="text" name="company_id" id="company_id" value="{{$contract->company_id}}">
              <input type="text" id="hex" value="">
              <input type="text" id="message" value="">
              <input type="text" id="serial" value="">
              <input type="text" id="status" value="{{$contract->doc_status}}">
              <input type="text" id="inn" value="{{base64_encode($contract->company->inn)}}">
              <input type="text" id="tokenExpire" value="{{$contract->company->checkTokenExpire()}}">
              <textarea id="tst" style="height: 144px;"></textarea>
              <textarea name="data" id="data" style="height: 144px;"></textarea><br><label id="keyId"></label><br><textarea name="pkcs7" style="height: 144px;"></textarea><br>
              <textarea id="res" style="height: 144px;"></textarea>
            </div>
          </form>
        </div>
        <div class="py-2 flex justify-end items-center space-x-4">
          {{--              <button class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" data-dismiss="modal"  style="border: 1px solid #718096; color: #718096;">{{__('main.close')}}</button>
          <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" id="close_modal"
            onclick="closeModal('multi-step-modal')"
            data-dismiss="modal" style="border: 1px solid #718096; color: #718096">{{__('main.close')}}</button>
            --}}
          <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" id="sign-document" onclick="sign()" _data-dismiss="_modal" style="background: orange">{{__('main.next')}}</button>
          <button type="button" class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" id="complete" onclick="addTimestamp()" style="display: none;background: orange" _data-dismiss="_modal">{{__('main.sign_document')}}</button>
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
@include('frontend.profile.sections.openai_chat',['chatItems'=>$chatItems])
@endempty

