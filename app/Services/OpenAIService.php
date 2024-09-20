<?php
namespace App\Services;

use App\Helpers\Elog;

class OpenAIService{

    const OPENAI_API_KEY = '';
    private static $context = false;

    public static function send(&$openAI){

        Elog::save('OpenAI::send for id:' .$openAI->id . ' document_id: '. $openAI->doctype. ' document_id: '. $openAI->document_id,'openai');
        Elog::save('coockie' ,'openai');
        Elog::save($_COOKIE ,'openai');


        $messages= [];
        if(self::$context) {
            if (!empty($openAI->chatItems)) $messages = self::getMessages($openAI->chatItems);
        }else{
            if(!empty($openAI->chatItems)) $messages = self::getMessage($openAI);
        }


        //dd($messages);

        Elog::save('messages' ,'openai');
        Elog::save($messages ,'openai');

        Elog::save($openAI->chatItems,'openai');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "model": "gpt-3.5-turbo",
                "messages": ' . $messages.'
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::OPENAI_API_KEY,
                //'Cookie: _cfuvid=Ym2Qe_sRyx1Iav318hs3y9Gu2nIZ1SBdDG.kGA6YnAE-1714540940223-0.0.1.1-604800000'
            ),
        ));


        $response = curl_exec($curl);
        Elog::save('response:','openai');
        Elog::save($response,'openai');


        curl_close($curl);

        return json_decode($response);

    }

    public static function getMessages(&$chatItems){
        $messages = [];
        if(!empty($chatItems)){
            foreach ($chatItems as $item){
                $messages[] = ['role'=>$item->role,'content'=>$item->message];
            }
        }
        Elog::save('getMessages','openai');
        Elog::save($messages,'openai');
        return json_encode($messages,JSON_UNESCAPED_UNICODE);
    }
    public static function getMessage(&$openai){

        $openai->load('chatItemMain','chatItemLast');
        $messages = [];
        if(!empty($openai->chatItemMain)) $messages[] = ['role'=>$openai->chatItemMain->role,'content'=>$openai->chatItemMain->message];
        if(!empty($openai->chatItemLast)) $messages[] = ['role'=>$openai->chatItemLast->role,'content'=>$openai->chatItemLast->message];
        Elog::save('getMessageLast','openai');
        Elog::save($messages,'openai');
        return json_encode($messages,JSON_UNESCAPED_UNICODE);
    }

}
