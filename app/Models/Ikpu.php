<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ikpu extends Model
{
    use HasFactory, Locale,Order,Status,Statuses;

    public $table = 'ikpu';

    protected $guarded = [];

    public function nomenklature(){
        return $this->hasOne(Nomenklature::class,'ikpu_id','id');
    }

}
