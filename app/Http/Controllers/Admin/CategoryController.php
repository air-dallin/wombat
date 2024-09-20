<?php

namespace App\Http\Controllers\Admin;

use App\Models\Status;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('status','<>',Status::STATUS_DELETED)->order()->paginate(15);

        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.form');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate(
            [
               // 'title_en' => 'required',
               // 'title_uz' => 'required',
                'title_ru' => 'required',
            ],
           /* [
                'text_en' => 'required',
                'text_uz' => 'required',
                'text_ru' => 'required',
            ]*/
        );

        $params = $request->all();

        $slug = Str::slug($params['title_uz']);

        unset($params['image']);

        if ( Category::where(['slug' => $slug])->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }

        //if(is_null($params['parent_id'])) $params['parent_id'] = 0;

        $category = Category::create($params);
       /* if ($request->has('image')) {
            Image::add($request->file('image'), 'categories', $category->id);
        }*/

        return redirect()->route('admin.category.index', app()->getLocale())->with('success', 'Operation successfull completed!');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {

        return view('admin.category.form', compact('category'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category     $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {

        $params = $request->all();

        $slug = Str::slug($params['title_uz']);
        if ( Category::where(['slug'=>$slug])->where('id','!=',$category->id)->first()) {
            $referer =  $request->headers->get('referer');
            return redirect()->to($referer)
                ->withInput()
                ->with('error', __('main.slug_already_exist'));
        }else{
            $params['slug'] = $slug;
        }
        $category->update($params);

        return redirect()->route('admin.category.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        $category->status = Status::STATUS_DELETED;
        $category->save();
        return redirect()->route('admin.category.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }
}
