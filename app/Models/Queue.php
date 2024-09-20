<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    const STATUS_WAIT = 0;
    const STATUS_COMPLETE = 1;
    const STATUS_PROCESS = 2;
    const STATUS_ERROR = 3;

    protected $guarded = [];

    const TYPE_INCOMING = 0;
    const TYPE_OUTGOING = 1;


    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
}
