<?php


namespace App\Http\Traits;


use Illuminate\Support\Facades\Storage;

trait Images
{
    public static function getImage($image){

        if(isset($image)){
            $image = Storage::url($image->image);
        }else{
            $image = '/frontend/images/default.png';
        }

        return $image;

    }

    public static function getSmallImage($image){

        if($image){
            $image = Storage::url( $image->small() );
        }else{
            $image = '/frontend/images/default.png';
        }

        return $image;

    }

}
