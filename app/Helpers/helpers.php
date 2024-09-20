<?php

use App\Helpers\TelegramHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

if (!function_exists('localeRoute')) {
    /**
     * Generate the URL to a named route with locale.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function localeRoute($name, $parameters = [], $absolute = true){
        $locale = app()->getLocale();
        if(!is_array($parameters))
            $params[] = $parameters;
        else $params = $parameters;
        if(session('locale'))
            $locale = session('locale');
        return app('url')->route($name, array_merge($params, ['locale' => $locale]), $absolute);

    }
}

if(!function_exists('d')){
    function d($data,$exit = false){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        echo '<hr>';
        if($exit) exit;
    }
}

if(!function_exists('monthCompare')){

    function monthCompare($a, $b) {
        $months = ['JAN' => 1, 'FEB' =>2,'MAR' => 3,'APR' => 4,'MAY' => 5,'JUN' => 6,'JULY' => 7,'AUG' => 8, 'SEP' => 9, 'OCT' => 10, 'NOV' => 11, 'DEC' => 12];
        if($a[0] == $b[0]) {
            return 0;
        }
        return ($months[$a[0]] > $months[$b[0]]) ? 1 : -1;
    }
}

if(!function_exists('correct_phone')) {

    function correct_phone($phone){
        if(empty($phone)) return '';
        $phone = preg_replace('/[^0-9]/','',$phone);
        return $phone;

    }
}
if(!function_exists('is_passport')) {

    function is_passport($passport){

        return preg_match('/^([a-zA-Z]{2})([0-9]{7})$/','',$passport);

    }
}

if(!function_exists('upFirstLetter')){
    function upFirstLetter($str, $encoding = 'UTF-8')
    {
        if(mb_strlen($str)) {
            $str = mb_strtolower($str, $encoding);
            return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
        }
        return '';
    }
}

if(!function_exists('num2str')) {
    function num2str($inn, $stripkop = false)
    {
        $nol = 'ноль';
        $str[100] = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
        $str[11] = array('', 'десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать', 'двадцать');
        $str[10] = array('', 'десять', 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
        $sex = array(
            array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),// m
            array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять') // f
        );
        $forms = array(
            array('тийин', 'тийин', 'тийин', 1), // 10^-2
            array('сум', 'сума', 'сумов', 0), // 10^ 0
            array('тысяча', 'тысячи', 'тысяч', 1), // 10^ 3
            array('миллион', 'миллиона', 'миллионов', 0), // 10^ 6
            array('миллиард', 'миллиарда', 'миллиардов', 0), // 10^ 9
            array('триллион', 'триллиона', 'триллионов', 0), // 10^12
        );
        $out = $tmp = array();
        // Поехали!
        $tmp = explode('.', str_replace(',', '.', $inn));
        $rub = number_format($tmp[0], 0, '', '-');
        if ($rub == 0) $out[] = $nol;
        // нормализация копеек
        $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0, 2) : '00';
        $segments = explode('-', $rub);
        $offset = sizeof($segments);
        if ((int)$rub == 0) { // если 0 рублей
            $o[] = $nol;
            $o[] = morph(0, $forms[1][0], $forms[1][1], $forms[1][2]);
        } else {
            foreach ($segments as $k => $lev) {
                $sexi = (int)$forms[$offset][3]; // определяем род
                $ri = (int)$lev; // текущий сегмент
                if ($ri == 0 && $offset > 1) {// если сегмент==0 & не последний уровень(там Units)
                    $offset--;
                    continue;
                }
                // нормализация
                $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
                // получаем циферки для анализа
                $r1 = (int)substr($ri, 0, 1); //первая цифра
                $r2 = (int)substr($ri, 1, 1); //вторая
                $r3 = (int)substr($ri, 2, 1); //третья
                $r22 = (int)$r2 . $r3; //вторая и третья
                // разгребаем порядки
                if ($ri > 99) $o[] = $str[100][$r1]; // Сотни
                if ($r22 > 20) {// >20
                    $o[] = $str[10][$r2];
                    $o[] = $sex[$sexi][$r3];
                } else { // <=20
                    if ($r22 > 9) $o[] = $str[11][$r22 - 9]; // 10-20
                    elseif ($r22 > 0) $o[] = $sex[$sexi][$r3]; // 1-9
                }
                // Рубли
                $o[] = morph($ri, $forms[$offset][0], $forms[$offset][1], $forms[$offset][2]);
                $offset--;
            }
        }
        // Копейки
        if (!$stripkop) {
            $o[] = $kop;
            $o[] = morph($kop, $forms[0][0], $forms[0][1], $forms[0][2]);
        }
        return preg_replace("/\s{2,}/", ' ', implode(' ', $o));
    }
}

if(!function_exists('morph')) {
    /**
     * Склоняем словоформу
     */
    function morph($n, $f1, $f2, $f5)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $f5;
        if ($n1 > 1 && $n1 < 5) return $f2;
        if ($n1 == 1) return $f1;
        return $f5;
    }
}


