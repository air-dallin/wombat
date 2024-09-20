<?php

use App\Helpers\MenuHelper;
use App\Models\User;

MenuHelper::init();
$controller = MenuHelper::getControllerName();
$action     = MenuHelper::getActionName();
$owner = '';
if($controller=='document'){
    $owner = strpos(request()->getRequestUri(),'incoming') ? 'incoming':'outgoing';
}
?>
<aside
    class="sidebar-wrapper fixed top-0 z-30 block h-full w-[308px] bg-white dark:bg-darkblack-600 sm:hidden xl:block"
>
  <div
      class="sidebar-header relative z-30 flex h-[108px] w-full items-center border-b border-r border-b-[#F7F7F7] border-r-[#F7F7F7] pl-[50px] dark:border-darkblack-400"
  >
    <a href="{{ localeRoute('frontend.profile.companies.index') }}">
      <img
          src="{{ asset('profile/assets/images/logo/logo.png') }}"
          class="block dark:hidden"
          alt="logo"
          style="height: 100px"
      />
      <img
          src="{{ asset('profile/assets/images/logo/logo.png') }}"
          class="hidden dark:block"
          alt="logo"
      />
    </a>
    <button
        type="button"
        class="drawer-btn absolute right-0 top-auto"
        title="Ctrl+b"
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
  </div>
  <div
      class="sidebar-body overflow-style-none relative z-30 h-screen w-full overflow-y-scroll pb-[200px] pl-[48px] pt-[14px]"
  >
    <div class="nav-wrapper mb-[36px] pr-[50px]">
      <div class="item-wrapper mb-5">
        <h4
            class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50"
        >
          {{ __('main.menu') }}
        </h4>
        <ul class="mt-2.5">
          <li class="item py-[11px] text-bgray-900 dark:text-white {{ MenuHelper::check($controller,'company')}}">
            <a href="{{ localeRoute('frontend.profile.companies.index') }}">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                          <span class="item-ico">
                            <svg
                                width="18"
                                height="21"
                                viewBox="0 0 18 21"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  class="path-1"
                                  d="M0 8.84719C0 7.99027 0.366443 7.17426 1.00691 6.60496L6.34255 1.86217C7.85809 0.515019 10.1419 0.515019 11.6575 1.86217L16.9931 6.60496C17.6336 7.17426 18 7.99027 18 8.84719V17C18 19.2091 16.2091 21 14 21H4C1.79086 21 0 19.2091 0 17V8.84719Z"
                                  fill="#747474"
                              />
                              <path
                                  class="path-2"
                                  d="M5 17C5 14.7909 6.79086 13 9 13C11.2091 13 13 14.7909 13 17V21H5V17Z"
                                  fill="#ffa500"
                              />
                            </svg>
                          </span>
                  <span
                      class="item-text text-lg font-medium leading-none"
                  >{{__('main.companies')}}</span
                  >
                </div>
              </div>
            </a>
          </li>
          @isset($tarifs)
            @foreach($tarifs->modules as $module)
              @if(!$module->status)
                @continue
              @endif
              @if($module->slug=='guarant')
                @continue
              @endif

              @if($module->slug=='casses_order')

                <li class="item py-[11px] text-bgray-900 dark:text-white @if($controller=='incomingorder' || $controller=='expenseorder') active @endif">
                  <a href="{{ localeRoute("frontend.profile.modules.incoming_order.index") }}" >
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-2.5">
                        <span class="item-ico">
                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M18 16V6C18 3.79086 16.2091 2 14 2H4C1.79086 2 0 3.79086 0 6V16C0 18.2091 1.79086 20 4 20H14C16.2091 20 18 18.2091 18 16Z" fill="#747474" class="path-1"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 8C4.25 7.58579 4.58579 7.25 5 7.25H13C13.4142 7.25 13.75 7.58579 13.75 8C13.75 8.41421 13.4142 8.75 13 8.75H5C4.58579 8.75 4.25 8.41421 4.25 8Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 12C4.25 11.5858 4.58579 11.25 5 11.25H13C13.4142 11.25 13.75 11.5858 13.75 12C13.75 12.4142 13.4142 12.75 13 12.75H5C4.58579 12.75 4.25 12.4142 4.25 12Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 16C4.25 15.5858 4.58579 15.25 5 15.25H9C9.41421 15.25 9.75 15.5858 9.75 16C9.75 16.4142 9.41421 16.75 9 16.75H5C4.58579 16.75 4.25 16.4142 4.25 16Z" fill="#ffa500" class="path-2"></path>
                              <path d="M11 0H7C5.89543 0 5 0.895431 5 2C5 3.10457 5.89543 4 7 4H11C12.1046 4 13 3.10457 13 2C13 0.895431 12.1046 0 11 0Z" fill="#ffa500" class="path-2"></path>
                            </svg>
                          </span>
                        <span class="item-text text-lg font-medium leading-none">{{$module->getTitle()}}</span>
                      </div>
                    </div>
                  </a>
                </li>

                      <li class="item py-[11px] text-bgray-900 dark:text-white @if($controller=='companyaccount') active @endif">
                          <a href="{{ localeRoute("frontend.profile.company_account.index") }}" >
                              <div class="flex items-center justify-between">
                                  <div class="flex items-center space-x-2.5">
                        <span class="item-ico">
                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M18 16V6C18 3.79086 16.2091 2 14 2H4C1.79086 2 0 3.79086 0 6V16C0 18.2091 1.79086 20 4 20H14C16.2091 20 18 18.2091 18 16Z" fill="#747474" class="path-1"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 8C4.25 7.58579 4.58579 7.25 5 7.25H13C13.4142 7.25 13.75 7.58579 13.75 8C13.75 8.41421 13.4142 8.75 13 8.75H5C4.58579 8.75 4.25 8.41421 4.25 8Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 12C4.25 11.5858 4.58579 11.25 5 11.25H13C13.4142 11.25 13.75 11.5858 13.75 12C13.75 12.4142 13.4142 12.75 13 12.75H5C4.58579 12.75 4.25 12.4142 4.25 12Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 16C4.25 15.5858 4.58579 15.25 5 15.25H9C9.41421 15.25 9.75 15.5858 9.75 16C9.75 16.4142 9.41421 16.75 9 16.75H5C4.58579 16.75 4.25 16.4142 4.25 16Z" fill="#ffa500" class="path-2"></path>
                              <path d="M11 0H7C5.89543 0 5 0.895431 5 2C5 3.10457 5.89543 4 7 4H11C12.1046 4 13 3.10457 13 2C13 0.895431 12.1046 0 11 0Z" fill="#ffa500" class="path-2"></path>
                            </svg>
                          </span>
                                      <span class="item-text text-lg font-medium leading-none">{{__('main.movement_report')}}</span>
                                  </div>
                              </div>
                          </a>
                      </li>

              @elseif($module->slug=='product')
                <li class="item py-[11px] text-bgray-900 dark:text-white @if($owner=='incoming'){{MenuHelper::check($controller,'document')}}@endif">
                  <a href="{{ localeRoute("frontend.profile.modules.document.index",['owner'=>'incoming']) }}">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-2.5">
                        <span class="item-ico">
                            <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M17.1464 10.4394C16.8536 10.7323 16.8536 11.2072 17.1464 11.5001C17.4393 11.7929 17.9142 11.7929 18.2071 11.5001L19.5 10.2072C20.1834 9.52375 20.1834 8.41571 19.5 7.73229L18.2071 6.4394C17.9142 6.1465 17.4393 6.1465 17.1464 6.4394C16.8536 6.73229 16.8536 7.20716 17.1464 7.50006L17.8661 8.21973H11.75C11.3358 8.21973 11 8.55551 11 8.96973C11 9.38394 11.3358 9.71973 11.75 9.71973H17.8661L17.1464 10.4394Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.75 17.75H12C14.6234 17.75 16.75 15.6234 16.75 13C16.75 12.5858 16.4142 12.25 16 12.25C15.5858 12.25 15.25 12.5858 15.25 13C15.25 14.7949 13.7949 16.25 12 16.25H8.21412C7.34758 17.1733 6.11614 17.75 4.75 17.75ZM8.21412 1.75H12C13.7949 1.75 15.25 3.20507 15.25 5C15.25 5.41421 15.5858 5.75 16 5.75C16.4142 5.75 16.75 5.41421 16.75 5C16.75 2.37665 14.6234 0.25 12 0.25H4.75C6.11614 0.25 7.34758 0.82673 8.21412 1.75Z" fill="#747474" class="path-1"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M0 5C0 2.37665 2.12665 0.25 4.75 0.25C7.37335 0.25 9.5 2.37665 9.5 5V13C9.5 15.6234 7.37335 17.75 4.75 17.75C2.12665 17.75 0 15.6234 0 13V5Z" fill="#747474" class="path-1"></path>
                            </svg>
                          </span>
{{--                          <span class="item-ico">--}}
{{--                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                              <path d="M20 4C20 1.79086 18.2091 0 16 0H4C1.79086 0 0 1.79086 0 4V14C0 16.2091 1.79086 18 4 18H16C18.2091 18 20 16.2091 20 14V4Z" fill="#747474" class="path-1"></path>--}}
{{--                              <path d="M6 9C6 7.34315 4.65685 6 3 6H0V12H3C4.65685 12 6 10.6569 6 9Z" fill="#ffa500" class="path-2"></path>--}}
{{--                            </svg>--}}
{{--                          </span>--}}
                        <span class="item-text text-lg font-medium leading-none">{!! __('main.product_incoming')!!}</span>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="item py-[11px] text-bgray-900 dark:text-white @if($owner=='outgoing'){{MenuHelper::check($controller,'document')}} @endif">
                  <a href="{{ localeRoute("frontend.profile.modules.document.index",['owner'=>'outgoing']) }}">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-2.5">
                          <span class="item-ico" style="transform: rotate(180deg)">
                            <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M17.1464 10.4394C16.8536 10.7323 16.8536 11.2072 17.1464 11.5001C17.4393 11.7929 17.9142 11.7929 18.2071 11.5001L19.5 10.2072C20.1834 9.52375 20.1834 8.41571 19.5 7.73229L18.2071 6.4394C17.9142 6.1465 17.4393 6.1465 17.1464 6.4394C16.8536 6.73229 16.8536 7.20716 17.1464 7.50006L17.8661 8.21973H11.75C11.3358 8.21973 11 8.55551 11 8.96973C11 9.38394 11.3358 9.71973 11.75 9.71973H17.8661L17.1464 10.4394Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.75 17.75H12C14.6234 17.75 16.75 15.6234 16.75 13C16.75 12.5858 16.4142 12.25 16 12.25C15.5858 12.25 15.25 12.5858 15.25 13C15.25 14.7949 13.7949 16.25 12 16.25H8.21412C7.34758 17.1733 6.11614 17.75 4.75 17.75ZM8.21412 1.75H12C13.7949 1.75 15.25 3.20507 15.25 5C15.25 5.41421 15.5858 5.75 16 5.75C16.4142 5.75 16.75 5.41421 16.75 5C16.75 2.37665 14.6234 0.25 12 0.25H4.75C6.11614 0.25 7.34758 0.82673 8.21412 1.75Z" fill="#747474" class="path-1"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M0 5C0 2.37665 2.12665 0.25 4.75 0.25C7.37335 0.25 9.5 2.37665 9.5 5V13C9.5 15.6234 7.37335 17.75 4.75 17.75C2.12665 17.75 0 15.6234 0 13V5Z" fill="#747474" class="path-1"></path>
                            </svg>
                          </span>
                        <span class="item-text text-lg font-medium leading-none">{!! __('main.product_outgoing')!!}</span>
                      </div>
                    </div>
                  </a>
                </li>
              @else
                <li class="item py-[11px] text-bgray-900 dark:text-white {{ MenuHelper::check($controller,str_replace('_','',$module->slug))}}">
                  <a href="{{ localeRoute("frontend.profile.modules.$module->slug.index") }}">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-2.5">
                     <span class="item-ico">
                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M5 2V11C5 12.1046 5.89543 13 7 13H18C19.1046 13 20 12.1046 20 11V2C20 0.895431 19.1046 0 18 0H7C5.89543 0 5 0.89543 5 2Z" fill="#747474" class="path-1"></path>
                              <path d="M0 15C0 13.8954 0.895431 13 2 13H2.17157C2.70201 13 3.21071 13.2107 3.58579 13.5858C4.36683 14.3668 5.63317 14.3668 6.41421 13.5858C6.78929 13.2107 7.29799 13 7.82843 13H8C9.10457 13 10 13.8954 10 15V16C10 17.1046 9.10457 18 8 18H2C0.89543 18 0 17.1046 0 16V15Z" fill="#ffa500" class="path-2"></path>
                              <path d="M7.5 9.5C7.5 10.8807 6.38071 12 5 12C3.61929 12 2.5 10.8807 2.5 9.5C2.5 8.11929 3.61929 7 5 7C6.38071 7 7.5 8.11929 7.5 9.5Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M8.25 4.5C8.25 4.08579 8.58579 3.75 9 3.75L16 3.75C16.4142 3.75 16.75 4.08579 16.75 4.5C16.75 4.91421 16.4142 5.25 16 5.25L9 5.25C8.58579 5.25 8.25 4.91421 8.25 4.5Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M11.25 8.5C11.25 8.08579 11.5858 7.75 12 7.75L16 7.75C16.4142 7.75 16.75 8.08579 16.75 8.5C16.75 8.91421 16.4142 9.25 16 9.25L12 9.25C11.5858 9.25 11.25 8.91421 11.25 8.5Z" fill="#ffa500" class="path-2"></path>
                            </svg>
                          </span>
{{--                          <span class="item-ico">--}}
{{--                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                              <path d="M20 4C20 1.79086 18.2091 0 16 0H4C1.79086 0 0 1.79086 0 4V14C0 16.2091 1.79086 18 4 18H16C18.2091 18 20 16.2091 20 14V4Z" fill="#747474" class="path-1"></path>--}}
{{--                              <path d="M6 9C6 7.34315 4.65685 6 3 6H0V12H3C4.65685 12 6 10.6569 6 9Z" fill="#ffa500" class="path-2"></path>--}}
{{--                            </svg>--}}
{{--                          </span>--}}
                        <span class="item-text text-lg font-medium leading-none">{{$module->getTitle()}}</span>
                      </div>
                    </div>
                  </a></li>
              @endif

            @endforeach

          @endisset
          <li class="item py-[11px] text-bgray-900 dark:text-white">
            <a href="#" class="nav-link">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                  <span class="item-ico">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M0 4C0 1.79086 1.79086 0 4 0H16C18.2091 0 20 1.79086 20 4V16C20 18.2091 18.2091 20 16 20H4C1.79086 20 0 18.2091 0 16V4Z" fill="#747474" class="path-1"></path>
                              <path d="M14 9C12.8954 9 12 9.89543 12 11L12 13C12 14.1046 12.8954 15 14 15C15.1046 15 16 14.1046 16 13V11C16 9.89543 15.1046 9 14 9Z" fill="#ffa500" class="path-2"></path>
                              <path d="M6 5C4.89543 5 4 5.89543 4 7L4 13C4 14.1046 4.89543 15 6 15C7.10457 15 8 14.1046 8 13L8 7C8 5.89543 7.10457 5 6 5Z" fill="#ffa500" class="path-2"></path>
                            </svg>
                          </span>
{{--                          <span class="item-ico">--}}
{{--                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                              <path d="M20 4C20 1.79086 18.2091 0 16 0H4C1.79086 0 0 1.79086 0 4V14C0 16.2091 1.79086 18 4 18H16C18.2091 18 20 16.2091 20 14V4Z" fill="#747474" class="path-1"></path>--}}
{{--                              <path d="M6 9C6 7.34315 4.65685 6 3 6H0V12H3C4.65685 12 6 10.6569 6 9Z" fill="#ffa500" class="path-2"></path>--}}
{{--                            </svg>--}}
{{--                          </span>--}}
                  <span class="item-text text-lg font-medium leading-none">Производство</span>
                </div>
              </div>
            </a>
          </li>
          <li class="item py-[11px] text-bgray-900 dark:text-white">
            <a href="#" class="nav-link">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                  <span class="item-ico">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M18 11C18 15.9706 13.9706 20 9 20C4.02944 20 0 15.9706 0 11C0 6.02944 4.02944 2 9 2C13.9706 2 18 6.02944 18 11Z" fill="#747474" class="path-1"></path>
                              <path d="M19.8025 8.01277C19.0104 4.08419 15.9158 0.989557 11.9872 0.197453C10.9045 -0.0208635 10 0.89543 10 2V8C10 9.10457 10.8954 10 12 10H18C19.1046 10 20.0209 9.09555 19.8025 8.01277Z" fill="#ffa500" class="path-2"></path>
                            </svg>
                          </span>
{{--                          <span class="item-ico">--}}
{{--                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                              <path d="M20 4C20 1.79086 18.2091 0 16 0H4C1.79086 0 0 1.79086 0 4V14C0 16.2091 1.79086 18 4 18H16C18.2091 18 20 16.2091 20 14V4Z" fill="#747474" class="path-1"></path>--}}
{{--                              <path d="M6 9C6 7.34315 4.65685 6 3 6H0V12H3C4.65685 12 6 10.6569 6 9Z" fill="#ffa500" class="path-2"></path>--}}
{{--                            </svg>--}}
{{--                          </span>--}}
                  <span class="item-text text-lg font-medium leading-none">Основные средства</span>
                </div>
              </div>
            </a>
          </li>
          <li class="item py-[11px] text-bgray-900 dark:text-white">
            <a href="#" class="nav-link">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                <span class="item-ico">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <ellipse cx="11.7778" cy="17.5555" rx="7.77778" ry="4.44444" fill="#747474" class="path-1"></ellipse>
                              <circle cx="11.7778" cy="6.44444" r="4.44444" fill="#ffa500" class="path-2"></circle>
                            </svg>
                          </span>
                  <span class="item-text text-lg font-medium leading-none">Кадры</span>
                </div>
              </div>
            </a>
          </li>
          <li class="item py-[11px] text-bgray-900 dark:text-white">
            <a href="#" class="nav-link">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
      <span class="item-ico">
                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M20 4C20 1.79086 18.2091 0 16 0H4C1.79086 0 0 1.79086 0 4V14C0 16.2091 1.79086 18 4 18H16C18.2091 18 20 16.2091 20 14V4Z" fill="#747474" class="path-1"></path>
                              <path d="M6 9C6 7.34315 4.65685 6 3 6H0V12H3C4.65685 12 6 10.6569 6 9Z" fill="#ffa500" class="path-2"></path>
                            </svg>
                          </span>
                  <span class="item-text text-lg font-medium leading-none">Заработная плата</span>
                </div>
              </div>
            </a>
          </li>
          <li class="item py-[11px] text-bgray-900 dark:text-white">
            <a href="#" class="nav-link">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                               <span class="item-ico">
                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M18 16V6C18 3.79086 16.2091 2 14 2H4C1.79086 2 0 3.79086 0 6V16C0 18.2091 1.79086 20 4 20H14C16.2091 20 18 18.2091 18 16Z" fill="#747474" class="path-1"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 8C4.25 7.58579 4.58579 7.25 5 7.25H13C13.4142 7.25 13.75 7.58579 13.75 8C13.75 8.41421 13.4142 8.75 13 8.75H5C4.58579 8.75 4.25 8.41421 4.25 8Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 12C4.25 11.5858 4.58579 11.25 5 11.25H13C13.4142 11.25 13.75 11.5858 13.75 12C13.75 12.4142 13.4142 12.75 13 12.75H5C4.58579 12.75 4.25 12.4142 4.25 12Z" fill="#ffa500" class="path-2"></path>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 16C4.25 15.5858 4.58579 15.25 5 15.25H9C9.41421 15.25 9.75 15.5858 9.75 16C9.75 16.4142 9.41421 16.75 9 16.75H5C4.58579 16.75 4.25 16.4142 4.25 16Z" fill="#ffa500" class="path-2"></path>
                              <path d="M11 0H7C5.89543 0 5 0.895431 5 2C5 3.10457 5.89543 4 7 4H11C12.1046 4 13 3.10457 13 2C13 0.895431 12.1046 0 11 0Z" fill="#ffa500" class="path-2"></path>
                            </svg>
                          </span>

                  <span class="item-text text-lg font-medium leading-none">Отчеты</span>
                </div>
              </div>
            </a>
          </li>

          <div class="item-wrapper">
            <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
              API
            </h4>
            <ul class="mt-2.5">
              <li class="item py-[11px] text-bgray-900 dark:text-white">
                <a href="//soliq.uz" class="nav-link" target="_blank" style="display: flex; align-items: center;">
                  <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="15px" height="15px"><path d="M 33.40625 0 C 32.855469 0.0507813 32.449219 0.542969 32.5 1.09375 C 32.550781 1.644531 33.042969 2.050781 33.59375 2 L 46.5625 2 L 25.6875 22.90625 C 25.390625 23.148438 25.253906 23.535156 25.339844 23.910156 C 25.425781 24.28125 25.71875 24.574219 26.089844 24.660156 C 26.464844 24.746094 26.851563 24.609375 27.09375 24.3125 L 48 3.4375 L 48 16.40625 C 47.996094 16.765625 48.183594 17.101563 48.496094 17.285156 C 48.808594 17.464844 49.191406 17.464844 49.503906 17.285156 C 49.816406 17.101563 50.003906 16.765625 50 16.40625 L 50 0 L 33.59375 0 C 33.5625 0 33.53125 0 33.5 0 C 33.46875 0 33.4375 0 33.40625 0 Z M 2 10 C 1.476563 10 0.941406 10.183594 0.5625 10.5625 C 0.183594 10.941406 0 11.476563 0 12 L 0 48 C 0 48.523438 0.183594 49.058594 0.5625 49.4375 C 0.941406 49.816406 1.476563 50 2 50 L 38 50 C 38.523438 50 39.058594 49.816406 39.4375 49.4375 C 39.816406 49.058594 40 48.523438 40 48 L 40 18 C 40.003906 17.640625 39.816406 17.304688 39.503906 17.121094 C 39.191406 16.941406 38.808594 16.941406 38.496094 17.121094 C 38.183594 17.304688 37.996094 17.640625 38 18 L 38 48 L 2 48 L 2 12 L 32 12 C 32.359375 12.003906 32.695313 11.816406 32.878906 11.503906 C 33.058594 11.191406 33.058594 10.808594 32.878906 10.496094 C 32.695313 10.183594 32.359375 9.996094 32 10 Z"/></svg>
                  <span style="margin-left: 5px">soliq.uz</span>
                </a>
              </li>
              <li class="item py-[11px] text-bgray-900 dark:text-white">
                <a href="//mehnat.uz" class="nav-link" target="_blank" style="display: flex; align-items: center;">
                  <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="15px" height="15px"><path d="M 33.40625 0 C 32.855469 0.0507813 32.449219 0.542969 32.5 1.09375 C 32.550781 1.644531 33.042969 2.050781 33.59375 2 L 46.5625 2 L 25.6875 22.90625 C 25.390625 23.148438 25.253906 23.535156 25.339844 23.910156 C 25.425781 24.28125 25.71875 24.574219 26.089844 24.660156 C 26.464844 24.746094 26.851563 24.609375 27.09375 24.3125 L 48 3.4375 L 48 16.40625 C 47.996094 16.765625 48.183594 17.101563 48.496094 17.285156 C 48.808594 17.464844 49.191406 17.464844 49.503906 17.285156 C 49.816406 17.101563 50.003906 16.765625 50 16.40625 L 50 0 L 33.59375 0 C 33.5625 0 33.53125 0 33.5 0 C 33.46875 0 33.4375 0 33.40625 0 Z M 2 10 C 1.476563 10 0.941406 10.183594 0.5625 10.5625 C 0.183594 10.941406 0 11.476563 0 12 L 0 48 C 0 48.523438 0.183594 49.058594 0.5625 49.4375 C 0.941406 49.816406 1.476563 50 2 50 L 38 50 C 38.523438 50 39.058594 49.816406 39.4375 49.4375 C 39.816406 49.058594 40 48.523438 40 48 L 40 18 C 40.003906 17.640625 39.816406 17.304688 39.503906 17.121094 C 39.191406 16.941406 38.808594 16.941406 38.496094 17.121094 C 38.183594 17.304688 37.996094 17.640625 38 18 L 38 48 L 2 48 L 2 12 L 32 12 C 32.359375 12.003906 32.695313 11.816406 32.878906 11.503906 C 33.058594 11.191406 33.058594 10.808594 32.878906 10.496094 C 32.695313 10.183594 32.359375 9.996094 32 10 Z"/></svg>
                  <span style="margin-left: 5px">mehnat.uz</span>
                </a>
              </li>
              <li class="item py-[11px] text-bgray-900 dark:text-white">
                <a href="//stat.uz" class="nav-link" target="_blank" style="display: flex; align-items: center;">
                  <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="15px" height="15px"><path d="M 33.40625 0 C 32.855469 0.0507813 32.449219 0.542969 32.5 1.09375 C 32.550781 1.644531 33.042969 2.050781 33.59375 2 L 46.5625 2 L 25.6875 22.90625 C 25.390625 23.148438 25.253906 23.535156 25.339844 23.910156 C 25.425781 24.28125 25.71875 24.574219 26.089844 24.660156 C 26.464844 24.746094 26.851563 24.609375 27.09375 24.3125 L 48 3.4375 L 48 16.40625 C 47.996094 16.765625 48.183594 17.101563 48.496094 17.285156 C 48.808594 17.464844 49.191406 17.464844 49.503906 17.285156 C 49.816406 17.101563 50.003906 16.765625 50 16.40625 L 50 0 L 33.59375 0 C 33.5625 0 33.53125 0 33.5 0 C 33.46875 0 33.4375 0 33.40625 0 Z M 2 10 C 1.476563 10 0.941406 10.183594 0.5625 10.5625 C 0.183594 10.941406 0 11.476563 0 12 L 0 48 C 0 48.523438 0.183594 49.058594 0.5625 49.4375 C 0.941406 49.816406 1.476563 50 2 50 L 38 50 C 38.523438 50 39.058594 49.816406 39.4375 49.4375 C 39.816406 49.058594 40 48.523438 40 48 L 40 18 C 40.003906 17.640625 39.816406 17.304688 39.503906 17.121094 C 39.191406 16.941406 38.808594 16.941406 38.496094 17.121094 C 38.183594 17.304688 37.996094 17.640625 38 18 L 38 48 L 2 48 L 2 12 L 32 12 C 32.359375 12.003906 32.695313 11.816406 32.878906 11.503906 C 33.058594 11.191406 33.058594 10.808594 32.878906 10.496094 C 32.695313 10.183594 32.359375 9.996094 32 10 Z"/></svg>
                  <span style="margin-left: 5px">stat.uz</span>
                </a>
              </li>
                <li class="item py-[11px] text-bgray-900 dark:text-white">
                <a href="//customs.uz" class="nav-link" target="_blank" style="display: flex; align-items: center;">
                  <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="15px" height="15px"><path d="M 33.40625 0 C 32.855469 0.0507813 32.449219 0.542969 32.5 1.09375 C 32.550781 1.644531 33.042969 2.050781 33.59375 2 L 46.5625 2 L 25.6875 22.90625 C 25.390625 23.148438 25.253906 23.535156 25.339844 23.910156 C 25.425781 24.28125 25.71875 24.574219 26.089844 24.660156 C 26.464844 24.746094 26.851563 24.609375 27.09375 24.3125 L 48 3.4375 L 48 16.40625 C 47.996094 16.765625 48.183594 17.101563 48.496094 17.285156 C 48.808594 17.464844 49.191406 17.464844 49.503906 17.285156 C 49.816406 17.101563 50.003906 16.765625 50 16.40625 L 50 0 L 33.59375 0 C 33.5625 0 33.53125 0 33.5 0 C 33.46875 0 33.4375 0 33.40625 0 Z M 2 10 C 1.476563 10 0.941406 10.183594 0.5625 10.5625 C 0.183594 10.941406 0 11.476563 0 12 L 0 48 C 0 48.523438 0.183594 49.058594 0.5625 49.4375 C 0.941406 49.816406 1.476563 50 2 50 L 38 50 C 38.523438 50 39.058594 49.816406 39.4375 49.4375 C 39.816406 49.058594 40 48.523438 40 48 L 40 18 C 40.003906 17.640625 39.816406 17.304688 39.503906 17.121094 C 39.191406 16.941406 38.808594 16.941406 38.496094 17.121094 C 38.183594 17.304688 37.996094 17.640625 38 18 L 38 48 L 2 48 L 2 12 L 32 12 C 32.359375 12.003906 32.695313 11.816406 32.878906 11.503906 C 33.058594 11.191406 33.058594 10.808594 32.878906 10.496094 C 32.695313 10.183594 32.359375 9.996094 32 10 Z"/></svg>
                  <span style="margin-left: 5px">customs.uz</span>
                </a>
              </li>
            </ul>
          </div>

          <ul class="flex justify-center items-center gap-x-4 mt-8">
            <li class="group">
              <a  href="https://instagramm.com/wambat" class="transition-all w-9 h-9 rounded-lg bg-bgray-300 inline-flex items-center justify-center group-hover:bg-success-300 dark:bg-darkblack-600">
                <svg class="stroke-bgray-600 group-hover:stroke-white" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="1.6665" y="1.66699" width="16.6667" height="16.6667" rx="4" stroke-width="1.5"></rect>
                  <ellipse cx="14.9998" cy="5.00033" rx="0.833333" ry="0.833333" fill="white"></ellipse>
                  <circle cx="10.0002" cy="9.99967" r="4.16667" stroke-width="1.5"></circle>
                </svg>
              </a>
            </li>
            <li class="group">
              <a href="https://t.me/wombat"  class="transition-all w-9 h-9 rounded-lg bg-bgray-300 inline-flex items-center justify-center group-hover:bg-success-300 dark:bg-darkblack-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-bgray-600 group-hover:stroke-white" fill="#718096"  viewBox="0 0 24 24" width="20px" height="20px">
                  <path d="M 20.572266 3.0117188 C 20.239891 2.9764687 19.878625 3.028375 19.515625 3.171875 C 19.065625 3.348875 12.014406 6.3150313 5.4414062 9.0820312 L 3.2695312 9.9960938 C 2.4285313 10.337094 2.0039062 10.891672 2.0039062 11.638672 C 2.0039062 12.161672 2.22525 12.871063 3.28125 13.289062 L 6.9472656 14.757812 C 7.2642656 15.708813 8.0005469 17.916906 8.1855469 18.503906 C 8.2955469 18.851906 8.5733906 19.728594 9.2753906 19.933594 C 9.4193906 19.982594 9.5696563 20.007813 9.7226562 20.007812 C 10.165656 20.007812 10.484625 19.801641 10.640625 19.681641 L 12.970703 17.710938 L 15.800781 20.328125 C 15.909781 20.439125 16.486719 21 17.261719 21 C 18.228719 21 18.962234 20.195016 19.115234 19.416016 C 19.198234 18.989016 21.927734 5.2870625 21.927734 5.2890625 C 22.172734 4.1900625 21.732219 3.6199531 21.449219 3.3769531 C 21.206719 3.1694531 20.904641 3.0469688 20.572266 3.0117188 z M 19.910156 5.171875 C 19.533156 7.061875 17.478016 17.378234 17.166016 18.865234 L 13.029297 15.039062 L 10.222656 17.416016 L 11 14.375 C 11 14.375 16.362547 8.9468594 16.685547 8.6308594 C 16.945547 8.3778594 17 8.2891719 17 8.2011719 C 17 8.0841719 16.939781 8 16.800781 8 C 16.675781 8 16.506016 8.1197812 16.416016 8.1757812 C 15.272669 8.8885973 10.404094 11.662239 8.0078125 13.025391 L 4.53125 11.636719 L 6.21875 10.927734 C 10.51775 9.1177344 18.174156 5.893875 19.910156 5.171875 z"/>
                </svg>
              </a>
            </li>
            <li class="group">
              <a href="https://facebook.com/wambat" class="transition-all w-9 h-9 rounded-lg bg-bgray-300 inline-flex items-center justify-center group-hover:bg-success-300 dark:bg-darkblack-600">
                <svg class="stroke-bgray-600 group-hover:stroke-white" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15 2.5H13.3333C10.5719 2.5 8.33333 4.73858 8.33333 7.5V8.33333H5V11.6667H8.33333V17.5H11.6667V11.6667H15V8.33333H11.6667V6.83333C11.6667 6.28105 12.1144 5.83333 12.6667 5.83333H15V2.5Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </a>
            </li>
            <li class="group">
              <a href="" class="transition-all w-9 h-9 rounded-lg bg-bgray-300 inline-flex items-center justify-center group-hover:bg-success-300 dark:bg-darkblack-600"><svg class="stroke-bgray-600 group-hover:stroke-white" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_1699_21823)">
                    <path d="M3.33317 10.0003C3.33317 13.6822 6.31794 16.667 9.99984 16.667C10.2699 16.667 10.5362 16.6509 10.7979 16.6197C11.5556 17.6586 12.7822 18.3337 14.1665 18.3337C16.4677 18.3337 18.3332 16.4682 18.3332 14.167C18.3332 12.7827 17.6581 11.5561 16.6192 10.7984C16.6504 10.5367 16.6665 10.2704 16.6665 10.0003C16.6665 6.31843 13.6817 3.33366 9.99984 3.33366C9.72977 3.33366 9.46346 3.34972 9.2018 3.38093C8.44408 2.34205 7.21746 1.66699 5.83317 1.66699C3.53198 1.66699 1.6665 3.53247 1.6665 5.83366C1.6665 7.21795 2.34156 8.44457 3.38044 9.20229C3.34923 9.46395 3.33317 9.73026 3.33317 10.0003Z" stroke-width="1.5" stroke-linejoin="round"></path>
                    <path d="M12.0832 8.33366C12.0832 7.41318 11.337 6.66699 10.4165 6.66699H9.58317C8.6627 6.66699 7.9165 7.41318 7.9165 8.33366C7.9165 9.25413 8.6627 10.0003 9.58317 10.0003H10.4165C11.337 10.0003 12.0832 10.7465 12.0832 11.667C12.0832 12.5875 11.337 13.3337 10.4165 13.3337H9.58317C8.6627 13.3337 7.9165 12.5875 7.9165 11.667" stroke-width="1.5" stroke-linecap="round"></path>
                  </g>
                  <defs>
                    <clipPath id="clip0_1699_21823">
                      <rect width="20" height="20" fill="white"></rect>
                    </clipPath>
                  </defs>
                </svg>
              </a>
            </li>
          </ul>

          <li class="item py-[11px] text-bgray-900 dark:text-white">
            <hr>
          </li>
{{--          <li class="social-links d-flex justify-center" style="display: flex">--}}
{{--            <a href="https://t.me/wombat" class="nav-link" target="_blank" title="telegram">--}}
{{--              <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="25px" height="25px"><path d="M 44.376953 5.9863281 C 43.889905 6.0076957 43.415817 6.1432497 42.988281 6.3144531 C 42.565113 6.4845113 40.128883 7.5243408 36.53125 9.0625 C 32.933617 10.600659 28.256963 12.603668 23.621094 14.589844 C 14.349356 18.562196 5.2382813 22.470703 5.2382812 22.470703 L 5.3046875 22.445312 C 5.3046875 22.445312 4.7547875 22.629122 4.1972656 23.017578 C 3.9185047 23.211806 3.6186028 23.462555 3.3730469 23.828125 C 3.127491 24.193695 2.9479735 24.711788 3.015625 25.259766 C 3.2532479 27.184511 5.2480469 27.730469 5.2480469 27.730469 L 5.2558594 27.734375 L 14.158203 30.78125 C 14.385177 31.538434 16.858319 39.792923 17.402344 41.541016 C 17.702797 42.507484 17.984013 43.064995 18.277344 43.445312 C 18.424133 43.635633 18.577962 43.782915 18.748047 43.890625 C 18.815627 43.933415 18.8867 43.965525 18.957031 43.994141 C 18.958531 43.994806 18.959437 43.99348 18.960938 43.994141 C 18.969579 43.997952 18.977708 43.998295 18.986328 44.001953 L 18.962891 43.996094 C 18.979231 44.002694 18.995359 44.013801 19.011719 44.019531 C 19.043456 44.030655 19.062905 44.030268 19.103516 44.039062 C 20.123059 44.395042 20.966797 43.734375 20.966797 43.734375 L 21.001953 43.707031 L 26.470703 38.634766 L 35.345703 45.554688 L 35.457031 45.605469 C 37.010484 46.295216 38.415349 45.910403 39.193359 45.277344 C 39.97137 44.644284 40.277344 43.828125 40.277344 43.828125 L 40.310547 43.742188 L 46.832031 9.7519531 C 46.998903 8.9915162 47.022612 8.334202 46.865234 7.7402344 C 46.707857 7.1462668 46.325492 6.6299361 45.845703 6.34375 C 45.365914 6.0575639 44.864001 5.9649605 44.376953 5.9863281 z M 44.429688 8.0195312 C 44.627491 8.0103707 44.774102 8.032983 44.820312 8.0605469 C 44.866523 8.0881109 44.887272 8.0844829 44.931641 8.2519531 C 44.976011 8.419423 45.000036 8.7721605 44.878906 9.3242188 L 44.875 9.3359375 L 38.390625 43.128906 C 38.375275 43.162926 38.240151 43.475531 37.931641 43.726562 C 37.616914 43.982653 37.266874 44.182554 36.337891 43.792969 L 26.632812 36.224609 L 26.359375 36.009766 L 26.353516 36.015625 L 23.451172 33.837891 L 39.761719 14.648438 A 1.0001 1.0001 0 0 0 38.974609 13 A 1.0001 1.0001 0 0 0 38.445312 13.167969 L 14.84375 28.902344 L 5.9277344 25.849609 C 5.9277344 25.849609 5.0423771 25.356927 5 25.013672 C 4.99765 24.994652 4.9871961 25.011869 5.0332031 24.943359 C 5.0792101 24.874869 5.1948546 24.759225 5.3398438 24.658203 C 5.6298218 24.456159 5.9609375 24.333984 5.9609375 24.333984 L 5.9941406 24.322266 L 6.0273438 24.308594 C 6.0273438 24.308594 15.138894 20.399882 24.410156 16.427734 C 29.045787 14.44166 33.721617 12.440122 37.318359 10.902344 C 40.914175 9.3649615 43.512419 8.2583658 43.732422 8.1699219 C 43.982886 8.0696253 44.231884 8.0286918 44.429688 8.0195312 z M 33.613281 18.792969 L 21.244141 33.345703 L 21.238281 33.351562 A 1.0001 1.0001 0 0 0 21.183594 33.423828 A 1.0001 1.0001 0 0 0 21.128906 33.507812 A 1.0001 1.0001 0 0 0 20.998047 33.892578 A 1.0001 1.0001 0 0 0 20.998047 33.900391 L 19.386719 41.146484 C 19.35993 41.068197 19.341173 41.039555 19.3125 40.947266 L 19.3125 40.945312 C 18.800713 39.30085 16.467362 31.5161 16.144531 30.439453 L 33.613281 18.792969 z M 22.640625 35.730469 L 24.863281 37.398438 L 21.597656 40.425781 L 22.640625 35.730469 z"/></svg>--}}
{{--            </a>--}}
{{--            <a href="https://facebook.com/wambat" class="nav-link" target="_blank" style="padding: 0px !important; margin: 0 15px" title="facebook">--}}
{{--              <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="25px" height="25px"><path d="M 30.140625 2 C 26.870375 2 24.045399 2.9969388 22.0625 4.9667969 C 20.079601 6.936655 19 9.823825 19 13.367188 L 19 18 L 13 18 A 1.0001 1.0001 0 0 0 12 19 L 12 27 A 1.0001 1.0001 0 0 0 13 28 L 19 28 L 19 47 A 1.0001 1.0001 0 0 0 20 48 L 28 48 A 1.0001 1.0001 0 0 0 29 47 L 29 28 L 36 28 A 1.0001 1.0001 0 0 0 36.992188 27.125 L 37.992188 19.125 A 1.0001 1.0001 0 0 0 37 18 L 29 18 L 29 14 C 29 12.883334 29.883334 12 31 12 L 37 12 A 1.0001 1.0001 0 0 0 38 11 L 38 3.3457031 A 1.0001 1.0001 0 0 0 37.130859 2.3554688 C 36.247185 2.2382213 33.057174 2 30.140625 2 z M 30.140625 4 C 32.578477 4 34.935105 4.195047 36 4.2949219 L 36 10 L 31 10 C 28.802666 10 27 11.802666 27 14 L 27 19 A 1.0001 1.0001 0 0 0 28 20 L 35.867188 20 L 35.117188 26 L 28 26 A 1.0001 1.0001 0 0 0 27 27 L 27 46 L 21 46 L 21 27 A 1.0001 1.0001 0 0 0 20 26 L 14 26 L 14 20 L 20 20 A 1.0001 1.0001 0 0 0 21 19 L 21 13.367188 C 21 10.22255 21.920305 7.9269075 23.472656 6.3847656 C 25.025007 4.8426237 27.269875 4 30.140625 4 z"/></svg>--}}
{{--            </a>--}}
{{--            <a href="https://instagramm.com/wambat" class="nav-link" target="_blank" style="padding: 0px !important;" title="instagram">--}}
{{--              <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="25px" height="25px"><path d="M 16 3 C 8.8324839 3 3 8.8324839 3 16 L 3 34 C 3 41.167516 8.8324839 47 16 47 L 34 47 C 41.167516 47 47 41.167516 47 34 L 47 16 C 47 8.8324839 41.167516 3 34 3 L 16 3 z M 16 5 L 34 5 C 40.086484 5 45 9.9135161 45 16 L 45 34 C 45 40.086484 40.086484 45 34 45 L 16 45 C 9.9135161 45 5 40.086484 5 34 L 5 16 C 5 9.9135161 9.9135161 5 16 5 z M 37 11 A 2 2 0 0 0 35 13 A 2 2 0 0 0 37 15 A 2 2 0 0 0 39 13 A 2 2 0 0 0 37 11 z M 25 14 C 18.936712 14 14 18.936712 14 25 C 14 31.063288 18.936712 36 25 36 C 31.063288 36 36 31.063288 36 25 C 36 18.936712 31.063288 14 25 14 z M 25 16 C 29.982407 16 34 20.017593 34 25 C 34 29.982407 29.982407 34 25 34 C 20.017593 34 16 29.982407 16 25 C 16 20.017593 20.017593 16 25 16 z"/></svg>--}}
{{--            </a>--}}

