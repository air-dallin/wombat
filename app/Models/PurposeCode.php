<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurposeCode extends Model
{
    use HasFactory, Locale,Order;


    protected $guarded = [];

}
