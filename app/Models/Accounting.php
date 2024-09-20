<?php
namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use HasFactory,Locale, Order;

    protected $guarded = [];


    public function debit(){
        return $this->hasOne(Plan::class,'code','debit_account');
    }
    public function credit(){
        return $this->hasOne(Plan::class,'code','credit_account');
    }

    public function getPlan(){

        return !empty($this->debet) ?  $this->debet->code .', '. $this->debet->title_ru : (!empty($this->credit) ?   $this->credit->code .', '.$this->credit->title_ru:'no');
    }
    public function getPlanLink($amount,$type){
        return '<a href="#">'.$amount.'</a>';
    }


}
