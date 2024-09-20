<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldCategory extends Model
{
    use HasFactory, Locale, Order;

    protected $guarded = [];

    public static $locale = 'uz';
    public static $title = 'title_uz';

    const CATEGORY_PUBLICATION = 15;
    const CATEGORY_NEWS = 14;
    const CATEGORY_KNOWLEDGE = 16;


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::$title = 'title_' . app()->getLocale();
        self::$locale = app()->getLocale();

    }

    public function parent(){
        return $this->belongsTo(self::class);
    }
    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('childs');
    }

    public function childs(){
        return $this->hasMany(self::class,'parent_id','id');
    }

    public function image(){
        return $this->hasOne(Image::class,'object_id','id')->where('type','categories');;
    }

    public static function getSelectMenu($categories,$active_id=null){

        $tree = self::array_to_tree($categories);
        return self::out_options($tree,$active_id);
    }

    public static function getBreadcrumbs($categories,$active_id,$isArticle = false){
        $bc = self::breadcrumb($categories,$active_id);
        return self::getBrc($bc,$active_id,$isArticle);
    }

    public static function getLink($categories,$active_id){ //},$isArticle = false){
        $data = self::breadcrumb($categories,$active_id);
        if(empty($data)){
            return '/';
        }
       return self::getUrl($data);
    }

    public static function getListMenu($categories,$active_id=0){

        $cat = [];

        foreach($categories as $item){
            $cat[$item['id']] = $item;
        }

        $cat = self::get_cat($cat);

        ob_start();
        self::view_cat($cat,$active_id);
        $listMenu = ob_get_contents();
        ob_end_clean();

        return $listMenu;

    }

    public static function array_to_tree($array, $sub = 0)
    {
        $a = array();
        foreach($array as $v) {
            if($sub == $v['parent_id']) {
                $b = self::array_to_tree($array, $v['id']);
                if(!empty($b)) {
                    $a[$v['id']] = $v;
                    $a[$v['id']]['children'] = $b;
                } else {
                    $a[$v['id']] = $v;
                }
            }
        }
        return $a;
    }

    public static function out_options($array, $selected_id = 0, $level = 0)
    {
        $level++;
        $out = '';
        foreach ($array as $i => $row) {
            $out .= '<option value="' . $row['id'] . '"';
            if ($row['id'] == $selected_id) {
                $out .= ' selected';
            }
            $out .= '>';

            if ($level > 1) {
                $out .= str_repeat('●', $level - 1) . ' '; //&emsp;
            }

            $out .= $row[self::$title] . '</option>';

            if (!empty($row['children'])) {
                $out .= self::out_options($row['children'], $selected_id, $level);
            }
        }
        return $out;
    }


    public static function get_cat($arr){
        //Функция получения массива каталога

            $arr_cat = array();

            foreach($arr as $row){
                //Формируем массив, где ключами являются адишники на родительские категории
                if(empty($arr_cat[$row['parent_id']])) {
                    $arr_cat[$row['parent_id']] = array();
                }
                $arr_cat[$row['parent_id']][] = $row;
            }
            //возвращаем массив
            return $arr_cat;


    }

    public static function view_cat(&$arr,$parent_id = 0) {

        //Условия выхода из рекурсии
        if(empty($arr[$parent_id])) {
            return;
        }

        echo '<ul class="crops-list__all">';
        echo '<ul style="padding-left:10px;">';
        echo '<ul class="crops-list__all">';
        //перебираем в цикле массив и выводим на экран
        for($i = 0; $i < count($arr[$parent_id]); $i++) {

            if(!isset($arr[$parent_id][$i])) break;

            echo '<li class="crops-list"><a href="/'.self::$locale.'/articles/'.$arr[$parent_id][$i]['slug'].
                '?parent_id='.$parent_id.'">'
                .$arr[$parent_id][$i][self::$title].'</a>';
            //рекурсия - проверяем нет ли дочерних категорий
            self::view_cat($arr,$arr[$parent_id][$i]['id']);
            echo '</li>';
        }
        echo '</ul>';
    }

    /**
     * @param $cat array
     * @param $id int
     * @return array
     * Получаем массив для хлебных крошек
     */
    public static function breadcrumb(&$cat, $id){
        if(!intval($id)) return false;
        $brc = [];

        foreach($cat as $item){
            $cat[$item['id']] = $item;
        }

        for($i = 0; $i < count($cat); $i++){
            //Проверяем что мы не нашли родителя и массив не пуст
            if( $id != 0 && !empty($cat[$id])){
                // Ищем родителя
                $brc[$cat[$id]['id']] = [
                    'id' => $cat[$id]['id'],
                    'slug'=>$cat[$id]['slug'],
                    'title'=>$cat[$id][self::$title],
                ];
                $id = $cat[$id]['parent_id'];
            }else{ // Останавливаем цикл
                break;
            }
        }

        return array_reverse($brc, true);
    }
    /**
     * @param $data array
     * @return string
     * Выводим хлебные крошки
     */
    public static function getBrc($data,$active_id,$isArticle){

        //Проверяем что массив не пуст
        if(empty($data)){
            return false;
        }else {
            $brc_menu = '';
            $count = count($data)-1;
            $k=0;
            //Перебераем массив для построения хлебных крошек
            foreach ($data as $id => $item) {
                $active = $active_id==$id ? 'active' : '';
                if($k==$count && !$isArticle){
                    $brc_menu .= '<li class="breadcrumb-item ' . $active . '" aria-current="page">' . $item['title'] . '</li> ';
                }else {
                    $brc_menu .= '<li class="breadcrumb-item ' . $active . '" aria-current="page"><a href="/' . app()->getLocale() . '/categories/' . $item['slug'] . '">' . $item['title'] . '</a></li> ';
                }
                $k++;
            }

            //удаляем ссылку на последний элемент в крошках
            return preg_replace('#(.+)?<a.+>(.+)</a>$#', '$1$2', $brc_menu);
        }
    }

    // получаем url
    public static function getUrl($data){

        //Проверяем что массив не пуст
        if(empty($data)){
            return false;
        }else {
            $items = [];
            foreach ($data as $id => $item) {
                $items[] = $item['slug'];
            }
            $brc_menu =  app()->getLocale() . '/categories/' . implode('/', $items);
            return $brc_menu;
        }
    }


    // sidebarmenu
    public static function getMegamenu(&$categories){

        $menu = [];

        $lang = app()->getLocale();

        foreach($categories as $category) { // crops livestock hub ...

            if(in_array($category->slug,['news','publications','knowledge'])) continue;

            if($category->childs) { // crops / service-provider

                $menu[] = "<li class='first'><a href='/{$lang}/categories/{$category->slug}/'>{$category->getTitle()}</a>
                            <ul class='megamenu'><div class='container'>
                                <h3  class='second'><a href='/{$lang}/categories/{$category->slug}/'>{$category->getTitle()}</a></h3>";

                foreach ($category->childs as $i=>$child) { // crops / service-provider /  universities

                    $menu[] = "<li><a href='/{$lang}/categories/{$child->slug}/'><strong>{$child->getTitle()}</strong></a>";
                    if($child->childs){
                        foreach ($child->childs as $childs) {
                            // список фермеров и университетов
                            if(in_array($childs->slug,['farmers','universities'])) {
                                $menu[] = "<a href='/{$lang}/users/{$childs->slug}'>{$childs->getTitle()}</a>";
                            }else {
                                $menu[] = "<a href='/{$lang}/categories/{$childs->slug}/'>{$childs->getTitle()}</a>";
                            }
                        }

                    }else {
                        if($i==0) $menu[] = "<h3 class='inner'><a href='/{$lang}/categories/{$category->slug}/'>{$child->getTitle()}</a></h3>";
                    }
                    $menu[] = '</li>';
                }
                $menu[] = '</li></div></ul></li>';
            }

        }
        $menu[] = "<li class='first'><a href='/{$lang}/knowledge'>".__('main.knowledge')."</a></li>";
        $menu[] = "<li class='first'><a href='/{$lang}/trainings'>".__('main.trainings')."</a></li>";


        return implode("\n",$menu);
    }




}
