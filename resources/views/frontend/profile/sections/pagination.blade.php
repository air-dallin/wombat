<div class="custom-pagination" style="margin-top: 20px;">
  <div class="pagination-content w-full">
    <div class="flex w-full items-center justify-end lg:justify-end">
      @if ($paginator->hasPages())
        <ul class="flex items-center space-x-2">
          {{-- Previous Page Link --}}
          @if ($paginator->onFirstPage())
            <li class="disabled"><span>
                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.7217 5.03271L7.72168 10.0327L12.7217 15.0327" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                              </svg>
          </span></li>
          @else
            <li class="rounded-lg px-4 py-1.5 text-xs font-bold text-bgray-500 transition duration-300 ease-in-out hover:bg-success-50 hover:text-success-300 dark:hover:bg-darkblack-500 lg:px-6 lg:py-2.5 lg:text-sm"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
          @endif

          {{-- Pagination Elements --}}
          @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
              <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
              @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                  <li class="active rounded-lg bg-success-50 px-4 py-1.5 text-xs font-bold text-success-300 dark:bg-darkblack-500 dark:text-bgray-50 lg:px-6 lg:py-2.5 lg:text-sm"><span>{{ $page }}</span></li>
                @else
                  <li><a href="{{ $url }}" class="rounded-lg px-4 py-1.5 text-xs font-bold text-bgray-500 transition duration-300 ease-in-out hover:bg-success-50 hover:text-success-300 dark:hover:bg-darkblack-500 lg:px-6 lg:py-2.5 lg:text-sm">{{ $page }}</a></li>
                @endif
              @endforeach
            @endif
          @endforeach

          {{-- Next Page Link --}}
          @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M7.72168 5.03271L12.7217 10.0327L7.72168 15.0327" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </a></li>
          @else
            <li class="disabled"><span>&raquo;</span></li>
          @endif
        </ul>
      @endif
    </div>
  </div>
</div>
