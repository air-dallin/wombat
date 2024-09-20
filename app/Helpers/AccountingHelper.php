<?php


namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AccountingHelper
{
    public static $pageLimit = 10;

    public static function getData($options)
    {
        $limit = ' LIMIT ' . ($options['page'] - 1) * self::$pageLimit . ',' . self::$pageLimit;

        $sfrom = date('2020-01-01');
        $sto = date('Y-m-d', strtotime($options['from'] . ' -1 day'));
        $result = DB::select("SELECT   dt, ct,amount , dt_title , dt_type, ct_title , ct_type, currency,stype
            FROM(
                SELECT a.debit_account AS dt,a.credit_account AS ct,SUM(a.amount) as amount , pd.title_ru AS dt_title ,pd.type AS dt_type, pc.title_ru AS ct_title ,pc.type AS ct_type, a.currency,0 as stype
                FROM accountings a
                left JOIN plans pd ON pd.code=a.debit_account
                left JOIN plans pc ON pc.code=a.credit_account
                WHERE (a.company_id={$options['company_id']} ) AND a.date BETWEEN  '{$options['from']}' AND '{$options['to']}'
                GROUP BY a.debit_account,a.credit_account, pd.title_ru,pc.title_ru,pd.`type`,pc.`type`,a.currency
                #{$limit}

                UNION all

                SELECT a.debit_account AS dt,a.credit_account AS ct,SUM(a.amount) as amount , pd.title_ru AS dt_title ,pd.type AS dt_type, pc.title_ru AS ct_title ,pc.type AS ct_type, a.currency,1 as stype
                FROM accountings a
                left JOIN plans pd ON pd.code=a.debit_account
                left JOIN plans pc ON pc.code=a.credit_account
                WHERE (a.company_id={$options['company_id']} ) /* AND (pd.`type`=1 or pc.`type`=1)*/ AND a.date BETWEEN  '{$sfrom}' AND '{$sto}'
                GROUP BY a.debit_account,a.credit_account, pd.title_ru,pc.title_ru,pd.`type`,pc.`type`,a.currency
                #{$limit}

                ) res

                {$limit}

            ");
        return $result;

    }

    /** колво
     */
    public static function getCount($options)
    {
        $sfrom = date('2020-01-01');
        $sto = date('Y-m-d', strtotime($options['from'] . ' -1 day'));
        $result = DB::select("SELECT COUNT(res.dt) as cnt, stype FROM (
                SELECT  a.debit_account as dt,a.credit_account as ct,0 as stype
                FROM accountings a

                left JOIN plans pd ON pd.code=a.debit_account
                left JOIN plans pc ON pc.code=a.credit_account
                WHERE (a.company_id={$options['company_id']} ) AND a.date BETWEEN '{$options['from']}' AND '{$options['to']}'
                GROUP BY a.debit_account,a.credit_account

                UNION all

                SELECT  a.credit_account as dt,a.credit_account as ct,1 as stype
                FROM accountings a
                left JOIN plans pd ON pd.code=a.debit_account
                left JOIN plans pc ON pc.code=a.credit_account
                WHERE (a.company_id={$options['company_id']} ) AND (pd.`type`=1 or pc.`type`=1) AND a.date BETWEEN '{$sfrom}' AND '{$sto}'
                GROUP BY a.debit_account,a.credit_account
                ) res
                GROUP BY stype
                ");
        // dump($result);
        if (empty($result[0])) return 0;
        return $result[0]->cnt;
    }

    /** оборотно-сальдовая ведомость
     */
    public static function getPlanRows(&$accountings)
    {
        $planRows = [];
        $n = 0;
        $_url = explode('?', request()->getUri());
        $params = self::getQueryParams($_url);
        //dd($params);
        //dump($accountings);
        foreach ($accountings as $account) {
            if ($account->stype == 0) {
                $url = $_url[0] . '/' . $account->dt . '-' . $account->ct . $params;
                $type = $account->dt_type == 0;
                $planRows[$n] = [
                    'code' => $account->dt,
                    'title' => $account->dt_title,
                    'type' => $type,
                    'url' => $url,
                    'items' => []
                ];
                self::setAmount($planRows[$n]['items'], $account->amount, $type);
                $n++;
                $type = $account->ct_type == 0;
                $planRows[$n] = [
                    'code' => $account->ct,
                    'title' => $account->ct_title,
                    'type' => $type,
                    'url' => $url,
                    'items' => []
                ];
                self::setAmount($planRows[$n]['items'], $account->amount, $type);
                $n++;
            } else {
                // начальное / конечное сальдо

                if (self::exist($planRows, $account)) {
                    self::getSaldo($planRows, $account);
                } else {
                    $prefix = strlen($params)?'&':'?';
                    $url = $_url[0] . '/' . $account->dt . '-' . $account->ct . $params . $prefix .'saldo=start';
                    $type = $account->dt_type == 0;
                    $planRows[$n] = [
                        'code' => $account->dt,
                        'title' => $account->dt_title,
                        'type' => $type,
                        'url' => $url,
                        'items' => []
                    ];
                    self::initRows($planRows[$n]['items'], 0);
                    $col = $account->dt_type == 1 ? 1 : 0;
                    $planRows[$n]['items'][$col] = $account->amount;
                    $planRows[$n]['items'][$col + 4] = $account->amount;
                    $n++;
                    $type = $account->ct_type == 0;
                    $planRows[$n] = [
                        'code' => $account->ct,
                        'title' => $account->ct_title,
                        'type' => $type,
                        'url' => $url,
                        'items' => []
                    ];
                    self::initRows($planRows[$n]['items'], 0);
                    $col = $account->dt_type == 1 ? 1 : 0;
                    $planRows[$n]['items'][$col] = $account->amount;
                    $planRows[$n]['items'][$col + 4] = $account->amount;

                    $n++;
                }
            }
        }
        return $planRows;
    }

    public static function initRows(&$items, $value)
    {
        $items[0] = $value;                                                               // сальдо на начало
        $items[1] = $value;
        $items[2] = $value;
        $items[3] = $value;
        $items[4] = $value;
        $items[5] = $value;
    }

    /**
     * таблица дебет-кредит
     */
    public static function setAmount(&$items, $amount, $type)
    {
        $items[0] = 0;                                                               // сальдо на начало
        $items[1] = 0;
        $items[2] = $type == 1 ? $amount  /*. ' '. $account->currency*/ : 0;   // оборот
        $items[3] = $type == 0 ? $amount  /*. ' '. $account->currency*/ : 0;
        $items[4] = $type == 1 ? $amount  /*. ' '. $account->currency*/ : 0;  // сальдо на конец
        $items[5] = $type == 0 ? $amount  /*. ' '. $account->currency*/ : 0;
    }

    /** параметры фильтрации
     */
    public static function getoptions(&$request)
    {
        $options['company_id'] = $request->company->id;
        $options['page'] = $request->has('page') ? $request->page : 1;
        $options['from'] = $request->has('from') ? $request->from : date('Y-m-01');
        $options['to'] = $request->has('to') ? $request->to : date('Y-m-d');
        return $options;
    }

    /** пагинация массива
     */
    public static function arrayPaginator($array, $request, $options)
    {
        $page = request()->get('page', 1);
        $rowsCount = AccountingHelper::getCount($options);//count($array) ;
        return new LengthAwarePaginator(
            $array,
            $rowsCount,
            self::$pageLimit,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    /** запрос и корректировка страницы
     */
    public static function getQueryParams($url)
    {
        if (!empty($url[1])) {
            $res = [];
            parse_str($url[1], $res);
            if (isset($res['page'])) {
                $res['page'] = 1;
                $url[1] = http_build_query($res);
            }
        }
        return !empty($url[1]) ? '?' . $url[1] : '';
    }

    /** пересчет начального/конечного сальдо
     */
    public static function getSaldo(&$planRows, &$account)
    {
        foreach ($planRows as $k => $planRow) {
            if ($planRow['code'] == $account->dt) {
                if (isset($planRows[$k + 1]) && $planRows[$k + 1]['code'] == $account->ct) {
                    $u = explode('?',$planRows[$k]['url']);
                    $col = $account->dt_type == 1 ? 1 : 0;
                    $planRows[$k]['url'] .= count($u)>1 ?'&saldo=start':'?saldo=start';
                    $planRows[$k]['items'][$col] = $account->amount;
                    $planRows[$k]['items'][$col + 4] = $account->amount + $planRows[$k]['items'][$col + 2] - $planRows[$k]['items'][$col + 3];

                    $col = $account->ct_type == 1 ? 1 : 0;
                    $planRows[$k + 1]['url'] .= count($u)>1 ?'&saldo=start':'?saldo=start';
                    $planRows[$k + 1]['items'][$col] = $account->amount;
                    $planRows[$k + 1]['items'][$col + 4] = $account->amount + $planRows[$k + 1]['items'][$col + 2] - $planRows[$k + 1]['items'][$col + 3];
                    break;
                }
            }
        }
    }

    /** проверка на существование оборота
     */
    public static function exist(&$planRows, &$account)
    {
        foreach ($planRows as $k => $planRow) {
            if ($planRow['code'] == $account->dt) {
                if (isset($planRows[$k + 1]) && $planRows[$k + 1]['code'] == $account->ct) {
                    return true;
                }
            }
        }
        return false;
    }


}
