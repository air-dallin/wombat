<?php

namespace App\Models;

use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenaiChatItems extends Model
{
    use HasFactory,Order;
    protected $guarded = [];

}
