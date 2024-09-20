{{-- Extends layout --}}
@extends('layouts.profile')

{{-- Content --}}
@section('content')

    <div class="container-fluid">
        <?php /*<div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Города</a></li>
            </ol>
        </div> */ ?>
        <!-- row -->

            <?php /*

 */ ?>

        @include('alert-profile')

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('main.payments')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                <tr>
                                    <?php /* <th class="width50">
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="checkAll" required="">
														<label class="custom-control-label" for="checkAll"></label>
													</div>
												</th> */ ?>
                                    <th class="sorting" data-sort="title_{{ app()->getLocale() }}"><strong>{{__('main.title')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('title_'.app()->getLocale()) !!}</th>
                                    <th class="sorting" data-sort="amount"><strong>{{__('main.amount')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('amount') !!}</th>
                                    <th class="sorting" data-sort="payment_system"><strong>{{__('main.payment_system')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('payment_system') !!}</th>
                                    <th class="sorting" data-sort="created_at"><strong>{{__('main.payment_at')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('created_at') !!}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(isset($payments))
                                    @foreach($payments as $payment)
                                        <tr>
                                            <?php /* <td>
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
														<label class="custom-control-label" for="customCheckBox2"></label>
													</div>
												</td> */ ?>

                                            <td>{{ $payment->tarif->getTitle() }}</td>
                                            <td>{{ $payment->amount }}</td>
                                            <td>{{ $payment->payment_system }}</td>
                                            <td>{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>

                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>
                        </div>
                        {{ $payments->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}

                    </div>


                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sorting.js') }}"></script>
@endpush
