<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Crop;
use App\Models\District;
use App\Models\Livestock;
use App\Models\UserType;
use App\Models\Article;
use App\Models\OldCategory;
use App\Models\City;
use App\Models\Contact;
use App\Models\Event;
use App\Models\News;
use App\Models\Region;
use App\Models\Reviews;
use App\Models\User;
use Butschster\Head\Facades\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class FrontController extends Controller
{
    use \App\Http\Traits\Meta;

    // сменить язык
    public function changeLang(Request $request)
    {
        $old_lang = app()->getLocale();
        if (in_array($request->lang, config('app.locales'))) {
            $locale = $request->lang;
            app()->setLocale($locale);
        } else {
            return redirect('/'.config('app.locales'));
        }

        $referer = explode('/', $request->headers->get('referer'));

        $referer = str_replace($old_lang, $locale, $referer);

        $referer[3] = $locale;

        return redirect()->to(implode('/', $referer));
    }

    public function getCities(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $title = 'title_'.app()->getLocale();
            if ($cities = City::where('region_id', $request->region_id)->orderBy('title_ru', 'ASC')->pluck($title, 'id')) {
                $_cities = [];
                foreach ($cities as $id => $city) {
                    $_cities[] = '<option value="'.$id.'">'.$city.'</option>';
                }

                return ['status' => true, 'data' => implode('\r', $_cities)];
            }
        }

        return ['status' => false];
    }
    public function getDistricts(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $title = 'title_'.app()->getLocale();
            if ($districts = District::where('city_id', $request->city_id)->orderBy('title_ru', 'ASC')->pluck($title, 'id')) {
                $_districts = [];
                foreach ($districts as $id => $district) {
                    $_districts[] = '<option value="'.$id.'">'.$district.'</option>';
                }

                return ['status' => true, 'data' => implode('\r', $_districts)];
            }
        }

        return ['status' => false];
    }
    /**
     * Show the application profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return redirect('/' .app()->getLocale() .'/login');

        //$categories = OldCategory::select('id', 'parent_id', 'title_ru')->get();

        $events = []; // Event::with(['image'=>function($q){$q->where(['type'=>'events']);}])->get();

        $news = [];// Article::with(['image'=>function($q){$q->where(['type'=>'news']);}])->where(['type' => Article::TYPE_NEWS])->get();

        $categories = []; //OldCategory::with(['image'=>function($q){$q->where(['type'=>'categories']);}])->where('parent_id', 0)->get();

        $reviews = [];// Reviews::with('user.image')->where(['status' => 1])->limit(4)->get();

        $this->getMeta(__('meta.home'), __('meta.home_description'), __('meta.home_keywords') );


        return view('frontend.index', compact('events', 'news', 'categories', 'reviews'));
    }


    public function contacts()
    {
        $contacts = null; // Contact::all();

        $this->getMeta(__('meta.contacts'), 'Современный онлайн-сервис для ведения бухгалтерии и учета. Разработанный специально для малого и среднего бизнеса, он обеспечивает удобный и быстрый доступ к бухгалтерии в любое время и из любой точки мира', ['contacts']);

        return view('frontend.contacts', compact('contacts'));
    }

    public function about()
    {
        Meta::prependTitle(__('meta.about'));
        return view('frontend.about');
    }

    public function policy()
    {
        return view('frontend.policy');
    }

    public function offer()
    {
        return view('frontend.offer');
    }




    // поиск по статьям Article - knowledge hub
    public function search(Request $request)
    {
        $searchStr = trim($request->q);

        if ($searchStr) {
            $fields = implode(',', ['title_'.app()->getLocale(), 'text_'.app()->getLocale()]);

            $searchArray = explode(' ', trim($searchStr));
            $_search = '';
            foreach ($searchArray as $item) {
                $_search .= $item.'* ';
            }
            $search  = $_search;
            $results = []; // News::with(['image'=>function($q){$q->whereIn('type',['articles','publications','knowledge','news']);}], 'category')->whereRaw("MATCH ($fields) AGAINST ('+{$search}' IN BOOLEAN MODE)")->paginate(10);
        } else {
            $searchStr   = '';
            $results     = '';
            $searchArray = [];
        }

        return view('frontend.search', compact('searchStr', 'results', 'searchArray'));
    }


}
