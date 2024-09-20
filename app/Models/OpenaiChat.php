<?php

namespace App\Models;

use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OpenaiChat extends Model
{
    use HasFactory,Order;

    protected $guarded = [];

    public function chatItems(){
        return $this->hasMany(OpenaiChatItems::class,'openai_chat_id','id'); //->orderBy('created_at');
    }
    public function chatItemMain(){
        return $this->hasOne(OpenaiChatItems::class,'openai_chat_id','id')->where(['role'=>'system']); //->orderBy('created_at');
    }
    public function chatItemLast(){
        return $this->hasOne(OpenaiChatItems::class,'openai_chat_id','id')->where(['role'=>'user'])->orderBy('created_at','DESC');
    }
    public function contract(){
        return $this->hasOne(Contract::class,'id','contract_id'); //->orderBy('created_at');
    }

    public static function init(&$params){
        $openAI = OpenaiChat::create(['user_id'=>Auth::id(),'company_id'=>$params['company_id'],'doctype'=>$params['doctype'],'document_id'=>$params['document_id']]);
        $template = $params['template'];
        clear_content($template);
        OpenaiChatItems::create(['openai_chat_id'=>$openAI->id,'role'=>'system','message'=>$template]);
        return true;
    }

}
