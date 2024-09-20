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

        @include('alert-profile')

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h4 class="card-title">{{__('main.nomenklatures')}}</h4>
                        <div>
                            @if(true || (!empty($tarif) && $tarif->checkTarifIsActive()))
                                <a href="{{ localeRoute('frontend.profile.modules.nomenklature.create') }}" class="btn-create bg-warning">{{__('main.create')}}</a>
                            @endif

                        </div>
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
{{--
                                    <th class="sorting" data-sort="status"><strong>{{__('main.status')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('status') !!}</th>
--}}
                                    <th class="sorting" data-sort="title_{{ app()->getLocale() }}"><strong>{{__('main.title')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('title_'.app()->getLocale()) !!}</th>
                                    <th class="sorting" data-sort="company_id"><strong>{{__('main.company')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('company_id') !!}</th>
                                    <th class="sorting" data-sort="ikpu_id"><strong>{{__('main.ikpu')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('ikpu_id') !!}</th>
                                    <th class="sorting" data-sort="unit_id"><strong>{{__('main.unit')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('unit_id') !!}</th>
                                    <th class="sorting" data-sort="quantity"><strong>{{__('main.quantity')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('quantity') !!}</th>
                                    <th class="sorting" data-sort="status"><strong>{{__('main.status')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('status') !!}</th>
                                  {{--  <th class="sorting" data-sort="category_id"><strong>{{__('main.category')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('category_id') !!}</th>
                                    <th class="sorting" data-sort="unit_id"><strong>{{__('main.unit')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('unit_id') !!}</th>
                                    <th class="sorting" data-sort="nds_id"><strong>{{__('main.nds')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('nds_id') !!}</th>
                                    <th class="sorting" data-sort="article"><strong>{{__('main.article')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('article') !!}</th>
                                   --}} <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(isset($nomenklatures))
                                    @foreach($nomenklatures as $nomenklature)
                                        <tr>
                                            <?php /* <td>
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
														<label class="custom-control-label" for="customCheckBox2"></label>
													</div>
												</td> */ ?>

                                            <td>{{ $nomenklature->getTitle() }}	</td>
                                            <td>@isset($nomenklature->company) {{ $nomenklature->company->name }}  @else  '' @endif	</td>
                                            <td>@isset($nomenklature->ikpu) {{ $nomenklature->ikpu->code . ' - ' . \Illuminate\Support\Str::limit($nomenklature->ikpu->getTitle(),32) }} @else  '' @endif </td>
                                            <td>@isset($nomenklature->unit) {{ $nomenklature->unit->getTitle() }} @else  '' @endif </td>
                                            <td>{{ $nomenklature->quantity }}	</td>
                                            <td>{!! $nomenklature->getStatusLabel() !!}	</td>
                                            {{--          <td>{{ $nomenklature->category->getTitle() }}</td>
                                                      <td>{{ $nomenklature->unit->getTitle() }}</td>
                                                      <td>{{ $nomenklature->nds->getTitle() }}</td>
                                                      <td>{{ $nomenklature->article }}</td>--}}
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ localeRoute('frontend.profile.modules.nomenklature.edit',$nomenklature) }}" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-edit"></i></a>
                                                    <form method="post" action="{{ localeRoute('frontend.profile.modules.nomenklature.destroy',$nomenklature) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>
                        </div>
                        {{ $nomenklatures->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}

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