if(!function_exists('report_filter')) {

    function report_filter(&$query, $filterBy = false)
    {

        $request = request();

        if ($query) {
            $sql = $query->toSql();

            if (!$filterBy) {
                $created_at = 'created_at';
                // проверка на использование таблицы order_products
                if (strpos($sql, 'order_products') > 0) {
                    $created_at = 'order_products.created_at';
                }
            } else {
                $created_at = $filterBy;
            }
        }

        switch ($request->type) {
            case 'custom':

                if (is_array($request->date)) {
                    $date_from = $request->date[0];
                    $date_to = $request->date[1];
                } else {
                    list($date_from, $date_to) = explode(',', $request->date);
                }

                if (!empty($date_from)) {
                    $date_from = date('Y-m-d 00:00:00', strtotime($date_from));
                }

                if (!empty($date_to)) {
                    $date_to = date('Y-m-d 23:59:59', strtotime($date_to));
                }

                if ($query && !is_null($date_from) && !is_null($date_to)) {
                    $query->whereBetween($created_at, [$date_from, $date_to]); // confirmed_at - дата подтверждения ??
                }

                break;
            case 'last_7_days': // за последние 7 дней
                $date_from = date('Y-m-d', strtotime('-6 days'));
                $date_to = date('Y-m-d');
                if ($query) $query->whereBetween($created_at, [$date_from, $date_to]); // confirmed_at - дата подтверждения ??
                break;
            case 'last_week': // за неделю
                $d = date('d');
                $w = date('w');
                if ($w > 0) {
                    $dt = $d;
                } elseif ($w == 0) {
                    $dt = 7;
                }
                $date_from = date('Y-m-d 23:59:59', strtotime('-' . $dt . ' days'));
                $date_to = date('Y-m-d 23:59:59');
                if ($query) $query->whereBetween($created_at, [$date_from, $date_to]); // confirmed_at - дата подтверждения ??
                break;
            case 'last_month': // за месяц
                $m = date('m');
                $date_from = date('Y-' . $m . '-01 00:00:00');
                $date_to = date('Y-m-d 23:59:59');
                if ($query) $query->whereBetween($created_at, [$date_from, $date_to]); // confirmed_at - дата подтверждения ??

                break;
            case 'last_half_year': // за полгода
                $date_from = date('Y-m-d H:i:s', strtotime('-6 months'));
                $date_to = date('Y-m-d 23:59:59');
                if ($query) $query->whereBetween($created_at, [$date_from, $date_to]); // confirmed_at - дата подтверждения ??
                break;
            case 'last_day': // текущий день
            default:
                $date_from = date('Y-m-d 00:00:00', time());
                $date_to = date('Y-m-d 23:59:59', time());
                if ($query) $query->whereBetween($created_at, [$date_from, $date_to]); // confirmed_at - дата подтверждения ??

        }

        Log::channel('report')->info($request);

        if ($query) Log::channel('report')->info($query->toSql());
        if ($query) Log::channel('report')->info($query->getBindings());

        return ['date_from' => $date_from, 'date_to' => $date_to];

    }

    if (!function_exists('system_dump')) {
        function system_dump($exception)
        {

            $error_msg = $exception->getMessage();
            $em = explode('updated_at', $error_msg);

            $error_hash = md5($em[0]);

            if (!Redis::exists($error_hash) && $error_msg) {

                $msg = '';
                foreach ($exception->getTrace() as $e) {
                    if (!isset($e['file']) || !isset($e['function']) || !isset($e['line'])) continue;
                    if (strpos($e['function'], 'upload') > 0 && strpos($e['file'], 'FileHelper.php') > 0) continue;
                    if (strpos($e['file'], 'about.blade.php') > 0) continue;
                    if (strpos($e['file'], 'vendor') > 0) continue;
                    if (strpos($e['function'], 'lluminate') > 0) continue;
                    $msg .= '<b>File:</b> ' . $e['file'] . "\n<b>Line:</b> " . $e['line'] . "\n<b>Function:</b> " . $e['function'] . "\n";
                }

                if ($msg != '') {

                    Redis::set($error_hash, 1);
                    Redis::expire($error_hash, 3200);

                }

            }
        }
    }

    if (!function_exists('partner_phone_short')) {
        function partner_phone_short($phone)
        {
            return mb_substr(correct_phone($phone), 3, 9);
        }
    }

    if (!function_exists('partner_phone')) {
        function partner_phone($phone)
        {
            return '998' . $phone;
        }
    }
    if (!function_exists('validate_date')) {
        function validate_date($date, $format = 'Y-m-d')
        {
            $d = DateTime::createFromFormat($format, $date);
            // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
            return $d && $d->format($format) === $date;
        }
    }

    if (!function_exists('clear_content')) {

        function clear_content(&$content)
        {
            $content = preg_replace('/\s?<style[^>]*?>.*?<\/style>\s?/si', ' ', $content);
            $content = preg_replace('/\s?<script[^>]*?>.*?<\/script>\s?/si', ' ', $content);
            $content = strip_tags($content);
            $content = preg_replace('/\r?\n|\r/si', '', $content);
            $content = preg_replace('/[ \t]{2,}/si', ' ', $content);
            return true;
        }
    }

}

