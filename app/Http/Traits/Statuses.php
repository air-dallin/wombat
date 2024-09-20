<?php

namespace App\Http\Traits;

use App\Models\Company;
use App\Services\DidoxService;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;

trait Statuses
{
    public function scopeActive($query){
            return $query->whereNotIn('status',[Status::STATUS_DELETED,Status::STATUS_DRAFT]);
    }
    public function scopeInActive($query){
            return $query->whereIn('status',[Status::STATUS_DRAFT]);
    }
    public function scopeDraft($query){
            return $query->where(['status'=>Status::STATUS_DRAFT]);
    }
    public function scopeDeleted($query){
            return $query->where(['status'=>Status::STATUS_DELETED]);
    }
    public function scopeNotDeleted($query){
            return $query->whereNotIn('status',[Status::STATUS_DRAFT,Status::STATUS_DELETED]);
    }
    public function scopeNotDeletedOnly($query){
        return $query->whereNotIn('status',[Status::STATUS_DELETED]);
    }
    public function scopeSigned($query){
        return $query->where(['status'=>Status::STATUS_SIGNED]);
    }
    /** проверка на подпись */
    public function scopeIsNotSigned(){
        if($this->doc_status==DidoxService::STATUS_CREATED){
            return true;
        }elseif($this->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE && !empty($this->response_sign)) {
            return true;
        }
        return false;
    }
    /** проверка на возможность удаления */
    public function scopeCanDestroy(){
        if($this->owner==\App\Services\DidoxService::OWNER_TYPE_OUTGOING && $this->doc_status==\App\Services\DidoxService::STATUS_CREATED /*&& $this->doc_status!=\App\Services\DidoxService::STATUS_DELETED*/ ){
             return (time()-strtotime($this->created_at))/86400 < 10;  // менее 10 дней со дня создания
        }
        return false;

    }

}
