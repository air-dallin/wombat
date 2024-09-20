<?php
namespace App\Http\Traits;

use App\Helpers\CryptHelper;

trait Password
{

    public function getPassword(){
        return CryptHelper::decrypt($this->password);
    }

    public function getLogin(){
        return CryptHelper::decrypt($this->login);
    }

    public function encode(&$params){
        if(!empty($params['password'])) $params['password'] = CryptHelper::encrypt($params['password']);
        if(!empty($params['login'])) $params['login'] = CryptHelper::encrypt($params['login']);
    }

}
