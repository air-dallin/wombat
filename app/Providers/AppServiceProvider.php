<?php

namespace App\Providers;

use App\Helpers\MenuHelper;
use App\Models\Article;
use App\Models\Movements;
use App\Models\OldCategory;
use App\Models\Contact;
use App\Models\Module;
use App\Models\News;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        URL::forceRootUrl(env('APP_URL'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); // for mariadb<10.2.2 & mysql<5.7.7
        Paginator::useTailwind();

        view()->composer(
            ['frontend.profile.sections.sidebar','frontend.profile.modules.*'],
            function ($view)  {
                $user = User::where(['users.id'=>Auth::id()])->with([
                    'tarif',
                    'tarifs.modules' // нужны все модули тарифа
                    ])->first();

                $tarif = isset($user->tarif[0]) ? $user->tarif[0] : false;

                $tarifs = Tarif::with(['modules'=>function($q){ return $q->orderBy('pos');}])->where(['id'=>4])->first();

                $view->with(compact('user','tarif','tarifs'));
            }
        );

        view()->composer(
            ['frontend.profile.modules.*'],
            function ($view)  {
                MenuHelper::init();
                $controller = MenuHelper::getControllerName();
                $action = MenuHelper::getActionName();
                $view->with(compact('controller','action'));
            }
        );




        view()->composer(
            'frontend.sections.news',
            function ($view) {
                $lastNews = [];// Article::where(['type'=>Article::TYPE_NEWS,'category_id'=>OldCategory::CATEGORY_NEWS])->limit(3)->latest()->get();
                $view->with(compact('lastNews'));
            }
        );
        view()->composer(
            'frontend.sections.contacts',
            function ($view) {
                $contacts = [];
                $view->with(compact('contacts'));
            }
        );

        view()->composer(
            'frontend.sections.header',
            function ($view) {
                $categories = [];
                $megamenu   ='';
                $view->with(compact('megamenu', 'categories'));
            }
        );
        view()->composer(
            'layouts.profile',
            function ($view) {
                if(Cache::has('movements')) {
                    $movements = Cache::get('movements');
                }else{
                    $movements = Movements::where(['status' => 1])->get();
                    Cache::put('movements',$movements,600);
                }
                $view->with(compact('movements'));
            }
        );

    }
}
