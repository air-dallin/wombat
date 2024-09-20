<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory, Locale,Order;


    protected $guarded = [];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;

    public static function getStatusLabel($status){

        switch($status){
            case 0:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FDF9E9] px-4 py-1.5 text-sm font-semibold leading-[22px] text-warning-300 dark:bg-darkblack-500">'.__('main.draft') .'</label></div>';
            case 1:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FAEFEE] px-4 py-1.5 text-sm font-semibold leading-[22px] text-[#FF4747] dark:bg-darkblack-500">'.__('main.active') .'</label></div>';
            default:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FDF9E9] px-4 py-1.5 text-sm font-semibold leading-[22px] text-warning-300 dark:bg-darkblack-500">'.__('main.unknown') .'</label></div>';
        }
    }

}
