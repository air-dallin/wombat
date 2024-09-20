<div class="card-header justify-content-between">

    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-4 mt-5">
        <div class="flex flex-col gap-2">
            <input type="date" id="from" value="{{$from}}" class="date h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white">
        </div>
        <div class="flex flex-col gap-2">
            <input type="date" id="to" value="{{$to}}" class="date h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white">
        </div>
        <div class="flex flex-col gap-2">
            <button id="filter" class="modal-open cursor-pointer h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 bg-orange text-white">...</button>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fixed inset-0 z-50 h-full overflow-y-auto flex items-center justify-center hidden" id="multi-step-modal">
    <div class="modal-overlay absolute inset-0 bg-gray-500 opacity-75 dark:bg-bgray-900 dark:opacity-50"></div>
    <div class="modal-content md:w-full max-w-3xl px-4">
        <div class="step-content step-1">
            <!-- My Content -->
            <div class="max-w-[750px] rounded-lg bg-white dark:bg-darkblack-600 p-6 transition-all relative">
                <header>
                    <div>
                        <h3 class="font-bold text-bgray-900 dark:text-white text-2xl mb-1">
                            {{__('main.period_settings')}}
                        </h3>
                    </div>
                    <div class="absolute top-0 right-0 pt-5 pr-5">
                        <button type="button" id="step-1-cancel" class="rounded-md bg-white dark:bg-darkblack-500 focus:outline-none">
                            <span class="sr-only">{{__('main.close')}}</span>
                            <!-- Cross Icon -->
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 6L18 18M6 18L18 6L6 18Z" stroke="#747681" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </header>
                <div class="pt-4">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="product-inputs">

                                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2 mb-3">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                        <input type="checkbox" name="work_period" id="year" class="h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.work_period')}}</label>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-3 mb-3">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                        <input type="radio" name="period" id="year" checked class="radio-field h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.year')}}</label>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <select data-filter="1" class="filter_field h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" data-type="year" id="year" style="width: 100% !important;">
                                            @for($i=date('Y');$i>=2020;$i--)
                                                <option value="{{\App\Helpers\UtilsHelper::getFilter('year',$i)}}" title="{{$i}}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                        <input type="checkbox" name="work_period" id="year" class="h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.begin_year')}}</label>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-3 mb-3">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                        <input type="radio" name="period" id="quarter" class="radio-field h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.quarter')}}</label>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <select data-filter="1" class="filter_field h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" data-type="quarter" id="quarter" style="width: 100% !important;">
                                            @for($i=1;$i<=4;$i++)
                                                <option value="{{\App\Helpers\UtilsHelper::getFilter('quarter',$i)}}">{{ $i }} квартал {{date('Y') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                            <input type="checkbox" name="work_period" id="year" class="h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.begin_quarter')}}</label>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-3 mb-3">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                            <input type="radio" name="period" id="month" class="radio-field h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.month')}}</label>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <select data-filter="1" class="filter_field h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white" data-type="month" id="month" style="width: 100% !important;">
                                            @for($i=1;$i<=12;$i++)
                                                <option value="{{\App\Helpers\UtilsHelper::getFilter('month',$i)}}">{{ \App\Helpers\UtilsHelper::getMonthLabel($i) }} {{date('Y') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                            <input type="checkbox" name="work_period" id="year" class="h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.begin_month')}}</label>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-3 mb-3">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                            <input type="radio" name="period" id="day" class="radio-field h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.day')}}</label>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <div class="flex flex-col gap-2">
                                            <input type="date" id="from" data-type="day" class="filter_field h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white">
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <input type="hidden" id="to" data-type="day" class="filter_field h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6 2xl:grid-cols-3 mb-3">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">
                                        <input type="radio" name="period" id="range" class="radio-field h-2 border-1 bg-bgray-50 p-4 focus:border  focus:ring-0" placeholder=""> {{__('main.arbitrary_interval')}}</label>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <div class="flex flex-col gap-2">
                                            <input type="date" id="from" data-type="range" value="{{$from}}" class="filter_field h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white">
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <input type="date" id="to" data-type="range" value="{{$to}}" class="filter_field h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white">
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="py-2 flex justify-end items-center space-x-4">
                            {{--              <button class="text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" data-dismiss="modal"  style="border: 1px solid #718096; color: #718096;">{{__('main.close')}}</button>--}}
                            <div class="text-white cursor-pointer px-4 py-2 rounded-md hover:bg-blue-700 transition -btn-create" id="apply_filter" data-dismiss="modal" style="background: orange">{{__('main.apply')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function(){
            var url = '{{\App\Helpers\QueryHelper::getUrl()}}';
            $('.date').on('input',function(){
                var from = $('#from').val();
                var to = $('#to').val();
                if(from!='' && to!=''){
                    params = '?from='+from+'&to='+to;
                    location.href = url + params;
                }
            })
            $('.radio-field').click(function(){
                $('.filter_field').prop('disabled',true);
                $('.filter_field[data-type="'+$(this).attr('id')+'"]').prop('disabled',false);
            })

            $('.filter_field').prop('disabled',true);
            $('.filter_field[data-type="year"]').prop('disabled',false);

            $('#apply_filter').click(function(){
                var id = $('.radio-field:checked').attr('id');
                var params = '';
                $('.filter_field[data-type="'+id+'"]').each(function(){
                    if($(this).data('filter')!=undefined){
                        params = $(this).val();
                    }else {
                        if (params.length) {
                            params += '&';
                        }else{
                            _to = $(this).val();
                        }
                        if(id=='day') {
                            params += $(this).attr('id') + '=' + _to;
                        }else{
                            params += $(this).attr('id') + '=' + $(this).val();
                        }
                    }

                });

                window.location.href = url + '?'+ params
            })

        })
    </script>
@endsection
