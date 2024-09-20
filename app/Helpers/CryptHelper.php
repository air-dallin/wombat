<?php

namespace App\Helpers;

class CryptHelper{

    const ENCRYPTION_KEY = 'd5c862afab86d144e3f080b61c7c6d84'; // readonly !!!


    /**
     * шифровать
    */
    public static function encrypt($data){
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $cipher_raw = openssl_encrypt($data, $cipher, self::ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $cipher_raw, self::ENCRYPTION_KEY, $as_binary=true);

        return base64_encode( $iv.$hmac.$cipher_raw );
    }

    /**
     * расшифровать
     */
    public static function decrypt($data){
        $c = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $cipher_raw = substr($c, $ivlen+$sha2len);
        $plaintext = openssl_decrypt($cipher_raw, $cipher, self::ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $cipher_raw, self::ENCRYPTION_KEY, $as_binary=true);

        return hash_equals($hmac, $calcmac) ? $plaintext : null;
    }


}
