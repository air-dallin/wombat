<?php

namespace App\Http\Traits;

use App\Helpers\CryptHelper;

trait CorrectPassword
{
    public function correctPassword(&$params){
        if( $this->password == $params['password']){
            unset($params['password']);
        }else{
            $this->refreshPassword($params);
        }
    }

    public function refreshPassword(&$params){
        $params['password'] = CryptHelper::encrypt($params['password']);
    }

}
