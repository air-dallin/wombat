<?php

namespace App\Models;

use App\Http\Traits\Images;
use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory, Locale, Order, Images;

    protected $guarded = [];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function Images(){
        return $this->hasMany(Image::class,'object_id')->where('type','news');;
    }

    public function Image(){
        return $this->hasOne(Image::class,'object_id')->where('type','news');;
    }


}
