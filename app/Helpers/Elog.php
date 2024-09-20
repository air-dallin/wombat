<?php

namespace App\Helpers;

class Elog{

    public static function save($data,$type='info'){

	   $date = date('Y-m-d');
	   $path = 'logs/'.$date . '_' . $type.'_logs.txt';
	   $mode = (!file_exists($path)) ? 'w':'a';

       $f = fopen($path,$mode);
       fwrite($f, date('Y-m-d H:i:s') . ' ' . json_encode($data,JSON_UNESCAPED_UNICODE) ."\n");
       fclose($f);

    }


}
