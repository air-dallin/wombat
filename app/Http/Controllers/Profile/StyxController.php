<?php
namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\StyxService;

class StyxController extends Controller{


    public function getCertificateInfo(){
        $result = StyxService::getCertInfo();
        if(empty($result->certInfos)){
            return ['status'=>false,'error'=>__('main.epass_certificate_not_found')];
        }
        return ['status'=>true];
    }



}
