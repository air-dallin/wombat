<?php
namespace App\Services;

use App\Helpers\CryptHelper;
use App\Helpers\Elog;
use App\Models\PaymentOrder;

class StyxService {

    // статусы документов
    const STATUS_UNDEFINED = 0; // не определен
    const STATUS_ENTERED = 1;  // введен
    const STATUS_CONFIRMED = 2; // утвержден
    const STATUS_EXECUTED = 3; // проведен
    const STATUS_DELETED = 6; // удален
    const STATUS_POSTPONED = 16; // отложен

    // статусы платежных поручений
    const STATUS_REJECTED = 53; // забракован
    const STATUS_PROCESSED = 54; // обработан
    const STATUS_IN_PROCESS = 55; // в обработке
    const STATUS_AWAITING = 56; // ожидает опер. дня
    const STATUS_EXPIRED = 57; // просрочен

    /** get certificate
     * step 1 & 2
     */
    public static function getCertificate(){
        Elog::save('StyxService::getCertificate');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:6210/crypto/getCertificate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
              "phone": "+998903240031",
              "inn": "309436114",
              "pin": "",
              "password": "309436114a",
              "token_type": "virtual"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/json; charset=utf-8'
            ),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);
        Elog::save('response:');
        Elog::save($response);
        return $response;

    }

    public static function getCertInfo(){
        Elog::save('StyxService::getCertInfo');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:6210/crypto/getCertInfo',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/json; charset=utf-8'
            ),
        ));

        $response = json_decode( curl_exec($curl));

        /**  response
         *
         * {
            "status": "success",
            "errorCode": 0,
            "comments": "The Smart card resource manager is not running!",
            "signedMsg": null,
            "tokenStatus": null,
            "macaddresses": [
                "001C428722D8"
            ],
            "certInfos": [
            {
                "issuer": "CN=CERTSRV-CA-CBRU(2)",
                "notafter": "2023-03-11T05:00:00+05:00",
                "notbefore": "2022-03-11T05:00:00+05:00",
                "serialnumber": "69EBEF22162AA348",
                "thumbprint": "600891EB67537FC6F01D198EBF363A66B41AB6D4",
                "subject": "STREET=Sayilgoh, E=info@gmail.com, CN=Ivanov Ivan, OU=IT, O=Roga Kopyta, S=Tashkent, L=Tashkent, C=UZ",
                "pubCert": "MIIC+zCCAeOgAwIBAgIIaevvIhYqo0gwDQYJKoZIhvcNAQELBQAwHTEbMBkGA1UEAwwSQ0VSVFNSVi1DQS1DQlJVKDIpMB4XDTIyMDMxMTAwMDAwMFoXDTIzMDMxMTAwMDAwMFowgZ4xCzAJBgNVBAYTAlVaMREwDwYDVQQHEwhUYXNoa2VudDERMA8GA1UECBMIVGFzaGtlbnQxFDASBgNVBAoTC1JvZ2EgS29weXRhMQswCQYDVQQLEwJJVDEUMBIGA1UEAxMLSXZhbm92IEl2YW4xHTAbBgkqhkiG9w0BCQEWDmluZm9AZ21haWwuY29tMREwDwYDVQQJEwhTYXlpbGdvaDBgMBkGCSqGXAMPAQECATAMBgoqhlwDDwEBAgEBA0MABECtZ55Sxs85YTYzyJjm2aqAt/SzQcIKTkUwIkBOGNzDHbU4b8i+ue6PURnWorlrNFGTqs5cV9TBVLjUK1yblqZRo4GAMH4wFAYDVR0gAQH/BAowCDAGBgRVHSAAMBYGA1UdJQEB/wQMMAoGCCsGAQUFBwMEMB0GA1UdDgQWBBT1hbjaVb91pQU1PSYCbVOQNuMy5jAfBgNVHSMEGDAWgBS6PNqZgBf+hhCUIdhxTFbjO1qS4jAOBgNVHQ8BAf8EBAMCBPAwDQYJKoZIhvcNAQELBQADggEBAIOivpasTQOfpNB1iaEkTyZ3j8uViMhCna2MMXxBFp9ALGNMt+GSxxjumJJNArpC1OLCLRglkTwp5LahILo2Irp3PYoBNnjjXqslAZL5i9XhTAm7mddPVVmeeSrm0Ytdao1+NT+lpMROdJUCgs91GuUqlpP31QPQKZdiW8Y+65VGck3TExNagYDaBfCTC5WrJjRnXQlpnJNMpafTb0YcROvNG9XxBwt1MwZmgJVDMxSUaYnrFGM+EUrPUHp+Pzk9be8ShGCshan1u2r+0rVRc70eLyyXpuHMkZ9AkYzpTAu16wNQ+iEUHN5gYJv9a/9u7Q3jdw57u+8Ulny52X9HCFM="
            },
            null
            ],
            "var1": null,
            "var2": null,
            "var3": null
        }
         */

        curl_close($curl);
        Elog::save('response:');
        Elog::save($response);
        return $response;
    }

    public static function signByThumb(){
        Elog::save('StyxService::signByThumb');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:6210/crypto/signMSG',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "obj": "{\\"bic_to\\":\\"testrumm\\",\\"amount\\":1234,\\"currency\\":\\"usd\\",\\"ben_party_acc\\":\\"22614840505391906001\\"}",
                "cms": false,
                "detached": false,
                "cert_thumb": "300000948D51158DEF8119600100030000948D",
                "cert_sn": ""
            }
            ',
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/json; charset=utf-8'
            ),
        ));


        $response = json_decode(curl_exec($curl));

        curl_close($curl);
        Elog::save('response:');
        Elog::save($response);
        return $response;
    }

    /**
     * @var $data
     * @data_sample: 22.06.2020:123:00974:20208000012345678001:01088:20208000087654321002:100000
    */
    public static function signBySerial($serial,$strToSign){
        Elog::save('StyxService::signBySerial');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:6220/crypto/signMSG',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'OPTIONS',
            CURLOPT_POSTFIELDS =>'{
                "obj": "'.$strToSign.'",
                "cert_sn": "'.$serial.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/json; charset=utf-8'
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        Elog::save('response:');
        Elog::save($response);
        return $response;

    }

    /**
     * @var PaymentOrder $document
     * @var array $seller
     * @var array $client
    */
    public static function strToSign($document,$seller,$client){
        /**
         * string strToSign = string.Format("{0}:{1}:{2}:{3}:{4}:{5}:{6}",
            ddate, num, mfo_dt, acc_dt, mfo_ct, acc_ct, amount);
        Пример подписываемой строки:
        strToSign =  "22.06.2020:123:00974:20208000012345678001:01088:20208000087654321002:100000"
        ddate в формате ДД.ММ.ГГГГ,
        amount – сумма платежа в тийинах
         */
        $amount = $document->amount  * 100;
        return date('dd.mm.YY',strtotime($document->order_date)) .':'.$document->number . ':'.$seller['mfo'] .':'.$seller['account'].':'.$client['mfo'].':'.$client['account'] .':'.$amount;
    }

    public static function uniq($document){
        /** YYYYMMDDH24MISSFFFNUMLOGIN, где YYYY-год, MM-
        месяц, DD-день, H24-часы в 24-х часовом формате, MI-
        минуты, SS-секунды, FFF-миллисекунды, NUM-номер
        документа, LOGIN-логин клиента. */
        $mt = rand(100,999);
        $login = CryptHelper::decrypt($document->company->kapital->login);
        return date('YmdHis') . $mt . $document->number . $login;
    }

}
