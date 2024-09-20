<header class="header-wrapper fixed z-30 hidden w-full md:block">
  <div
      class="relative flex h-[108px] w-full items-center justify-between bg-white px-10 dark:bg-darkblack-600 2xl:px-[48px]"
  >
    <button
        title="Ctrl+b"
        type="button"
        class="drawer-btn absolute left-0 top-auto rotate-180 transform"
    >
                <span>
                  <svg
                      width="16"
                      height="40"
                      viewBox="0 0 16 40"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                        d="M0 10C0 4.47715 4.47715 0 10 0H16V40H10C4.47715 40 0 35.5228 0 30V10Z"
                        fill="#ffa500"
                    />
                    <path
                        d="M10 15L6 20.0049L10 25.0098"
                        stroke="#ffffff"
                        stroke-width="1.2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                  </svg>
                </span>
    </button>
    <!--              page-title-->
    <div>
      <h3
          class="text-xl font-bold text-bgray-900 dark:text-bgray-50 lg:text-3xl lg:leading-[36.4px]"
      >
        @yield('title', __('main.profile'))
      </h3>
{{--      <p--}}
{{--          class="text-xs font-medium text-bgray-600 dark:text-bgray-50 lg:text-sm lg:leading-[25.2px]"--}}
{{--      >--}}
{{--        Let’s check your update today--}}
{{--      </p>--}}
    </div>
    <!--  quick access-->
    <div class="quick-access-wrapper relative">
      <div class="flex items-center space-x-[43px]">
        <div class="hidden items-center space-x-5 xl:flex">
          <button
              type="button"
              id="theme-toggle"
              class="relative flex h-[52px] w-[52px] items-center justify-center rounded-[12px] border  dark:border-darkblack-400"
          >
               СРМ
          </button>
          <button
              type="button"
              id="theme-toggle"
              class="relative flex h-[52px] w-[52px] items-center justify-center rounded-[12px] border  dark:border-darkblack-400"
          >
            УУ
          </button>
{{--          <button--}}
{{--              type="button"--}}
{{--              id="theme-toggle"--}}
{{--              class="relative flex h-[52px] w-[52px] items-center justify-center rounded-[12px] border  dark:border-darkblack-400"--}}
{{--          >--}}
{{--                      <span class="block dark:hidden">--}}
{{--                        <svg--}}
{{--                            class="stroke-bgray-900"--}}
{{--                            width="24"--}}
{{--                            height="24"--}}
{{--                            viewBox="0 0 24 24"--}}
{{--                            fill="none"--}}
{{--                            xmlns="http://www.w3.org/2000/svg"--}}
{{--                        >--}}
{{--                          <path--}}
{{--                              d="M18.3284 14.8687C13.249 14.8687 9.13135 10.751 9.13135 5.67163C9.13135 4.74246 9.26914 3.84548 9.5254 3C5.74897 4.14461 3 7.65276 3 11.803C3 16.8824 7.11765 21 12.197 21C16.3472 21 19.8554 18.251 21 14.4746C20.1545 14.7309 19.2575 14.8687 18.3284 14.8687Z"--}}
{{--                              stroke-width="1.5"--}}
{{--                              stroke-linejoin="round"--}}
{{--                          />--}}
{{--                        </svg>--}}
{{--                      </span>--}}
{{--            <span class="hidden dark:block">--}}
{{--                        <svg--}}
{{--                            class="stroke-bgray-900 dark:stroke-bgray-50"--}}
{{--                            width="24"--}}
{{--                            height="24"--}}
{{--                            viewBox="0 0 24 24"--}}
{{--                            fill="none"--}}
{{--                            xmlns="http://www.w3.org/2000/svg"--}}
{{--                        >--}}
{{--                          <circle cx="12" cy="12" r="5" stroke-width="1.5"/>--}}
{{--                          <path--}}
{{--                              d="M12 2V4"--}}
{{--                              stroke-width="1.5"--}}
{{--                              stroke-linecap="round"--}}
{{--                          />--}}
{{--                          <path--}}
{{--                              d="M12 20V22"--}}
{{--                              stroke-width="1.5"--}}
{{--                              stroke-linecap="round"--}}
{{--                          />--}}
{{--                          <path--}}
{{--                              d="M20.6602 7L18.9281 8"--}}
{{--                              stroke-width="1.5"--}}
{{--                              stroke-linecap="round"--}}
{{--                          />--}}
{{--                          <path--}}
{{--                              d="M5.07178 16L3.33973 17"--}}
{{--                              stroke-width="1.5"--}}
{{--                              stroke-linecap="round"--}}
{{--                          />--}}
{{--                          <path--}}
{{--                              d="M3.33984 7L5.07189 8"--}}
{{--                              stroke-width="1.5"--}}
{{--                              stroke-linecap="round"--}}
{{--                          />--}}
{{--                          <path--}}
{{--                              d="M18.9282 16L20.6603 17"--}}
{{--                              stroke-width="1.5"--}}
{{--                              stroke-linecap="round"--}}
{{--                          />--}}
{{--                        </svg>--}}
{{--                      </span>--}}
{{--          </button>--}}
          <button onclick="notificationAction()" id="notification-btn" type="button" class="relative flex h-[52px] w-[52px] items-center justify-center rounded-[12px] border  dark:border-darkblack-400">
                      <span class="absolute -right-[5px] -top-[2px] h-3.5 w-3.5 rounded-full border-2 border-white bg-bgray-300 dark:border-none dark:bg-bgray-600">
                      </span>
            <svg class="fill-bgray-900 dark:fill-white" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M19.9718 6.78149L19.2803 7.07178L19.9718 6.78149ZM19.3571 7.25473C19.5174 7.63666 19.957 7.81631 20.3389 7.65599C20.7209 7.49567 20.9005 7.05609 20.7402 6.67416L19.3571 7.25473ZM16.7784 2.77061C16.3937 2.61687 15.9573 2.80404 15.8036 3.18867C15.6498 3.5733 15.837 4.00973 16.2216 4.16347L16.7784 2.77061ZM16.6672 3.53388L16.3889 4.23031L16.6672 3.53388ZM4.0768 6.78149L4.76834 7.07178L4.0768 6.78149ZM3.30846 6.67416C3.14813 7.05609 3.32778 7.49567 3.70971 7.65599C4.09164 7.81631 4.53122 7.63666 4.69154 7.25473L3.30846 6.67416ZM7.82701 4.16347C8.21164 4.00973 8.39881 3.5733 8.24507 3.18867C8.09134 2.80405 7.65491 2.61687 7.27028 2.77061L7.82701 4.16347ZM7.38142 3.53388L7.10305 2.83745V2.83745L7.38142 3.53388ZM18.2395 9.93743L17.4943 10.0221V10.0221L18.2395 9.93743ZM18.6867 13.8746L19.4319 13.7899V13.7899L18.6867 13.8746ZM5.31328 13.8746L4.56807 13.7899L5.31328 13.8746ZM5.76046 9.93743L6.50567 10.0221L5.76046 9.93743ZM4.44779 15.83L3.87686 15.3436H3.87686L4.44779 15.83ZM19.5522 15.83L18.9813 16.3164L18.9813 16.3164L19.5522 15.83ZM14.2699 5.33931H13.5199C13.5199 5.65996 13.7238 5.94513 14.0272 6.04893L14.2699 5.33931ZM9.73005 5.33931L9.97284 6.04893C10.2762 5.94513 10.4801 5.65996 10.4801 5.33931H9.73005ZM15.7022 21.2175C15.8477 20.8296 15.6512 20.3973 15.2634 20.2518C14.8755 20.1064 14.4432 20.3029 14.2978 20.6907L15.7022 21.2175ZM9.70223 20.6907C9.55678 20.3029 9.12446 20.1064 8.73663 20.2518C8.34879 20.3973 8.15231 20.8296 8.29777 21.2175L9.70223 20.6907ZM19.2803 7.07178L19.3571 7.25473L20.7402 6.67416L20.6634 6.4912L19.2803 7.07178ZM16.2216 4.16347L16.3889 4.23031L16.9456 2.83745L16.7784 2.77061L16.2216 4.16347ZM20.6634 6.4912C19.9638 4.82468 18.6244 3.50849 16.9456 2.83745L16.3889 4.23031C17.6948 4.7523 18.7364 5.77599 19.2803 7.07178L20.6634 6.4912ZM3.38526 6.4912L3.30846 6.67416L4.69154 7.25473L4.76834 7.07178L3.38526 6.4912ZM7.27028 2.77061L7.10305 2.83745L7.65979 4.23031L7.82701 4.16347L7.27028 2.77061ZM4.76834 7.07178C5.31227 5.77599 6.35384 4.7523 7.65979 4.23031L7.10305 2.83745C5.4242 3.50849 4.08481 4.82468 3.38526 6.4912L4.76834 7.07178ZM17.7772 18.2056H6.22281V19.7056H17.7772V18.2056ZM17.4943 10.0221L17.9415 13.9592L19.4319 13.7899L18.9847 9.85279L17.4943 10.0221ZM6.05849 13.9592L6.50567 10.0221L5.01526 9.85279L4.56807 13.7899L6.05849 13.9592ZM5.01872 16.3164C5.59608 15.6386 5.96025 14.8241 6.05849 13.9592L4.56807 13.7899C4.50522 14.3432 4.2708 14.8812 3.87686 15.3436L5.01872 16.3164ZM17.9415 13.9592C18.0398 14.8241 18.4039 15.6386 18.9813 16.3164L20.1231 15.3436C19.7292 14.8812 19.4948 14.3432 19.4319 13.7899L17.9415 13.9592ZM6.22281 18.2056C5.5675 18.2056 5.10418 17.8817 4.89044 17.5053C4.68417 17.1421 4.68715 16.7056 5.01872 16.3164L3.87686 15.3436C3.11139 16.2422 3.0877 17.3685 3.5861 18.2461C4.07704 19.1105 5.04975 19.7056 6.22281 19.7056V18.2056ZM17.7772 19.7056C18.9503 19.7056 19.923 19.1105 20.4139 18.2461C20.9123 17.3685 20.8886 16.2422 20.1231 15.3436L18.9813 16.3164C19.3129 16.7056 19.3158 17.1421 19.1096 17.5053C18.8958 17.8817 18.4325 18.2056 17.7772 18.2056V19.7056ZM15.0199 5.33931V5.23567H13.5199V5.33931H15.0199ZM18.9847 9.85279C18.7054 7.39374 16.8802 5.43969 14.5127 4.6297L14.0272 6.04893C15.9445 6.70491 17.2914 8.23516 17.4943 10.0221L18.9847 9.85279ZM10.4801 5.33931V5.23567H8.98005V5.33931H10.4801ZM6.50567 10.0221C6.70863 8.23516 8.05551 6.70491 9.97284 6.04893L9.48727 4.6297C7.1198 5.43969 5.29456 7.39374 5.01526 9.85279L6.50567 10.0221ZM12 3.71741C12.84 3.71741 13.5199 4.39768 13.5199 5.23567H15.0199C15.0199 3.56821 13.6673 2.21741 12 2.21741V3.71741ZM12 2.21741C10.3327 2.21741 8.98005 3.56821 8.98005 5.23567H10.4801C10.4801 4.39768 11.16 3.71741 12 3.71741V2.21741ZM14.2978 20.6907C13.9752 21.5508 13.0849 22.2026 12 22.2026V23.7026C13.6851 23.7026 15.1514 22.686 15.7022 21.2175L14.2978 20.6907ZM12 22.2026C10.9151 22.2026 10.0248 21.5508 9.70223 20.6907L8.29777 21.2175C8.84856 22.686 10.3149 23.7026 12 23.7026V22.2026Z"></path>
            </svg>
          </button>
          <button onclick="messageAction()" type="button" id="message-btn" class="relative flex h-[52px] w-[52px] items-center justify-center rounded-[12px] border  dark:border-darkblack-400">
            <span class="absolute -right-[5px] -top-[2px] h-3.5 w-3.5 rounded-full border-2 border-white bg-error-300 dark:border-none"></span>
            <svg class="stroke-bgray-900 dark:stroke-white" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 12V7C2 4.79086 3.79086 3 6 3H18C20.2091 3 22 4.79086 22 7V17C22 19.2091 20.2091 21 18 21H8M6 8L9.7812 10.5208C11.1248 11.4165 12.8752 11.4165 14.2188 10.5208L18 8M2 15H8M2 18H8" stroke-width="1.5" stroke-linecap="round"></path>
            </svg>
          </button>
          <div>
            <div class="relative h-[56px] w-full">
              <button onclick="dateFilterAction('#amount-filter')" type="button"
                      class="relative flex h-full w-full items-center justify-between rounded-lg border   px-4 dark:bg-darkblack-500">
      <span class="text-base text-bgray-500">
             @if(app()->getLocale() == 'oz')
          <a href="/lang/oz" class="btn dropdown-toggle" style="color: #000; font-weight: 500" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> OZ</a>
        @endif
        @if(app()->getLocale() == 'ru')
          <a href="/lang/ru" class="btn dropdown-toggle" style="color: #000; font-weight: 500" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> RU</a>
        @endif
        @if(app()->getLocale() == 'uz')
          <a href="/lang/uz" class="btn dropdown-toggle" style="color: #000; font-weight: 500" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> UZ </a>
        @endif
      </span>
                <span>
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5.58203 8.3186L10.582 13.3186L15.582 8.3186" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                              </span>
              </button>
              <div id="amount-filter" class="absolute right-0 top-14 z-10 hidden w-full overflow-hidden rounded-lg bg-white shadow-lg dark:bg-darkblack-500" style="display: none;">
                <ul>
                  @if(app()->getLocale() != 'oz')
                    <li onclick="dateFilterAction('#amount-filter')" class="text-bgray-90 cursor-pointer px-5 py-2 text-sm font-semibold hover:bg-bgray-100 dark:text-white hover:dark:bg-darkblack-600">
                      <a href="/lang/oz" class="dropdown-item d-flex justify-content-center">OZ</a>
                    </li>
                  @endif
                  @if(app()->getLocale() != 'ru')
                    <li onclick="dateFilterAction('#amount-filter')" class="cursor-pointer px-5 py-2 text-sm font-semibold text-bgray-900 hover:bg-bgray-100 dark:text-white hover:dark:bg-darkblack-600">
                      <a href="/lang/ru" class="dropdown-item d-flex justify-content-center">RU</a>

                    </li>
                  @endif
                  @if(app()->getLocale() != 'uz')
                    <li onclick="dateFilterAction('#amount-filter')" class="cursor-pointer px-5 py-2 text-sm font-semibold text-bgray-900 hover:bg-bgray-100 dark:text-white hover:dark:bg-darkblack-600">
                      <a href="/lang/uz" class="dropdown-item d-flex justify-content-center">UZ</a>
                    </li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
          {{--          <a href="/lang/ru"--}}
          {{--              class="relative flex h-[52px] w-[52px] items-center justify-center rounded-[12px] border "--}}
          {{--          >--}}
          {{--            @if(app()->getLocale() === 'ru')--}}
          {{--                      <span--}}
          {{--                          class="absolute -right-[5px] -top-[2px] h-3.5 w-3.5 rounded-full border-2 border-white bg-success-300 dark:border-none"--}}
          {{--                      ></span>--}}
          {{--            @endif--}}
          {{--         RU--}}

          {{--          </a>--}}
          {{--          <a href="/lang/uz"--}}
          {{--              class="relative flex h-[52px] w-[52px] items-center justify-center rounded-[12px] border  dark:border-darkblack-400"--}}
          {{--          >--}}
          {{--            UZ--}}
          {{--            @if(app()->getLocale() === 'uz')--}}
          {{--              <span--}}
          {{--                  class="absolute -right-[5px] -top-[2px] h-3.5 w-3.5 rounded-full border-2 border-white bg-success-300 dark:border-none"--}}
          {{--              ></span>--}}
          {{--            @endif--}}
          {{--          </a>--}}
          {{--            <a href="/lang/en"--}}
          {{--              class="relative flex h-[52px] w-[52px] items-center justify-center rounded-[12px] border  dark:border-darkblack-400"--}}
          {{--          >--}}
          {{--            EN--}}
          {{--              @if(app()->getLocale() === 'en')--}}
          {{--                <span--}}
          {{--                    class="absolute -right-[5px] -top-[2px] h-3.5 w-3.5 rounded-full border-2 border-white bg-success-300 dark:border-none"--}}
          {{--                ></span>--}}
          {{--              @endif--}}
          {{--            </a>--}}
        </div>
        <div
            class="hidden h-[48px] w-[1px] bg-bgray-300 dark:bg-darkblack-400 xl:block"
        ></div>
        <!--                author-->
        <div
            onclick="profileAction()"
            class="flex cursor-pointer space-x-0 lg:space-x-3"
        >

          <div
              class="h-[52px] w-[52px] overflow-hidden rounded-xl border border-bgray-300"
          >
            <img
                class="object-cover"
                src="{{ asset('profile/assets/images/avatar/user_1.jpg') }}"
                alt="avater"
            />
          </div>
          <div class="hidden 2xl:block">
            <div class="flex items-center space-x-2.5">
              <h3
                  class="text-base font-bold leading-[28px] text-bgray-900 dark:text-white"
              >
              Алишер
                {{--                {{ auth()->user()->info->getFullname() }}--}}
              </h3>
              <span>
                          <svg
                              class="stroke-bgray-900 dark:stroke-white"
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                          >
                            <path
                                d="M7 10L12 14L17 10"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                          </svg>
                        </span>
            </div>
            <p
                class="text-sm font-medium leading-[20px] text-bgray-600 dark:text-bgray-50"
            >
              Владелец
            </p>
          </div>
        </div>
      </div>
      <!--                notification ,message, store-->
      <div id="notification-box" style="filter: drop-shadow(rgba(0, 0, 0, 0.08) 12px 12px 40px); display: none;" class="absolute -left-[347px] top-[81px] hidden w-[400px] rounded-lg bg-white dark:bg-darkblack-600">
        <div class="relative w-full pb-[75px] pt-[66px]">
          <div class="absolute left-0 top-0 flex h-[66px] w-full items-center justify-between px-8">
            <h3 class="text-xl font-bold text-bgray-900 dark:text-white">
              Уведомления
            </h3>
            <span>
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.325 4.317C10.751 2.561 13.249 2.561 13.675 4.317C13.7389 4.5808 13.8642 4.82578 14.0407 5.032C14.2172 5.23822 14.4399 5.39985 14.6907 5.50375C14.9414 5.60764 15.2132 5.65085 15.4838 5.62987C15.7544 5.60889 16.0162 5.5243 16.248 5.383C17.791 4.443 19.558 6.209 18.618 7.753C18.4769 7.98466 18.3924 8.24634 18.3715 8.51677C18.3506 8.78721 18.3938 9.05877 18.4975 9.30938C18.6013 9.55999 18.7627 9.78258 18.9687 9.95905C19.1747 10.1355 19.4194 10.2609 19.683 10.325C21.439 10.751 21.439 13.249 19.683 13.675C19.4192 13.7389 19.1742 13.8642 18.968 14.0407C18.7618 14.2172 18.6001 14.4399 18.4963 14.6907C18.3924 14.9414 18.3491 15.2132 18.3701 15.4838C18.3911 15.7544 18.4757 16.0162 18.617 16.248C19.557 17.791 17.791 19.558 16.247 18.618C16.0153 18.4769 15.7537 18.3924 15.4832 18.3715C15.2128 18.3506 14.9412 18.3938 14.6906 18.4975C14.44 18.6013 14.2174 18.7627 14.0409 18.9687C13.8645 19.1747 13.7391 19.4194 13.675 19.683C13.249 21.439 10.751 21.439 10.325 19.683C10.2611 19.4192 10.1358 19.1742 9.95929 18.968C9.7828 18.7618 9.56011 18.6001 9.30935 18.4963C9.05859 18.3924 8.78683 18.3491 8.51621 18.3701C8.24559 18.3911 7.98375 18.4757 7.752 18.617C6.209 19.557 4.442 17.791 5.382 16.247C5.5231 16.0153 5.60755 15.7537 5.62848 15.4832C5.64942 15.2128 5.60624 14.9412 5.50247 14.6906C5.3987 14.44 5.23726 14.2174 5.03127 14.0409C4.82529 13.8645 4.58056 13.7391 4.317 13.675C2.561 13.249 2.561 10.751 4.317 10.325C4.5808 10.2611 4.82578 10.1358 5.032 9.95929C5.23822 9.7828 5.39985 9.56011 5.50375 9.30935C5.60764 9.05859 5.65085 8.78683 5.62987 8.51621C5.60889 8.24559 5.5243 7.98375 5.383 7.752C4.443 6.209 6.209 4.442 7.753 5.382C8.753 5.99 10.049 5.452 10.325 4.317Z"
                                stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </svg>
                        </span>
          </div>
          <ul class="scroll-style-1 h-[335px] w-full overflow-y-scroll">
            <li class="border-b border-bgray-200 py-4 pl-6 pr-[50px] hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
              <a href="#">
                <div class="noti-item">
                  <p class="mb-1 text-sm font-medium text-bgray-600 dark:text-bgray-50">
                    <strong class="text-bgray-900 dark:text-white">"GEOTEHNOLOGY BUSINESS" MCHJ </strong>
                    отправила платеж по договору
                    <strong class="text-bgray-900 dark:text-white">1-02.04.2024</strong>
                    на сумму
                    <span class="text-success-300">2 500 000 сум</span>
                  </p>
                  <span class="text-xs font-medium text-bgray-500">23 минуты назад</span>
                </div>
              </a>
            </li>
            <li class="border-b border-bgray-200 py-4 pl-6 pr-[50px] hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
              <a href="#">
                <div class="noti-item">
                  <p class="mb-1 text-sm font-medium text-bgray-600 dark:text-white">
                    <strong class="text-bgray-900 dark:text-bgray-50">"OSIYO-POLYMER" MCHJ</strong>
                    отправила платеж по договору
                    <strong class="text-bgray-900 dark:text-bgray-50">ds21758</strong>
                    на сумму
                    <span class="text-error-200">1 300 000 сум</span>
                  </p>
                  <span class="text-xs font-medium text-bgray-500">1 час назад</span>
                </div>
              </a>
            </li>
            <li class="border-b border-bgray-200 py-4 pl-6 pr-[50px] hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
              <a href="#">
                <div class="noti-item">
                  <p class="mb-1 text-sm font-medium text-bgray-600 dark:text-bgray-50">
                    <strong class="text-bgray-900 dark:text-white">"KAPITALBANK" ATB</strong>
                    выставила счет-фактуру
                    <strong class="text-bgray-900 dark:text-white">38993/974</strong>
                  </p>
                  <span class="text-xs font-medium text-bgray-500">2 часа назад</span>
                </div>
              </a>
            </li>
          </ul>
          <div class="absolute bottom-0 left-0 flex h-[75px] w-full items-center justify-between px-8">
            <div>
              <a href="#">
                <div class="flex items-center space-x-2">
                              <span>
                                <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M6 6L11 11L21 1M1 6L6 11M11 6L16 1" stroke="#0CAF60" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                              </span>
                  <span class="text-sm font-semibold text-success-300">
