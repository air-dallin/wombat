<?php

use App\Helpers\MenuHelper;

MenuHelper::init();
$controller = MenuHelper::getControllerName();
$action     = MenuHelper::getActionName();

?>
<div class="deznav">
  <div class="deznav-scroll">
    <ul class="metismenu" id="menu">
      <li><a class="ai-icon" href="{!! localeRoute('admin.index'); !!}" aria-expanded="false">
          <i class="fa fa-dashboard"></i>
          <span class="nav-text">{{__('main.dashboard')}}</span>
        </a>
      </li>


      <?php
      /*<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                   <i class="flaticon-"></i>
                   <span class="nav-text">Роли</span>
                 </a>
                             <ul aria-expanded="true">
                                <?php // <li><a href="{!! localeRoute('admin.role.create') !!}">{{__('main.add')}} </a></li> ?>
                                 <li><a href="{!! localeRoute('admin.role.index') !!}">Роли</a></li>
                             </ul>
                         </li> */ ?>
      <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-user"></i>
          <span class="nav-text">{{__('main.employers')}}</span>
        </a>
        <ul aria-expanded="true">
          <li><a href="{!! localeRoute('admin.user.create') !!}">{{__('main.add')}} </a></li>
          <?php
          /*<li><a href="{!! localeRoute('admin.user.index',['role'=>\App\Models\User::ROLE_DIRECTOR]) !!}">{{__('main.director')}}</a></li>*/ ?>
          <li><a href="{!! localeRoute('admin.user.index',['role'=>\App\Models\User::ROLE_MODERATOR]) !!}">{{__('main.moderator')}}</a></li>
        </ul>
      </li>
      {{--<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
              <i class="fa fa-user"></i>
              <span class="nav-text">{{__('main.users')}}</span>
          </a>
          <ul aria-expanded="true">
              --}}{{--<li><a href="{!! localeRoute('admin.company.index',['role'=>\App\Models\User::ROLE_COMPANY]) !!}">{{__('main.companies')}}</a></li>--}}{{--
          </ul>
      </li>--}}
      <li class="{{MenuHelper::check($controller,'user','mm-active')}}"><a href="{!! localeRoute('admin.user.index',['role'=>\App\Models\User::ROLE_CLIENT]) !!}"><i class="fa fa-user"></i>{{__('main.clients')}}</a></li>
      <li class="{{MenuHelper::check($controller,'company','mm-active')}}"><a href="{!! localeRoute('admin.company.index') !!}"><i class="fa fa-users"></i>{{__('main.companies')}}</a></li>

      <li class="{{MenuHelper::check($controller,'region','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-map"></i>
          <span class="nav-text">{{__('main.region')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.region.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.region.index') !!}">{{__('main.region')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'city','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-map-marker"></i>
          <span class="nav-text">{{__('main.cities')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.city.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.city.index') !!}">{{__('main.cities')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'district','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-map-marker"></i>
          <span class="nav-text">{{__('main.districts')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.district.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.district.index') !!}">{{__('main.districts')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'plan','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-sitemap"></i>
          <span class="nav-text">{{__('main.plan')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.plan.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.plan.index') !!}">{{__('main.plan')}}</a></li>
        </ul>
      </li>

      <li class="{{MenuHelper::check($controller,'ikpu','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-sitemap"></i>
          <span class="nav-text">{{__('main.ikpu')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.ikpu.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.ikpu.index') !!}">{{__('main.ikpu')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'category','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-sitemap"></i>
          <span class="nav-text">{{__('main.categories')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.category.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.category.index') !!}">{{__('main.categories')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'package','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-sitemap"></i>
          <span class="nav-text">{{__('main.packages')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.package.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.package.index') !!}">{{__('main.packages')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'unit','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-sitemap"></i>
          <span class="nav-text">{{__('main.units')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.unit.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.unit.index') !!}">{{__('main.units')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'nds','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-sitemap"></i>
          <span class="nav-text">{{__('main.nds')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.nds.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.nds.index') !!}">{{__('main.nds')}}</a></li>
        </ul>
      </li>
      {{--<li class="{{MenuHelper::check($controller,'nomenklature','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
              <i class="fa fa-sitemap"></i>
              <span class="nav-text">{{__('main.nomenklatures')}}</span>
          </a>
          <ul aria-expanded="false">
              <li><a href="{!! localeRoute('admin.nomenklature.create') !!}">{{__('main.add')}} </a></li>
              <li><a href="{!! localeRoute('admin.nomenklature.index') !!}">{{__('main.nomenklatures')}}</a></li>
          </ul>
      </li> --}}
      <li class="{{MenuHelper::check($controller,'nomenklature','mm-active')}}"><a href="{!! localeRoute('admin.nomenklature.index') !!}"><i class="fa fa-book"></i> {{__('main.nomenklatures')}}</a></li>

      <li class="{{MenuHelper::routeHas('modules','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-id-card"></i>
          <span class="nav-text">{{__('main.clients_data')}}</span>
        </a>
        <ul aria-expanded="false">
          <li class="{{MenuHelper::check($controller.'.'.$action,'product.index','mm-active')}}"><a class="{{MenuHelper::check($controller.'.'.$action,'product.index','mm-active')}}" href="{!! localeRoute('admin.modules.product.index') !!}">{{__('main.purchase_invoices')}}</a></li>
          <li class="{{MenuHelper::check($controller,'contract','mm-active')}}"><a class="{{MenuHelper::check($controller,'contract','mm-active')}}" href="{!! localeRoute('admin.modules.contract.index') !!}">{{__('main.contracts')}}</a></li>
          <li class="{{MenuHelper::check($controller,'paymentorder','mm-active')}}"><a class="{{MenuHelper::check($controller,'paymentorder','mm-active')}}" href="{!! localeRoute('admin.modules.payment_order.index') !!}">{{__('main.payment_orders')}}</a></li>
          <li class="{{MenuHelper::check($controller,'incomingorder','mm-active')}}"><a class="{{MenuHelper::check($controller,'incomingorder','mm-active')}}" href="{!! localeRoute('admin.modules.incoming_order.index') !!}">{{__('main.incoming_orders')}}</a></li>
          <li class="{{MenuHelper::check($controller,'expenseorder','mm-active')}}"><a class="{{MenuHelper::check($controller,'expenseorder','mm-active')}}" href="{!! localeRoute('admin.modules.expense_order.index') !!}">{{__('main.expense_orders')}}</a></li>
          {{--<li class="{{MenuHelper::check($controller.'.'.$action,'product.remainds','mm-active')}}"><a class="{{MenuHelper::check($controller.'.'.$action,'product.remainds','mm-active')}}" href="{!! localeRoute('admin.modules.product.remains') !!}">{{__('main.remains')}}</a></li>
          <li class="{{MenuHelper::check($controller,'product.receipts','mm-active')}}"><a class="{{MenuHelper::check($controller,'product.receipts','mm-active')}}" href="{!! localeRoute('admin.modules.product.receipts') !!}">{{__('main.product_receipt')}}</a></li>
          <li class="{{MenuHelper::check($controller.'.'.$action,'product.sales','mm-active')}}"><a class="{{MenuHelper::check($controller.'.'.$action,'product.sales','mm-active')}}" href="{!! localeRoute('admin.modules.product.sales') !!}">{{__('main.product_sales')}}</a></li>--}}
        </ul>
      </li>

      <li class="{{MenuHelper::check($controller,'tarif','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-tasks"></i>
          <span class="nav-text">{{__('main.tarifs')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.tarif.create') !!}">{{__('main.add')}}</a></li>
          <li><a href="{!! localeRoute('admin.tarif.index') !!}">{{__('main.tarifs')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'payment_system','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-bank"></i>
          <span class="nav-text">{{__('main.payment_systems')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.payment_system.create') !!}">{{__('main.add')}}</a></li>
          <li><a href="{!! localeRoute('admin.payment_system.index') !!}">{{__('main.payment_systems')}}</a></li>
        </ul>
      </li>
      <li class="{{MenuHelper::check($controller,'payment','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-money"></i>
          <span class="nav-text">{{__('main.payments')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.payment.index') !!}">{{__('main.payments')}}</a></li>
        </ul>
      </li>

      <li class="{{MenuHelper::check($controller,'news','mm-active')}}"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
          <i class="fa fa-newspaper-o"></i>
          <span class="nav-text">{{__('main.news')}}</span>
        </a>
        <ul aria-expanded="false">
          <li><a href="{!! localeRoute('admin.news.create') !!}">{{__('main.add')}} </a></li>
          <li><a href="{!! localeRoute('admin.news.index') !!}">{{__('main.news')}}</a></li>
        </ul>
      </li>


    </ul>

  </div>
</div>
