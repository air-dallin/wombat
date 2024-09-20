<?php


namespace App\Http\Traits;


trait Order
{

    public function scopeOrder($query,$options=null){

        $table = $this->getTable();

        // оптимизировать сортировку по user_from-to ,
        // добавить по region

        $sort = explode('-',request()->sort);

        if(count($sort)>1) {
            $sort = $sort[1];
            $direction = 'ASC';
        }else {
            $sort = $sort[0];
            $direction = 'DESC';
        }

        $field = $table.'.'.$sort;

        $query->select($table.'.*');


        if(in_array($sort,['user_from','user_to'])) {

            if($table!='users') {
                $query->join('users as u_from', 'u_from.id', '=', $table . '.user_from')
                    ->leftJoin('users as u_to', 'u_to.id', '=', $table.'.user_to')
                    ->leftJoin('user_infos as ui_from', 'ui_from.user_id', '=', 'u_from.id')
                    ->leftJoin('user_infos as ui_to', 'ui_to.user_id', '=', 'u_to.id')
                    ->select($table.'.*','u_from.id', /*'u_from.status',*/ 'u_from.email','u_from.phone','u_from.role','ui_from.user_id','ui_from.firstname','ui_from.lastname');

                if($sort=='user_from'){
                    $field = 'ui_from.firstname';
                }elseif($sort=='user_to'){
                    $field = 'ui_to.firstname';
                }

            }else{ // users
                $query->join('user_infos', 'user_infos.user_id', '=', $table . '.id');
                $field = 'user_infos.firstname';
            }

        }elseif($sort=='region'){
            if(isset($options['exclude']) && in_array('users_from',$options['exclude'])){
                $query->join('user_infos as ui', 'ui.user_id', '=', 'users.id')
                ->leftJoin('regions as r', 'r.id','=', 'ui.region_id');
                $field = 'r.title_en';
            }else {
                $query->join('users as u_from', 'u_from.id', '=', $table . '.user_from')
                    ->join('user_infos as ui_from', 'ui_from.user_id', '=', 'u_from.id')
                    ->leftJoin('regions as r', 'r.id', $table . '.region_id');
                $field = 'r.title_en';
            }
        }elseif($sort=='city'){
            if(isset($options['exclude']) && in_array('users_from',$options['exclude'])){
                $query->join('user_infos as ui', 'ui.user_id', '=', 'users.id')
                ->leftJoin('cities as c', 'c.id','=', 'ui.city_id');
                $field = 'c.title_en';
            }else {
                $query->join('users as u_from', 'u_from.id', '=', $table . '.user_from')
                    ->join('user_infos as ui_from', 'ui_from.user_id', '=', 'u_from.id')
                    ->leftJoin('cities as c', 'c.id', $table . '.city_id');
                $field = 'c.title_en';
            }

        }else {
            if(!empty($sort)){
                $field = $sort;
            }else {
                if(isset($options['field'])){
                    $field = $table . '.' . $options['field'];
                    if(isset($options['direction'])) $direction = $options['direction'];
                }else {
                    return $query;
                    // $field = $table . '.id';
                }
            }
        }
        return $query->orderBy($field,$direction);
    }

}