Все прочитано
                              </span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="message-popup-wrapper">
        <div onclick="messageAction()" id="mes-outside" class="fixed -left-[43px] top-0 hidden h-full w-full" style="display: none;"></div>
        <div id="message-box" style="filter: drop-shadow(rgba(0, 0, 0, 0.08) 12px 12px 40px); display: none;" class="absolute -left-[276px] top-[81px] hidden w-[400px] rounded-lg bg-white dark:bg-darkblack-600">
          <div class="relative w-full pb-[75px] pt-[66px]">
            <div class="absolute left-0 top-0 flex h-[66px] w-full items-center justify-between px-8">
              <h3 class="text-xl font-bold text-bgray-900 dark:text-white">
               Сообщение
              </h3>
              <span>
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.325 4.317C10.751 2.561 13.249 2.561 13.675 4.317C13.7389 4.5808 13.8642 4.82578 14.0407 5.032C14.2172 5.23822 14.4399 5.39985 14.6907 5.50375C14.9414 5.60764 15.2132 5.65085 15.4838 5.62987C15.7544 5.60889 16.0162 5.5243 16.248 5.383C17.791 4.443 19.558 6.209 18.618 7.753C18.4769 7.98466 18.3924 8.24634 18.3715 8.51677C18.3506 8.78721 18.3938 9.05877 18.4975 9.30938C18.6013 9.55999 18.7627 9.78258 18.9687 9.95905C19.1747 10.1355 19.4194 10.2609 19.683 10.325C21.439 10.751 21.439 13.249 19.683 13.675C19.4192 13.7389 19.1742 13.8642 18.968 14.0407C18.7618 14.2172 18.6001 14.4399 18.4963 14.6907C18.3924 14.9414 18.3491 15.2132 18.3701 15.4838C18.3911 15.7544 18.4757 16.0162 18.617 16.248C19.557 17.791 17.791 19.558 16.247 18.618C16.0153 18.4769 15.7537 18.3924 15.4832 18.3715C15.2128 18.3506 14.9412 18.3938 14.6906 18.4975C14.44 18.6013 14.2174 18.7627 14.0409 18.9687C13.8645 19.1747 13.7391 19.4194 13.675 19.683C13.249 21.439 10.751 21.439 10.325 19.683C10.2611 19.4192 10.1358 19.1742 9.95929 18.968C9.7828 18.7618 9.56011 18.6001 9.30935 18.4963C9.05859 18.3924 8.78683 18.3491 8.51621 18.3701C8.24559 18.3911 7.98375 18.4757 7.752 18.617C6.209 19.557 4.442 17.791 5.382 16.247C5.5231 16.0153 5.60755 15.7537 5.62848 15.4832C5.64942 15.2128 5.60624 14.9412 5.50247 14.6906C5.3987 14.44 5.23726 14.2174 5.03127 14.0409C4.82529 13.8645 4.58056 13.7391 4.317 13.675C2.561 13.249 2.561 10.751 4.317 10.325C4.5808 10.2611 4.82578 10.1358 5.032 9.95929C5.23822 9.7828 5.39985 9.56011 5.50375 9.30935C5.60764 9.05859 5.65085 8.78683 5.62987 8.51621C5.60889 8.24559 5.5243 7.98375 5.383 7.752C4.443 6.209 6.209 4.442 7.753 5.382C8.753 5.99 10.049 5.452 10.325 4.317Z" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </svg>
                        </span>
            </div>
            <div>
              <div class="flex items-center border-b border-bgray-200 dark:border-darkblack-400">
                <button type="button" class="flex space-x-2 border-b-2 border-success-300 px-6 py-4 text-sm font-semibold capitalize text-success-300">
                  <span>Все</span>
                  <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-success-50 text-[10px] text-success-300">3</span>
                </button>
              </div>
              <ul class="scroll-style-1 h-[335px] w-full overflow-y-scroll">
                <li class="border-b border-bgray-200 px-7 py-6 hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
                  <a href="#">
                    <div class="flex space-x-3">
                      <div class="relative h-[48px] w-[48px] rounded-full">
                        <img src="{{ asset('profile/assets/images/avatar/3.png') }}" alt="avatar" class="h-full w-full object-cover">
                        <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border border-white bg-bgray-300"></div>
                      </div>
                      <div class="flex-1">
                        <p class="mb-1 text-base font-bold text-bgray-900 dark:text-white">
                        Азамат
                        </p>

                        <span class="text-sm font-medium text-bgray-500 dark:text-bgray-50"  style="margin-top: 10px; display:block;">23 минуты назад</span>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="border-b border-bgray-200 px-7 py-6 hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
                  <a href="#">
                    <div class="flex space-x-3">
                      <div class="relative h-[48px] w-[48px] rounded-full">
                        <img src="{{ asset('profile/assets/images/avatar/1.png') }}" alt="avatar" class="h-full w-full object-cover">
                        <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border border-white bg-success-300"></div>
                      </div>
                      <div class="flex-1">
                        <p class="mb-1 text-base font-bold text-bgray-900">
                          Виктория
                        </p>
                        <span class="text-sm font-medium text-bgray-500" style="margin-top: 10px; display:block;">1 час назад</span>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="border-b border-bgray-200 px-7 py-6 hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
                  <a href="#">
                    <div class="flex space-x-3">
                      <div class="relative h-[48px] w-[48px] rounded-full">
                        <img src="{{ asset('profile/assets/images/avatar/2.png') }}" alt="avatar" class="h-full w-full object-cover">
                        <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border border-white bg-success-300"></div>
                      </div>
                      <div class="flex-1">
                        <p class="mb-1 text-base font-bold text-bgray-900">
                          Сиддикджон
                        </p>
                        <span class="text-sm font-medium text-bgray-500"  style="margin-top: 10px; display:block;">2 часа назад</span>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </div>

            <div class="absolute bottom-0 left-0 flex h-[75px] w-full items-center justify-between px-8">
              <div>
                <a href="#">
                  <div class="flex items-center space-x-2">
                              <span>
                                <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M6 6L11 11L21 1M1 6L6 11M11 6L16 1" stroke="#0CAF60" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                              </span>
                    <span class="text-sm font-semibold text-success-300">
                               Все прочитано
                              </span>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="profile-wrapper">
        <div
            onclick="profileAction()"
            class="profile-outside fixed -left-[43px] top-0 hidden h-full w-full"
        ></div>
        <div
            style="
                      filter: drop-shadow(12px 12px 40px rgba(0, 0, 0, 0.08));
                    "
            class="profile-box absolute right-0 top-[81px] hidden w-[300px] overflow-hidden rounded-lg bg-white dark:bg-darkblack-600"
        >
          <div class="relative w-full px-3 py-2">
            <div>
              <ul>
                <li class="w-full">
                  <a href="{{ localeRoute('frontend.profile.info') }}">
                    <div
                        class="flex items-center space-x-[18px] rounded-lg p-[14px] text-bgray-600 hover:bg-bgray-100 hover:text-bgray-900 hover:dark:bg-darkblack-500"
                    >
                      <div class="w-[20px]">
                                  <span>
                                    <svg
                                        class="stroke-bgray-900 dark:stroke-bgray-50"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                      <path
                                          d="M12.1197 12.7805C12.0497 12.7705 11.9597 12.7705 11.8797 12.7805C10.1197 12.7205 8.71973 11.2805 8.71973 9.51047C8.71973 7.70047 10.1797 6.23047 11.9997 6.23047C13.8097 6.23047 15.2797 7.70047 15.2797 9.51047C15.2697 11.2805 13.8797 12.7205 12.1197 12.7805Z"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                      />
                                      <path
                                          d="M18.7398 19.3796C16.9598 21.0096 14.5998 21.9996 11.9998 21.9996C9.39977 21.9996 7.03977 21.0096 5.25977 19.3796C5.35977 18.4396 5.95977 17.5196 7.02977 16.7996C9.76977 14.9796 14.2498 14.9796 16.9698 16.7996C18.0398 17.5196 18.6398 18.4396 18.7398 19.3796Z"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                      />
                                      <path
                                          d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                      />
                                    </svg>
                                  </span>
                      </div>
                      <div class="flex-1">
                                  <span
                                      class="text-sm font-semibold text-bgray-900 dark:text-white"
                                  >{{ __('main.info') }}</span
                                  >
                      </div>
                    </div>
                  </a>
                </li>
                <li class="w-full">
                  <a href="{{ localeRoute('logout') }}">
                  <div
                      class="flex items-center space-x-[18px] rounded-lg p-[14px]"
                  >
                    <div class="w-[20px]">
                                  <span>
                                    <svg
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                      <path
                                          d="M15 10L13.7071 11.2929C13.3166 11.6834 13.3166 12.3166 13.7071 12.7071L15 14M14 12L22 12M6 20C3.79086 20 2 18.2091 2 16V8C2 5.79086 3.79086 4 6 4M6 20C8.20914 20 10 18.2091 10 16V8C10 5.79086 8.20914 4 6 4M6 20H14C16.2091 20 18 18.2091 18 16M6 4H14C16.2091 4 18 5.79086 18 8"
                                          stroke="red"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                      />
                                    </svg>
                                  </span>
                    </div>
                    <div class="flex-1">
                                  <span class="text-sm font-semibold"
                                  >{{ __('main.logout') }}</span
                                  >
                    </div>
                  </div>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<header class="mobile-wrapper fixed z-20 block w-full md:hidden">
  <div class="flex h-[80px] w-full items-center justify-between bg-white dark:bg-darkblack-600">
    <div class="flex h-full w-full items-center space-x-5">
      <button type="button" class="drawer-btn rotate-180 transform">
                  <span>
                    <svg width="16" height="40" viewBox="0 0 16 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0 10C0 4.47715 4.47715 0 10 0H16V40H10C4.47715 40 0 35.5228 0 30V10Z" fill="#F7F7F7"></path>
                      <path d="M10 15L6 20.0049L10 25.0098" stroke="#A0AEC0" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </span>
      </button>
      <div>
        <a href="/">
          <img src="{{ asset('profile/assets/images/logo/logo.png') }}" class="block dark:hidden" alt="logo" width="60">
          <img src="{{ asset('profile/assets/images/logo/logo.png') }}" class="hidden dark:block" alt="logo" width="60">
        </a>
      </div>
    </div>
    <div class="mr-2">
      <div onclick="profileAction()" class="flex cursor-pointer space-x-0 lg:space-x-3">
        <div class="h-[52px] w-[52px] overflow-hidden rounded-xl border border-bgray-300">
          <img class="object-cover" src="{{ asset('profile/assets/images/avatar/user_1.jpg') }}" alt="avater">
        </div>
        <div class="hidden 2xl:block">
          <div class="flex items-center space-x-2.5">
            <h3 class="text-base font-bold leading-[28px] text-bgray-900">
              John Doe
            </h3>
            <span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M7 10L12 14L17 10" stroke="#28303F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                      </span>
          </div>
          <p class="text-sm font-medium leading-[20px] text-bgray-600">
            Super Admin
          </p>
        </div>
      </div>

      <div class="profile-wrapper">
        <div onclick="profileAction()" class="profile-outside fixed -left-[43px] top-0 hidden h-full w-full"></div>
        <div style="
                      filter: drop-shadow(12px 12px 40px rgba(0, 0, 0, 0.08));
                    " class="profile-box absolute right-0 top-[81px] hidden w-[300px] overflow-hidden rounded-lg bg-white">
          <div class="relative w-full px-3 py-2">
            <div>
              <ul>
                <li class="w-full">
                  <a href="settings.html">
                    <div class="flex items-center space-x-[18px] rounded-lg p-[14px] text-bgray-600 hover:bg-bgray-100 hover:text-bgray-900">
                      <div class="w-[20px]">
                                  <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M12.1197 12.7805C12.0497 12.7705 11.9597 12.7705 11.8797 12.7805C10.1197 12.7205 8.71973 11.2805 8.71973 9.51047C8.71973 7.70047 10.1797 6.23047 11.9997 6.23047C13.8097 6.23047 15.2797 7.70047 15.2797 9.51047C15.2697 11.2805 13.8797 12.7205 12.1197 12.7805Z" stroke="#111827" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                      <path d="M18.7398 19.3796C16.9598 21.0096 14.5998 21.9996 11.9998 21.9996C9.39977 21.9996 7.03977 21.0096 5.25977 19.3796C5.35977 18.4396 5.95977 17.5196 7.02977 16.7996C9.76977 14.9796 14.2498 14.9796 16.9698 16.7996C18.0398 17.5196 18.6398 18.4396 18.7398 19.3796Z" stroke="#111827" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                      <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#111827" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                  </span>
                      </div>
                      <div class="flex-1">
                        <span class="text-sm font-semibold">My Profile</span>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="w-full">
                  <a href="messages.html">
                    <div class="flex items-center space-x-[18px] rounded-lg p-[14px] text-bgray-600 hover:bg-bgray-100 hover:text-bgray-900">
                      <div class="w-[20px]">
                                  <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M2 12V7C2 4.79086 3.79086 3 6 3H18C20.2091 3 22 4.79086 22 7V17C22 19.2091 20.2091 21 18 21H8M6 8L9.7812 10.5208C11.1248 11.4165 12.8752 11.4165 14.2188 10.5208L18 8M2 15H8M2 18H8" stroke="#2A313C" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                  </span>
                      </div>
                      <div class="flex-1">
                        <span class="text-sm font-semibold">Inbox</span>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="w-full">
                  <a href="#">
                    <div class="flex items-center space-x-[18px] rounded-lg p-[14px] text-success-300">
                      <div class="w-[20px]">
                                  <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M15 10L13.7071 11.2929C13.3166 11.6834 13.3166 12.3166 13.7071 12.7071L15 14M14 12L22 12M6 20C3.79086 20 2 18.2091 2 16V8C2 5.79086 3.79086 4 6 4M6 20C8.20914 20 10 18.2091 10 16V8C10 5.79086 8.20914 4 6 4M6 20H14C16.2091 20 18 18.2091 18 16M6 4H14C16.2091 4 18 5.79086 18 8" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                  </span>
                      </div>
                      <div class="flex-1">
                        <span class="text-sm font-semibold">Log Out</span>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </div>
            <div class="my-[14px] h-[1px] w-full bg-bgray-300"></div>
            <div>
              <ul>
                <li class="w-full">
                  <a href="settings.html">
                    <div class="rounded-lg p-[14px] text-bgray-600 hover:bg-bgray-100 hover:text-bgray-900">
                      <span class="text-sm font-semibold">Settings</span>
                    </div>
                  </a>
                </li>
                <li class="w-full">
                  <a href="users.html">
                    <div class="rounded-lg p-[14px] text-bgray-600 hover:bg-bgray-100 hover:text-bgray-900">
                      <span class="text-sm font-semibold">Users</span>
                    </div>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
@php
  $current_company_id = session()->has('current_company_id') ? session()->get('current_company_id') : 0;
@endphp

@section('js')
  <script>
      var current_company_id = '{{$current_company_id}}';
  </script>
@endsection