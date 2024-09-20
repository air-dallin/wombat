<?php

namespace App\Helpers;

use App\Models\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;


class FileHelper {

    public static function createArchieve(&$document,$params){

        $filename= $params['filename'];
        $pdf_params = [
            'view'=>'frontend.profile.modules.'.$params['type'].'.print',
            'data' => $params['data'],
            'orientation' => !empty($params['orientation']) ? $params['orientation']:'landscape',
            'filename' => $filename.'.pdf'
        ];

        $response_sign = json_decode($document->response_sign);

        if(empty($response_sign->data)) return ['status'=>false,'error'=>__('main.sign_not_set')];

        $sign = isset($params['not_save_sign']) ? false : $response_sign->data->toSign;
        $json = json_encode($response_sign->data->json,JSON_UNESCAPED_UNICODE);


        PdfHelper::create($pdf_params,true);
        if($sign) file_put_contents(public_path().'/'.$filename .'.p7s',$sign);
        file_put_contents(public_path().'/'.$filename .'.json',$json);

        $zip = new ZipArchive();
        $zip->open(public_path().'/'.$filename.'.zip',  ZipArchive::CREATE);
        if($sign) $zip->addFile($filename .'.p7s');
        $zip->addFile($filename .'.json');
        $zip->addFile($filename .'.pdf');
        $zip->close();

        if($sign) @unlink(public_path().'/'.$filename .'.p7s');
        @unlink(public_path().'/'.$filename .'.json');
        @unlink(public_path().'/'.$filename .'.pdf');

        return ['status'=>true,'file'=>public_path().'/'.$filename.'.zip'];
    }


    public static function download($file){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        @unlink($file);
        exit;
    }

    public static function createDir($path){
        $path = public_path().'/'. $path;
        @mkdir($path, 0777, true);
        return $path;

    }
    public static function move($filefrom,$fileto){
        Storage::move($filefrom, $fileto);
    }

    public static function getFileBase64($file){
        return base64_encode(file_get_contents($file->getRealPath()));
    }

}
