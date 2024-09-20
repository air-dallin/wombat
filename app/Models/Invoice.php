<?php

namespace App\Models;

use App\Http\Traits\ByCompany;
use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, Locale,Order,ByCompany;


    protected $guarded = [];



}
