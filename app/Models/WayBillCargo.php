<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class WayBillCargo
{

    use HasFactory;

    protected $guarded = [];

    public function items(){
        return $this->hasOne(WayBillItem::class,'way_bill_cargo_id','id')->with(['ikpu','package']);
    }

}
