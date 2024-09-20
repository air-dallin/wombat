<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifModule extends Model
{
    use HasFactory, Locale,Order;


    protected $guarded = [];

    public function modules(){
        return $this->hasMany(Module::class,'id','module_id');
    }

}
