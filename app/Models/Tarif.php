<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory, Locale,Order;

    protected $guarded = [];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;


    public function modules(){
        return $this->belongsToMany(Module::class,'tarif_modules','tarif_id','module_id');
    }

    public function checkTarifIsActive(){
        return date('Y-m-d', time()) <= date('Y-m-d', strtotime($this->created_at .' +1 month'));
    }

}
