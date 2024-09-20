<?php

namespace App\Helpers;

use DateTime;

class UtilsHelper{

    public static function getMonthLabel($month){
        $dateObj   = DateTime::createFromFormat('!m', $month);
        return  __('main.'.  $dateObj->format('F'));
    }

    public static function getFilter($type,$value=null){
        switch ($type){
            case 'year':
                $result = 'from='.date($value .'-01-01') . '&to='.date($value .'-12-31');
                break;
            case 'quarter':
                $m1 = ($value-1)*3+1;
                $m1 = self::addZero($m1);
                $result = 'from='.date('Y-'.$m1.'-01') . '&to='.date('Y-m-d',strtotime(date('Y-'.$m1.'-01') . ' +3 month -1 day'));
                break;
            case 'month':
                $value = self::addZero($value);
                $result = 'from='.date('Y-'.$value.'-01') . '&to='.date('Y-m-d',strtotime(date('Y-'.$value.'-01') . ' +1 month -1 day'));
                break;
            case 'day':
                $value = self::addZero($value);
                $result = 'from='.date('Y-m-'.$value) . '&to='.date('Y-m-'.$value);
                break;
            default:
                $result = '';
        }
        return $result;
    }

    public static function addZero($value){
        return $value<10 ?'0'.$value:$value;
    }

}
