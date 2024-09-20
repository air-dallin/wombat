<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Auth;

class MenuHelper
{

    public static $route;
    public static $controller;
    public static $action;
    public static $params;


    public static function init(){
        self::$params = request()->all();
        self::$route = strtolower(request()->route()->getActionName());
        $route      = explode('@', request()->route()->getActionName());
        $controller = explode('\\', $route[0]);
        self::$controller = strtolower(str_replace('Controller', '', end($controller)));
        self::$action = $route[1];

    }

    public static function getControllerName()
    {
        return self::$controller;
    }
    public static function getActionName()
    {
        return self::$action;
    }
    public static function check($a,$b,$class='active'){
        return $a==$b ? $class :'';
    }
    public static function checkSubMenu($a,$b,$class='active'){
        return in_array($a,$b) ? $class :'';
    }
    public static function routeHas($item,$class='active'){
        return strpos(self::$route,$item) ? $class : '';
    }
    public static function can($roles){
        return in_array(Auth::user()->role,$roles) ? true : false;
    }

    public static function routeParam($param,$value,$class='active'){

        $param = isset(self::$params[$param]) ? self::$params[$param] : null;

        if(empty($param)) return '';

        if(is_array($value)){
            $result = in_array(self::$params[$param],$value);
        }else{
            $result = self::$params[$param]==$value;
        }
        return $result ? $class:'';
    }

}
