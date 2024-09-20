<?php


namespace App\Models;


class WayBillItem
{

    use HasFactory;

    protected $guarded = [];

    public function cargo()
    {
        return $this->hasOne(WayBillCargo::class, 'id', 'way_bill_cargo_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function package()
    {
        return $this->hasOne(Package::class, 'id', 'unit_id');
    }

    public function ikpu()
    {
        return $this->hasOne(Ikpu::class, 'id', 'ikpu_id');
    }


}
