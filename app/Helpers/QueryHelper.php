<?php


namespace App\Helpers;


use Illuminate\Http\Request;

class QueryHelper
{

    public static function getDirectionLabel($field){
        $sort = request()->sort;
        $sort = explode('-',$sort);
        if(count($sort)==1) {
            $direction = '<i class="fas fa-long-arrow-alt-up"></i>';
            $sort = $sort[0];
        }else{
            $direction = '<i class="fas fa-long-arrow-alt-down"></i>';
            $sort = $sort[1];
        }
        if ($field == $sort) {
            return $direction ;
        }
        return '<i class="fas fa-exchange-alt" style="transform: rotate(90deg); opacity: .7"></i>';
    }

    public static function getSort(Request $request){
        if($request->has('sort')){
            $column = explode('-',$request->sort);
            if(count($column)==1) {
                $column = $column[0];
                $direction = 'ASC';
            }else{
                $column = $column[1];
                $direction = 'DESC';
            }
        }else{
            $column = 'date';
            $direction = 'DESC';
        }
        return ['column'=>$column,'direction'=>$direction];
    }
    public static function getSearchQuery(){
        return  request()->has('q') ? request()->get('q') :'';
    }
    // при поиске
    public static function fixSearchQuery(&$request){
        $ajax_search = false;
        if($request->has('ajax_search')) {
            $request->instance()->query->remove('_token');
            $request->instance()->query->remove('ajax_search');
            $ajax_search = true;
        }
        return $ajax_search;
    }
    public static function getUrl(){
        $url = explode('?',request()->url());
        return $url[0];
    }

}
