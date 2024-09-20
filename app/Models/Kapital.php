<?php
namespace App\Models;

use App\Helpers\CryptHelper;
use App\Http\Traits\CorrectPassword;
use App\Http\Traits\Password;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapital extends Model
{
    use HasFactory,CorrectPassword,Password;
    protected $guarded = [];

    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function getSid(){
        return CryptHelper::decrypt($this->sid);
    }
}
