<?php

namespace App\Helpers;

class ObjectHelper{

    public static function getIds($object,$field='id'){
       $ids = [];
        if(empty($object)) return [];
        foreach ($object as $k=>$obj) {
            $ids[] = $obj[$field];
       }
        return $ids;
    }

    public static function getSelectList($object,$label){

        if(empty($object)) return '';

        $list = [];
        foreach ($object as $item){
            $selected = get_class($item)=='App\Models\CompanyInvoice' && $item->is_main ?'selected':'';
            $list[] = "<option value='{$item->id}' {$selected}>{$item->$label}</option>";
        }

        return $list;
    }

}
