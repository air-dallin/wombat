<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Password;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DibankOption extends Model
{
    use HasFactory, Locale, Order,Status,MyCompany,Statuses,Password;
    protected $guarded = [];

    const STATUS_DRAFT = 0;
    const STATUS_READY = 1;
    const STATUS_SENT = 2;
    const STATUS_CONFIRMED = 3;
    const STATUS_COMPLATED = 4;
    const STATUS_REJECTED = 5;
    const STATUS_WAIT = 6;
    const STATUS_EXPIRED = 7;
    const STATUS_RECEIVED = 8;
    const STATUS_READY_DELETE = -1;
    const STATUS_DELETED = -2;
    const STATUS_ERROR_TO_SENT = -3;
    const STATUS_UNKNOWN_ERROR = -5;

    public function company() {
        return $this->hasOne(Company::class,'id','company_id');
    }


}
