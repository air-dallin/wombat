<?php
    $newClaims = 0; //\App\Models\Claim::where(['status'=>0])->count();
?>

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
                              {{ \Illuminate\Support\Facades\Auth::user()->info->getFullname() }}
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
                            Moderator
                        </p>
                    </div>
                </div>
            </div>
            <!--                notification ,message, store-->
            <div id="notification-box" style="filter: drop-shadow(rgba(0, 0, 0, 0.08) 12px 12px 40px); display: none;" class="absolute -left-[347px] top-[81px] hidden w-[400px] rounded-lg bg-white dark:bg-darkblack-600">
                <div class="relative w-full pb-[75px] pt-[66px]">
                    <div class="absolute left-0 top-0 flex h-[66px] w-full items-center justify-between px-8">
                        <h3 class="text-xl font-bold text-bgray-900 dark:text-white">
                            Notifications
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
                                        <strong class="text-bgray-900 dark:text-white">James Eusobio</strong>
                                        send a new payment for
                                        <strong class="text-bgray-900 dark:text-white">SEO writing</strong>
                                        totaling
                                        <span class="text-success-300">$199.00</span>
                                    </p>
                                    <span class="text-xs font-medium text-bgray-500">23 mins ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="border-b border-bgray-200 py-4 pl-6 pr-[50px] hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
                            <a href="#">
                                <div class="noti-item">
                                    <p class="mb-1 text-sm font-medium text-bgray-600 dark:text-white">
                                        <strong class="text-bgray-900 dark:text-bgray-50">James Eusobio</strong>
                                        send a new payment for
                                        <strong class="text-bgray-900 dark:text-bgray-50">SEO writing</strong>
                                        totaling
                                        <span class="text-error-200">$199.00</span>
                                    </p>
                                    <span class="text-xs font-medium text-bgray-500">23 mins ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="border-b border-bgray-200 py-4 pl-6 pr-[50px] hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
                            <a href="#">
                                <div class="noti-item">
                                    <p class="mb-1 text-sm font-medium text-bgray-600 dark:text-bgray-50">
                                        <strong class="text-bgray-900 dark:text-white">James Eusobio</strong>
                                        send a new payment for
                                        <strong class="text-bgray-900 dark:text-white">SEO writing</strong>
                                        totaling
                                        <span class="text-success-300">$199.00</span>
                                    </p>
                                    <span class="text-xs font-medium text-bgray-500">23 mins ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="border-b border-bgray-200 py-4 pl-6 pr-[50px] hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
                            <a href="#">
                                <div class="noti-item">
                                    <p class="mb-1 text-sm font-medium text-bgray-600 dark:text-bgray-50">
                                        <strong class="text-bgray-900 dark:text-white">James Eusobio</strong>
                                        send a new payment for
                                        <strong class="text-bgray-900 dark:text-white">SEO writing</strong>
                                        totaling
                                        <span class="text-success-300">$199.00</span>
                                    </p>
                                    <span class="text-xs font-medium text-bgray-500">23 mins ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="border-b border-bgray-200 py-4 pl-6 pr-[50px] hover:bg-bgray-100 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
                            <a href="#">
                                <div class="noti-item">
                                    <p class="mb-1 text-sm font-medium text-bgray-600 dark:text-bgray-50">
                                        <strong class="text-bgray-900 dark:text-white">James Eusobio</strong>
                                        send a new payment for
                                        <strong class="text-bgray-900 dark:text-white">SEO writing</strong>
                                        totaling
                                        <span class="text-success-300">$199.00</span>
                                    </p>
                                    <span class="text-xs font-medium text-bgray-500">23 mins ago</span>
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
                                Mark all as read
                              </span>
                                </div>
                            </a>
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
                                    <a href="#">
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
                                    <a href="{{ localeRoute('admin.logout') }}">
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
