<?php

namespace App\Models;

use App\Http\Traits\Filters;
use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory, Locale,Order,Filters;

    protected $guarded = [];

    private $typeList = ['А','П','АП','КА','КП'];

    public function getType(){
        return isset($this->typeList[$this->type]) ? $this->typeList[$this->type] : __('main.not_set');
    }

}
