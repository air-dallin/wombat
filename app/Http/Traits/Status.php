<?php


namespace App\Http\Traits;


trait Status
{

    public function getStatusLabel($status = false){

        if(!$status) $status = $this->status;

        switch($status){
            case 0:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FAEFEE] px-4 py-1.5 text-sm font-semibold leading-[22px] text-[#FF4747] dark:bg-darkblack-500">'.__('main.inactive') .'</label></div>';
            case 1:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-success-50 px-4 py-1.5 text-sm font-semibold leading-[22px] text-success-400 dark:bg-darkblack-500">'.__('main.active') .'</label></div>';
            case 3:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-success-50 px-4 py-1.5 text-sm font-semibold leading-[22px] text-success-400 dark:bg-darkblack-500">'.__('main.signed') .'</label></div>';
            /*case 2:
                return '<label class="badge badge-info bg-info">'.__('main.in_process') .'</label>';
            case 3:
                return '<label class="badge badge-warning bg-warning">'.__('main.assign') .'</label>';*/
            case 9:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FDF9E9] px-4 py-1.5 text-sm font-semibold leading-[22px] text-warning-300 dark:bg-darkblack-500">'.__('main.deleted') .'</label></div>';
        }
    }

    // user status
    public function getUserStatusLabel($status = false){

        if(!$status) $status = $this->status;

        switch($status){
            case 0:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FAEFEE] px-4 py-1.5 text-sm font-semibold leading-[22px] text-[#FF4747] dark:bg-darkblack-500">'.__('main.inactive') .'</label></div>';
            case 1:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-success-50 px-4 py-1.5 text-sm font-semibold leading-[22px] text-success-400 dark:bg-darkblack-500">'.__('main.active') .'</label></div>';
            case 3:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-success-50 px-4 py-1.5 text-sm font-semibold leading-[22px] text-success-400 dark:bg-darkblack-500">'.__('main.signed') .'</label></div>';

            case 9:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FDF9E9] px-4 py-1.5 text-sm font-semibold leading-[22px] text-warning-300 dark:bg-darkblack-500">'.__('main.deleted') .'</label></div>';
            default:
                return '<div class="flex w-full items-center"><label class="block rounded-md bg-[#FDF9E9] px-4 py-1.5 text-sm font-semibold leading-[22px] text-warning-300 dark:bg-darkblack-500">'.__('main.unknown') .'</label></div>';
        }
    }

}
