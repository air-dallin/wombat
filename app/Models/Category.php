<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Locale,Order;


    protected $guarded = [];

    const STATUS_INACTIVE= 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 9;


}
