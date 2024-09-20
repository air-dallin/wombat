<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return redirect()->back();

        $news = [] ;// Article::with(['image'=>function($q){$q->where(['type'=>'news']);}])->where(['type'=>Article::TYPE_NEWS])->order()->paginate(15);


        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // отобразить форму для создания
        return redirect()->back();

        return view('admin.news.form');


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /*$validator = $request->validate(
            [
                //'title_en' => 'required',
                'title_uz' => 'required',
                //'title_ru' => 'required',
               // 'text_en' => 'required',
                'text_uz' => 'required'
                //'text_ru' => 'required',
                //'slug' => 'required|unique'
            ]
        );

        if ($validator->fails()) {
            return redirect()->route('admin.news.index', app()->getLocale())
                //->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors->all());
                ;
        } */

        $params = $request->all();
        unset($params['image']);

        $params['slug'] = Str::slug($params['title_uz']);
        //$params['category_id'] = 14;
        $params['user_id'] = Auth::id();
        $params['type']= Article::TYPE_NEWS;
        $params['category_id'] = 14;

        $slug = Str::slug($params['title_uz']);

        if ( Article::where(['slug' => $slug])->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }


        if($news = Article::create($params)) {

            if ($request->has('image')) {
                foreach ($request->file('image') as $index => $item) {
                    Image::add($item, 'news', $news->id);
                }
            }

            return redirect()->route('admin.news.index', app()->getLocale())->with('success', 'Article create success');
        }

        return redirect()->route('admin.news.index', app()->getLocale())
            //->withErrors($validator)
            ->withInput()
            ->with('error', $news->errors->all());
        ;


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Article $news
     * @return \Illuminate\Http\Response
     */
    public function show(Article $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Article $news
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $news)
    {
        return view('admin.news.form', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Article $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $news)
    {

        $errors = [];
        $validator = Validator::make($request->all(), // $request->validate(
            [
              //  'title_en' => 'required',
                'title_uz' => 'required',
              //  'title_ru' => 'required',
               // 'text_en' => 'required',
                'text_uz' => 'required',
              //  'text_ru' => 'required',
            ]
        );

        $params = $request->all();
        unset($params['image']);

        $slug = Str::slug($params['title_uz']);
        if($news->slug!=$slug) {
            if ( Article::where(['slug'=>$slug])->where('id','!=',$news->id)->first()) {
                $referer =  $request->headers->get('referer');
                return redirect()->to($referer)
                    ->withInput()
                    ->with('error', __('main.slug_already_exist'));
            }else{
                $params['slug'] = $slug;
            }
        }

        if ( $validator->fails() ) {
            $errors[] = $validator->errors();
        }

        if($errors) {
            //dd($errors);
            return redirect()->to(app()->getLocale() . '/admin/news/edit/' . $news->slug)
                //->withErrors($validator)
                ->withInput()
                ->with('error', $errors);;
        }

        $news->update($params);

        if ($request->has('image')) {
            foreach ($request->file('image') as $index => $item) {
                Image::add($item, 'news', $news->id);
            }
        }

        return redirect()->route('admin.news.index', app()->getLocale())->with('success', 'Update success');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Article $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $news)
    {
/*
        $news->status = 0;
        $news->save();*/
        $news->delete();
        return redirect()->route('admin.news.index', app()->getLocale());
    }
}