{{--          </li>--}}
        </ul>
      </div>
    </div>
  </div>
</aside>
<div
    style="z-index: 25"
    class="aside-overlay fixed left-0 top-0 block h-full w-full bg-black bg-opacity-30 sm:hidden"
></div>
<aside class="relative hidden w-[96px] bg-white dark:bg-black sm:block">
  <div class="sidebar-wrapper-collapse relative top-0 z-30 w-full">
    <div
        class="sidebar-header sticky top-0 z-20 flex h-[108px] w-full items-center justify-center border-b border-r border-b-[#F7F7F7] border-r-[#F7F7F7] bg-white dark:border-darkblack-500 dark:bg-darkblack-600"
    >
      <a href="{{ localeRoute('frontend.profile.companies.index') }}">
        <img
            src="{{ asset('profile/assets/images/logo/logo-short.jpg') }}"
            class="block dark:hidden"
            width="45px"
            alt="logo"
        />
        <img
            src="{{ asset('profile/assets/images/logo/logo-short-white.svg') }}"
            class="hidden dark:block"
            alt="logo"
        />
      </a>
    </div>
    <div class="sidebar-body w-full pt-[14px]">
      <div class="flex flex-col items-center">
        <div class="nav-wrapper mb-[36px]">
          <div class="item-wrapper mb-5">
            <ul
                class="mt-2.5 flex flex-col items-center justify-center"
            >
              <li class="item px-[43px] py-[11px]">
                <a href="{{ localeRoute('frontend.profile.companies.index') }}">
                          <span class="item-ico">
                            <svg
                                width="18"
                                height="21"
                                viewBox="0 0 18 21"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  class="path-1"
                                  d="M0 8.84719C0 7.99027 0.366443 7.17426 1.00691 6.60496L6.34255 1.86217C7.85809 0.515019 10.1419 0.515019 11.6575 1.86217L16.9931 6.60496C17.6336 7.17426 18 7.99027 18 8.84719V17C18 19.2091 16.2091 21 14 21H4C1.79086 21 0 19.2091 0 17V8.84719Z"
                                  fill="#747474"
                              />
                              <path
                                  class="path-2"
                                  d="M5 17C5 14.7909 6.79086 13 9 13C11.2091 13 13 14.7909 13 17V21H5V17Z"
                                  fill="#ffa500"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="{{ localeRoute('frontend.profile.modules.incoming_order.index') }}">
                          <span class="item-ico">
                            <svg
                                width="18"
                                height="20"
                                viewBox="0 0 18 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M18 16V6C18 3.79086 16.2091 2 14 2H4C1.79086 2 0 3.79086 0 6V16C0 18.2091 1.79086 20 4 20H14C16.2091 20 18 18.2091 18 16Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M4.25 8C4.25 7.58579 4.58579 7.25 5 7.25H13C13.4142 7.25 13.75 7.58579 13.75 8C13.75 8.41421 13.4142 8.75 13 8.75H5C4.58579 8.75 4.25 8.41421 4.25 8Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M4.25 12C4.25 11.5858 4.58579 11.25 5 11.25H13C13.4142 11.25 13.75 11.5858 13.75 12C13.75 12.4142 13.4142 12.75 13 12.75H5C4.58579 12.75 4.25 12.4142 4.25 12Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M4.25 16C4.25 15.5858 4.58579 15.25 5 15.25H9C9.41421 15.25 9.75 15.5858 9.75 16C9.75 16.4142 9.41421 16.75 9 16.75H5C4.58579 16.75 4.25 16.4142 4.25 16Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  d="M11 0H7C5.89543 0 5 0.895431 5 2C5 3.10457 5.89543 4 7 4H11C12.1046 4 13 3.10457 13 2C13 0.895431 12.1046 0 11 0Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="{{ localeRoute('frontend.profile.modules.nomenklature.index') }}">
                          <span class="item-ico">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M18 11C18 15.9706 13.9706 20 9 20C4.02944 20 0 15.9706 0 11C0 6.02944 4.02944 2 9 2C13.9706 2 18 6.02944 18 11Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  d="M19.8025 8.01277C19.0104 4.08419 15.9158 0.989557 11.9872 0.197453C10.9045 -0.0208635 10 0.89543 10 2V8C10 9.10457 10.8954 10 12 10H18C19.1046 10 20.0209 9.09555 19.8025 8.01277Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="{{ localeRoute('frontend.profile.modules.payment_order.index') }}">
                          <span class="item-ico">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M0 4C0 1.79086 1.79086 0 4 0H16C18.2091 0 20 1.79086 20 4V16C20 18.2091 18.2091 20 16 20H4C1.79086 20 0 18.2091 0 16V4Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  d="M14 9C12.8954 9 12 9.89543 12 11L12 13C12 14.1046 12.8954 15 14 15C15.1046 15 16 14.1046 16 13V11C16 9.89543 15.1046 9 14 9Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  d="M6 5C4.89543 5 4 5.89543 4 7L4 13C4 14.1046 4.89543 15 6 15C7.10457 15 8 14.1046 8 13L8 7C8 5.89543 7.10457 5 6 5Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="{{ localeRoute("frontend.profile.modules.document.index",['owner'=>'incoming']) }}">
                          <span class="item-ico">
                            <svg
                                width="20"
                                height="18"
                                viewBox="0 0 20 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M20 4C20 1.79086 18.2091 0 16 0H4C1.79086 0 0 1.79086 0 4V14C0 16.2091 1.79086 18 4 18H16C18.2091 18 20 16.2091 20 14V4Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  d="M6 9C6 7.34315 4.65685 6 3 6H0V12H3C4.65685 12 6 10.6569 6 9Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="{{ localeRoute("frontend.profile.modules.document.index",['owner'=>'outgoing']) }}">
                          <span class="item-ico">
                            <svg
                                width="16"
                                height="18"
                                viewBox="0 0 16 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M8 18C9.38503 18 10.5633 17.1652 11 16H5C5.43668 17.1652 6.61497 18 8 18Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M9.6896 0.754028C9.27403 0.291157 8.67102 0 8 0C6.74634 0 5.73005 1.01629 5.73005 2.26995V2.37366C3.58766 3.10719 2.0016 4.85063 1.76046 6.97519L1.31328 10.9153C1.23274 11.6249 0.933441 12.3016 0.447786 12.8721C-0.649243 14.1609 0.394434 16 2.22281 16H13.7772C15.6056 16 16.6492 14.1609 15.5522 12.8721C15.0666 12.3016 14.7673 11.6249 14.6867 10.9153L14.2395 6.97519C14.2333 6.92024 14.2262 6.86556 14.2181 6.81113C13.8341 6.93379 13.4248 7 13 7C10.7909 7 9 5.20914 9 3C9 2.16744 9.25436 1.3943 9.6896 0.754028Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <circle
                                  cx="13"
                                  cy="3"
                                  r="3"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="integrations.html">
                          <span class="item-ico">
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M1.57666 3.61499C1.57666 2.51042 2.47209 1.61499 3.57666 1.61499H8.5C9.60456 1.61499 10.5 2.51042 10.5 3.61499V8.53833C10.5 9.64289 9.60456 10.5383 8.49999 10.5383H3.57666C2.47209 10.5383 1.57666 9.64289 1.57666 8.53832V3.61499Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  d="M13.5 15.5383C13.5 14.4338 14.3954 13.5383 15.5 13.5383H20.4233C21.5279 13.5383 22.4233 14.4338 22.4233 15.5383V20.4617C22.4233 21.5662 21.5279 22.4617 20.4233 22.4617H15.5C14.3954 22.4617 13.5 21.5662 13.5 20.4617V15.5383Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <circle
                                  cx="6.03832"
                                  cy="18"
                                  r="4.46166"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M18 2C18.4142 2 18.75 2.33579 18.75 2.75V5.25H21.25C21.6642 5.25 22 5.58579 22 6C22 6.41421 21.6642 6.75 21.25 6.75H18.75V9.25C18.75 9.66421 18.4142 10 18 10C17.5858 10 17.25 9.66421 17.25 9.25V6.75H14.75C14.3358 6.75 14 6.41421 14 6C14 5.58579 14.3358 5.25 14.75 5.25H17.25V2.75C17.25 2.33579 17.5858 2 18 2Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="users.html">
                          <span class="item-ico">
                            <svg
                                width="14"
                                height="18"
                                viewBox="0 0 14 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <ellipse
                                  cx="7"
                                  cy="14"
                                  rx="7"
                                  ry="4"
                                  class="path-1"
                                  fill="#747474"
                              />
                              <circle
                                  cx="7"
                                  cy="4"
                                  r="4"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="history.html">
                          <span class="item-ico">
                            <svg
                                width="18"
                                height="21"
                                viewBox="0 0 18 21"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M17.5 12.5C17.5 17.1944 13.6944 21 9 21C4.30558 21 0.5 17.1944 0.5 12.5C0.5 7.80558 4.30558 4 9 4C13.6944 4 17.5 7.80558 17.5 12.5Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M8.99995 1.75C8.02962 1.75 7.09197 1.88462 6.20407 2.13575C5.80549 2.24849 5.39099 2.01676 5.27826 1.61818C5.16553 1.21961 5.39725 0.805108 5.79583 0.692376C6.81525 0.404046 7.89023 0.25 8.99995 0.25C10.1097 0.25 11.1846 0.404046 12.2041 0.692376C12.6026 0.805108 12.8344 1.21961 12.7216 1.61818C12.6089 2.01676 12.1944 2.24849 11.7958 2.13575C10.9079 1.88462 9.97028 1.75 8.99995 1.75Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  d="M11 13C11 14.1046 10.1046 15 9 15C7.89543 15 7 14.1046 7 13C7 11.8954 7.89543 11 9 11C10.1046 11 11 11.8954 11 13Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M9 7.25C9.41421 7.25 9.75 7.58579 9.75 8V12C9.75 12.4142 9.41421 12.75 9 12.75C8.58579 12.75 8.25 12.4142 8.25 12V8C8.25 7.58579 8.58579 7.25 9 7.25Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
            </ul>
          </div>
          <div class="item-wrapper mb-5">
            <ul
                class="mt-2.5 flex flex-col items-center justify-center"
            >
              <li class="item px-[43px] py-[11px]">
                <a href="support-ticket.html">
                          <span class="item-ico">
                            <svg
                                width="20"
                                height="18"
                                viewBox="0 0 20 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M5 2V11C5 12.1046 5.89543 13 7 13H18C19.1046 13 20 12.1046 20 11V2C20 0.895431 19.1046 0 18 0H7C5.89543 0 5 0.89543 5 2Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  d="M0 15C0 13.8954 0.895431 13 2 13H2.17157C2.70201 13 3.21071 13.2107 3.58579 13.5858C4.36683 14.3668 5.63317 14.3668 6.41421 13.5858C6.78929 13.2107 7.29799 13 7.82843 13H8C9.10457 13 10 13.8954 10 15V16C10 17.1046 9.10457 18 8 18H2C0.89543 18 0 17.1046 0 16V15Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  d="M7.5 9.5C7.5 10.8807 6.38071 12 5 12C3.61929 12 2.5 10.8807 2.5 9.5C2.5 8.11929 3.61929 7 5 7C6.38071 7 7.5 8.11929 7.5 9.5Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M8.25 4.5C8.25 4.08579 8.58579 3.75 9 3.75L16 3.75C16.4142 3.75 16.75 4.08579 16.75 4.5C16.75 4.91421 16.4142 5.25 16 5.25L9 5.25C8.58579 5.25 8.25 4.91421 8.25 4.5Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M11.25 8.5C11.25 8.08579 11.5858 7.75 12 7.75L16 7.75C16.4142 7.75 16.75 8.08579 16.75 8.5C16.75 8.91421 16.4142 9.25 16 9.25L12 9.25C11.5858 9.25 11.25 8.91421 11.25 8.5Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="settings.html">
                          <span class="item-ico">
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  d="M8.84849 0H7.15151C6.2143 0 5.45454 0.716345 5.45454 1.6C5.45454 2.61121 4.37259 3.25411 3.48444 2.77064L3.39424 2.72153C2.58258 2.27971 1.54473 2.54191 1.07612 3.30717L0.227636 4.69281C-0.240971 5.45808 0.0371217 6.43663 0.848773 6.87846C1.73734 7.36215 1.73734 8.63785 0.848771 9.12154C0.0371203 9.56337 -0.240972 10.5419 0.227635 11.3072L1.07612 12.6928C1.54473 13.4581 2.58258 13.7203 3.39424 13.2785L3.48444 13.2294C4.37259 12.7459 5.45454 13.3888 5.45454 14.4C5.45454 15.2837 6.2143 16 7.15151 16H8.84849C9.7857 16 10.5455 15.2837 10.5455 14.4C10.5455 13.3888 11.6274 12.7459 12.5156 13.2294L12.6058 13.2785C13.4174 13.7203 14.4553 13.4581 14.9239 12.6928L15.7724 11.3072C16.241 10.5419 15.9629 9.56336 15.1512 9.12153C14.2627 8.63784 14.2627 7.36216 15.1512 6.87847C15.9629 6.43664 16.241 5.45809 15.7724 4.69283L14.9239 3.30719C14.4553 2.54192 13.4174 2.27972 12.6058 2.72154L12.5156 2.77065C11.6274 3.25412 10.5455 2.61122 10.5455 1.6C10.5455 0.716344 9.7857 0 8.84849 0Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  d="M11 8C11 9.65685 9.65685 11 8 11C6.34315 11 5 9.65685 5 8C5 6.34315 6.34315 5 8 5C9.65685 5 11 6.34315 11 8Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="signin.html">
                          <span class="item-ico">
                            <svg
                                width="14"
                                height="18"
                                viewBox="0 0 14 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <ellipse
                                  cx="7"
                                  cy="14"
                                  rx="7"
                                  ry="4"
                                  class="path-1"
                                  fill="#747474"
                              />
                              <circle
                                  cx="7"
                                  cy="4"
                                  r="4"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="signup.html">
                          <span class="item-ico">
                            <svg
                                width="14"
                                height="18"
                                viewBox="0 0 14 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <ellipse
                                  cx="7"
                                  cy="14"
                                  rx="7"
                                  ry="4"
                                  class="path-1"
                                  fill="#747474"
                              />
                              <circle
                                  cx="7"
                                  cy="4"
                                  r="4"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                            </svg>
                          </span>
                </a>
              </li>
              <li class="item px-[43px] py-[11px]">
                <a href="#">
                          <span class="item-ico">
                            <svg
                                width="21"
                                height="18"
                                viewBox="0 0 21 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M17.1464 10.4394C16.8536 10.7323 16.8536 11.2072 17.1464 11.5001C17.4393 11.7929 17.9142 11.7929 18.2071 11.5001L19.5 10.2072C20.1834 9.52375 20.1834 8.41571 19.5 7.73229L18.2071 6.4394C17.9142 6.1465 17.4393 6.1465 17.1464 6.4394C16.8536 6.73229 16.8536 7.20716 17.1464 7.50006L17.8661 8.21973H11.75C11.3358 8.21973 11 8.55551 11 8.96973C11 9.38394 11.3358 9.71973 11.75 9.71973H17.8661L17.1464 10.4394Z"
                                  fill="#ffa500"
                                  class="path-2"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M4.75 17.75H12C14.6234 17.75 16.75 15.6234 16.75 13C16.75 12.5858 16.4142 12.25 16 12.25C15.5858 12.25 15.25 12.5858 15.25 13C15.25 14.7949 13.7949 16.25 12 16.25H8.21412C7.34758 17.1733 6.11614 17.75 4.75 17.75ZM8.21412 1.75H12C13.7949 1.75 15.25 3.20507 15.25 5C15.25 5.41421 15.5858 5.75 16 5.75C16.4142 5.75 16.75 5.41421 16.75 5C16.75 2.37665 14.6234 0.25 12 0.25H4.75C6.11614 0.25 7.34758 0.82673 8.21412 1.75Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M0 5C0 2.37665 2.12665 0.25 4.75 0.25C7.37335 0.25 9.5 2.37665 9.5 5V13C9.5 15.6234 7.37335 17.75 4.75 17.75C2.12665 17.75 0 15.6234 0 13V5Z"
                                  fill="#747474"
                                  class="path-1"
                              />
                            </svg>
                          </span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</aside>


