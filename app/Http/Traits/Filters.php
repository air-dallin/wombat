<?php


namespace App\Http\Traits;


trait Filters
{

    public function scopeFilter($query,$options=null){

        if(!request()->has('q') ) return $query;

        $q = request()->q;

        if(request()->has('field')){
            $field = request()->field;
            $_fields = explode(',',$field);
            if(is_array($_fields)){
                foreach($_fields as $field) {
                    $query->orWhere($field, 'LIKE', "%{$q}%");
                }
                return $query;
            }
        }elseif(is_numeric($q)) {
            $field = $this->getFieldInn();
        }else{
            $field = $this->getFieldContragentCompanyName();
        }

        return $query->where($field,'LIKE',"%{$q}%");
    }

}
