<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\OpenaiChat;
use App\Models\OpenaiChatItems;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WombatAIController extends Controller{
    public function sendMessage(Request $request){
        If(!$openAI = OpenaiChat::where(['user_id'=>Auth::id(),'doctype'=>$request->doctype,'document_id'=>$request->document_id])->first()) {
            $openAI = OpenaiChat::create(['user_id'=>Auth::id(),'company_id'=>$request->company_id,'doctype'=>$request->doctype,'document_id'=>$request->document_id]);
        }
        $userChat = OpenaiChatItems::create(['openai_chat_id'=>$openAI->id,'role'=>'user','message'=>$request->message]);
        $openAI->load('chatItems','chatItemMain');

        $result = OpenAIService::send($openAI);

        if(empty($result->error)){
            $message = $result->choices[0]->message->content;
            $time = date('h:i A',$result->created);
            $userChat->update(['tokens'=>$result->usage->prompt_tokens]);
            OpenaiChatItems::create(['openai_chat_id'=>$openAI->id,'role'=>$result->choices[0]->message->role,'message'=>$message,'tokens'=>$result->usage->completion_tokens]);
            return ['status'=>true,'message'=>$message,'time'=>$time];

        }else{
            $error = $result->error->message . ' type: ' . $result->error->type . ' param: ' . $result->error->param. ' code: ' . $result->error->code;
        }

        return ['status'=>false,'error'=>$error];
    }
}
