<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    // protected $guarded = [];

    protected $fillable = [
        'user_id','region_id','city_id','rating','rating_count','address','gender','firstname','lastname','middlename','position','phone','bithdate','company','degree'
    ];

    public function city(){
        return $this->hasOne(City::class,'id','city_id');
    }
    public function region(){
        return $this->hasOne(Region::class,'id','region_id');
    }

    public function getFullName(){
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getFio(){
        return $this->middlename . ' ' . mb_strtoupper(mb_substr($this->firstname,1,1) . '.' . mb_substr($this->lastname,1,1) .'.');
    }

    public function getActivity(){
        $activities = [1=>__('main.crops'),2=>__('main.livestocks'),3=>__('main.crops_livestocks')];
        return isset($activities[$this->activity]) ? $activities[$this->activity] : 'not set';
    }

}
