<?php

namespace App\Helpers;

use LaravelQRCode\Facades\QRCode;

class QrcodeHelper{

    public static function create($url,$base64 = true){
        ob_start();
        QRCode::url($url)->png();
        $qrcode = ob_get_contents();
        ob_end_clean();
        if($base64) $qrcode = 'data:image/png;base64, ' . base64_encode($qrcode);
        return $qrcode;
    }


}
