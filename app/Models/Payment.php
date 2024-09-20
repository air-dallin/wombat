<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, Locale,Order;


    protected $guarded = [];

    public function tarif(){
        return $this->hasOne(Tarif::class,'id','tarif_id');
    }
    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }




}
