{{-- Extends layout --}}
@extends('layout.default')

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

        @include('alert')

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('main.modules')}}</h4>
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
                                    <th class="sorting" data-sort="region_id"><strong>{{__('main.region')}}</strong> {!! \App\Helpers\QueryHelper::getDirectionLabel('region_id') !!}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(isset($modules))
                                    @foreach($modules as $module)
                                        <tr>
                                            <?php /* <td>
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
														<label class="custom-control-label" for="customCheckBox2"></label>
													</div>
												</td> */ ?>

                                            <td>{{ $module->getTitle() }}	</td>
                                            <td>{{ $module->region->getTitle() }}</td>
                                            <td>
                                                <div class="payment-select relative">
                                                    <button onclick="dateFilterAction('#cardsOptions{{$module->id}}')" type="button">
                                                        <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8 2C8 2.55228 8.44772 3 9 3C9.55228 3 10 2.55228 10 2C10 1.44772 9.55228 1 9 1C8.44772 1 8 1.44772 8 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M1 2C1 2.55228 1.44772 3 2 3C2.55228 3 3 2.55228 3 2C3 1.44772 2.55228 1 2 1C1.44772 1 1 1.44772 1 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M15 2C15 2.55228 15.4477 3 16 3C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1C15.4477 1 15 1.44772 15 2Z" stroke="#CBD5E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </button>
                                                    <div id="cardsOptions{{$module->id}}" class="rounded-lg shadow-lg min-w-[100px] bg-white dark:bg-darkblack-500 absolute right-10 z-10 top-full hidden overflow-hidden" style="display: none;">
                                                        <ul style="min-width: 100px; text-align: center">
                                                            <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                                                <a href="{{localeRoute('admin.module.edit',$module)}}" class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                                                    {{ __('main.edit') }}
                                                                </a>
                                                            </li>
                                                            <li class="text-sm text-bgray-900 cursor-pointer px-5 py-2 hover:bg-bgray-100 hover:dark:bg-darkblack-600 dark:text-white font-semibold">
                                                                <form action="{{localeRoute('admin.module.destroy',$module)}}}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button class="inline-flex h-8 w-8 translate-y-0 transform items-center justify-center transition duration-300 ease-in-out hover:-translate-y-1">
                                                                        {{ __('main.delete') }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>
                        </div>
                        {{ $modules->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}

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
