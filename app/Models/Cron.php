<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cron extends Model
{
    use HasFactory;
    protected $guarded = [];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PROCESS = 2;
    const STATUS_ERROR = 3;


}
