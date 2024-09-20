<?php

namespace App\Models;

use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory, Order;

    public function user(){
        return $this->hasOne(User::class,'id','user_to');
    }

    public function userFrom(){
        return $this->hasOne(User::class,'id','user_from');
    }
    public function userTo(){
        return $this->hasOne(User::class,'id','user_to');
    }


    public function getStars(){
        $count = $this->rating_count;
        $stars = [];
        for($i=1; $i<=5; $i++){
            if($i<=$count){
                $stars[] = '<span>&starf;</span>';
            }else{
                $stars[] = '<span></span>';
            }
        }

        return '<div class="stars">' .implode('',$stars) .'</div>';

    }

    public function getStarsReviews(){


        $count = $this->rating_count;

        $stars = [];
        for($i=1; $i<=5; $i++){
            if($i<=$count){
                $stars[] = '<i class="fas fa-star checked"></i>';
            }else{
                $stars[] = '<i class="far fa-star"></i>';
            }
        }

        return implode('',$stars);

    }



    public static function getCount($review){

        $count = 0;
        foreach($review as $item){
            $count+=$item->cnt;
        }

        return $count;

    }
    public static function getAverage($reviews){

        $sum=0;
        $count = 0;
        foreach($reviews as $item){
            $count+=$item->cnt;
            $sum+=$item->ratio;
        }

        return  number_format($sum/$count,'1','.','');

    }

    public static function getPercent($review){

        return rand(1,5);

    }



}
