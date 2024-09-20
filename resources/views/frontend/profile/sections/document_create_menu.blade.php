@php
/** @var string $currentMenuType */
    $menuItems = \App\Helpers\DocumentHelper::getMenuItems($currentMenuType)
@endphp
<div>
    <div class="relative h-[56px] w-full">
        <button onclick="dateFilterAction('#dogvor-filter')" type="button" class="relative -flex h-full -w-full items-center justify-between px-4 dark:bg-darkblack-500">
            <h3 class="text-2xl font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">{{__('main.document_type')}}: {{ $menuItems[0]['title'] }}</h3>
            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.58203 8.3186L10.582 13.3186L15.582 8.3186" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        @if($menuItems[0]['type']=='factura')
        <button onclick="dateFilterAction('#dogvor-filter-type')" type="button" class="relative -flex h-full -w-full items-center justify-between px-4 dark:bg-darkblack-500">
            <h3 class="text-2xl {{--text-sm --}} font-bold text-bgray-900 dark:border-darkblack-400 dark:text-white">{{ App\Models\Product::getCurrentType()  }}</h3>
            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.58203 8.3186L10.582 13.3186L15.582 8.3186" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        @endif
        <div id="dogvor-filter" class="absolute left-0 top-14 z-10 hidden w-full overflow-hidden rounded-lg bg-white shadow-lg dark:bg-darkblack-500" style="max-width: 150px;">
            <ul>
                <li onclick="dateFilterAction('#dogvor-filter')" class="text-bgray-90 cursor-pointer px-5 py-2 text-sm font-semibold hover:bg-bgray-100 dark:text-white hover:dark:bg-darkblack-600">
                    <a href="{{ $menuItems[1]['url'] }}" class="dropdown-item d-flex justify-content-center">{{ $menuItems[1]['title'] }}</a>
                </li>
                <li onclick="dateFilterAction('#dogvor-filter')" class="cursor-pointer px-5 py-2 text-sm font-semibold text-bgray-900 hover:bg-bgray-100 dark:text-white hover:dark:bg-darkblack-600">
                    <a href="{{ $menuItems[2]['url'] }}" class="dropdown-item d-flex justify-content-center">{{ $menuItems[2]['title'] }}</a>
                </li>
               <li onclick="dateFilterAction('#dogvor-filter')" class="cursor-pointer px-5 py-2 text-sm font-semibold text-bgray-900 hover:bg-bgray-100 dark:text-white hover:dark:bg-darkblack-600">
                    <a href="{{ $menuItems[3]['url'] }}" class="dropdown-item d-flex justify-content-center">{{ $menuItems[3]['title'] }}</a>
                </li>
                <li onclick="dateFilterAction('#dogvor-filter')" class="cursor-pointer px-5 py-2 text-sm font-semibold text-bgray-900 hover:bg-bgray-100 dark:text-white hover:dark:bg-darkblack-600">
                    <a href="{{ $menuItems[4]['url'] }}" class="dropdown-item d-flex justify-content-center">{{ $menuItems[4]['title'] }}</a>
                </li>
            </ul>
        </div>

        @if($menuItems[0]['type']=='factura')
            @php
                $types = App\Models\Product::getTypes();
                $url = localeRoute('frontend.profile.modules.product.create');
            @endphp
            <div id="dogvor-filter-type" class="absolute right-0 top-14 z-5 hidden w-full overflow-hidden rounded-lg bg-white shadow-lg dark:bg-darkblack-500" style="max-width: 400px;">
                <ul>
                @foreach($types as $id=>$type)
                    <li onclick="dateFilterAction('#dogvor-filteк-type')" class="text-bgray-90 cursor-pointer px-5 py-2 text-sm {{--font-semibold--}} hover:bg-bgray-100 dark:text-white hover:dark:bg-darkblack-600">
                        <a href="{{ $url .'?type=' .$id }}" class="dropdown-item d-flex justify-content-center">{{ $type }}</a>
                    </li>
                @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
