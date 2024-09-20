<?php

namespace App\Models;

use App\Http\Traits\ByCompany;
use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use App\Services\DidoxService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Nomenklature extends Model
{
    use HasFactory, Locale,Order,Status,ByCompany,MyCompany,Statuses;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 9;

    protected $guarded = [];

    public function ikpu(){
        return $this->hasOne(Ikpu::class,'id','ikpu_id');
    }
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function unit(){
        return $this->hasOne(Unit::class,'id','unit_id');
    }

    public static function getByProduct($items,$company_id)
    {
        $ikpu_ids = [];
        foreach ($items as $item){
            $ikpu_ids[] = $item->ikpu_id;
        }
        if(count($ikpu_ids)) return Nomenklature::where(['company_id'=>$company_id])->whereIn('ikpu_id',$ikpu_ids)->get();
        return false;

    }

    /** Пересчитать колво товаров
     */
    public static function recalculateQuantity(&$document,$productItems=null){
        // добавить или удалить номенклатуру
        $sgn = $document->owner==DidoxService::OWNER_TYPE_OUTGOING ? -1 : 1;
        $ikpu_ids = [];
        $items = [];
        if(empty($productItems)) $productItems = $document->items;
        foreach ($productItems as $item){
            $ikpu_ids[] = $item->ikpu_id;
            $items[$item->ikpu_id] = ['quantity'=>$item->quantity,'unit_id'=>$item->unit_id];
        }
        if(count($ikpu_ids)) {
            // изменение номенклатур, которые имеются в компании
            $nomenklatures = Nomenklature::where(['company_id'=>$document->company_id])->whereIn('ikpu_id',$ikpu_ids)->get();
            foreach($nomenklatures as $nomenklature){
                $quantity = $nomenklature->quantity + $sgn * $items[$nomenklature->ikpu_id]['quantity'];
                if($quantity>=0){
                    $nomenklature->update(['quantity'=>$quantity]);
                    unset($items[$nomenklature->ikpu_id]);
                    $ikpu_ids = array_flip($ikpu_ids);
                    unset($ikpu_ids[$nomenklature->ikpu_id]);
                    $ikpu_ids = array_flip($ikpu_ids);
                }
            }
            if(count($items) && $sgn==1){
                // добавление номенклатур, которых нет в компании
                foreach($items as $ikpu_id=>$item){
                    if($item['quantity']>=0){
                        if($ikpu = Ikpu::where(['id'=>$ikpu_id])->first()) {
                            Nomenklature::create([
                                'company_id' => $document->company_id,
                                'user_id' => $document->user_id,
                                'ikpu_id' => $ikpu_id,
                                'unit_id' => $item['unit_id'],
                                'quantity' => $item['quantity'],
                                'title_ru' => $ikpu->title_ru,
                                'title_uz' => $ikpu->title_uz,
                                'title_en' => $ikpu->title_en,
                                'status' => 1,
                            ]);
                        }
                    }
                }
            }
        }
        return true;
    }

}
