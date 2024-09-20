<?php
namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyAccounting extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

}
