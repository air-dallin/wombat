{{-- Extends layout --}}
@extends('layout.default')


{{-- Content --}}
@section('content')


    <div class="container-fluid">
        @include('alert')
        <?php /*<div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Город</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{isset($payment)?$payment->title_ru :'' }}</a></li>
            </ol>
        </div> --*/ ?>
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('main.payment')}}</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data"
                            @isset($payment)
                              action="{{localeRoute('admin.payment.update',$payment)}}"
                              @else
                              action="{{localeRoute('admin.payment.store')}}"
                            @endisset
                        >
                        @csrf
                        @isset($payment)
                            @method('PUT')
                        @endisset


                        <!-- Nav tabs -->


                           форма с информацией для просмотра платежа




                        <div class="form-group">
                            <button type="submit" class="btn btn-success">{{__('main.save')}}</button>
                        </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/image.js')}}"></script>
@endpush
