<?php
namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAccount extends Model
{
    use HasFactory,Order;

    protected $guarded = [];
    public $timestamps = false;

}
