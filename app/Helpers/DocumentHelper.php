<?php

namespace App\Helpers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentHelper
{
    const pageLimit = 15;

    public static function getCount($options)
    {
        $company_ids = implode(',', $options['company_ids']);
        if (empty($company_ids)) $company_ids = 0; // должна быть 1 компания
        $result = DB::select("SELECT COUNT(id) as cnt
            FROM (
                SELECT p.id
                FROM products p
                WHERE p.status!=9 AND p.owner={$options['owner']} AND p.company_id IN({$company_ids})  " . self::getCondition('p', $options['where']) . self::getCondition('p', $options['search']) .
            " UNION ALL
                SELECT d.id
                FROM contracts d
                WHERE d.status!=9 AND d.owner={$options['owner']} AND d.company_id IN({$company_ids})  " . self::getCondition('d', $options['where']) . self::getCondition('d', $options['search']) .
            " UNION ALL
                SELECT g.id
                FROM guarants g
                WHERE g.status!=9 AND g.owner={$options['owner']} AND g.company_id IN({$company_ids})  " . self::getCondition('g', $options['where']) . self::getCondition('g', $options['search']) .
            " UNION ALL
                SELECT a.id
                FROM acts a
                WHERE a.status!=9 AND a.owner={$options['owner']} AND a.company_id IN({$company_ids})  " . self::getCondition('a', $options['where']) . self::getCondition('a', $options['search']) .
            " UNION ALL
                SELECT f.id
                FROM docs f
                WHERE f.status!=9 AND f.owner={$options['owner']} AND f.company_id IN({$company_ids})  " . self::getCondition('f', $options['where']) . self::getCondition('f', $options['search']) .
            ") AS res");
        return !empty($result[0]) ? $result[0]->cnt : 0;
    }

    public static function getData(Request $request, $options)
    {
        $sort = QueryHelper::getSort($request);
        $page = $request->has('page') ? ($request->page - 1) * self::pageLimit : 0;
        $company_ids = implode(',', $options['company_ids']);
        if (empty($company_ids)) $company_ids = 0; // должна быть 1 компания
        $query = "SELECT id,number,contract_date,contract_number,company_inn,contragent,contragent_company,doc_status,created_at,doctype,company_name,date ,owner,amount, quantity,parties_owner
            FROM (
                SELECT p.id,p.NUMBER,con.contract_date,con.contract_number ,p.company_inn,p.partner_inn as contragent,p.partner_company_name as contragent_company,p.doc_status,p.created_at,'product' AS doctype,p.company_name,p.date,p.owner,SUM(ppi.amount) as amount, SUM(ppi.quantity) as quantity,p.parties_owner
                FROM products p
                left JOIN contracts con ON con.id=p.contract_id
                left JOIN product_items ppi ON ppi.product_id=p.id
                WHERE p.status!=9 AND p.owner={$options['owner']} AND p.company_id IN({$company_ids}) " . self::getCondition('p', $options['where']) . self::getCondition('p', $options['search']) .
            " GROUP by p.id, con.contract_number UNION ALL
                SELECT d.id, d.contract_number AS NUMBER,d.contract_date,d.contract_number,d.company_inn, d.partner_inn as contragent,d.partner_company_name as contragent_company,d.doc_status,d.created_at,'contract' AS doctype,d.company_name,d.contract_date AS date,d.owner,SUM(ci.amount) as amount, SUM(ci.quantity) as quantity,d.parties_owner
                FROM contracts d
                left JOIN contract_items ci ON ci.contract_id=d.id
                WHERE d.status!=9 AND d.owner={$options['owner']} AND d.company_id IN({$company_ids}) " . self::getCondition('d', $options['where']) . self::getCondition('d', $options['search']) .
            " GROUP by d.id UNION ALL
                SELECT g.id,g.guarant_number AS NUMBER,con2.contract_date,con2.contract_number,g.company_inn, g.partner_inn as contragent,g.partner_company_name AS contragent_company,g.doc_status,g.created_at ,'guarant' AS doctype ,g.company_name,g.guarant_date AS date,g.owner,SUM(gi.amount) as amount, SUM(gi.quantity) as quantity,g.parties_owner
                FROM guarants g
                left JOIN contracts con2 ON con2.id=g.contract_id
                left JOIN guarant_items gi ON gi.guarant_id=g.id
                WHERE g.status!=9 AND g.owner={$options['owner']} AND g.company_id IN({$company_ids})  " . self::getCondition('g', $options['where']) . self::getCondition('g', $options['search']) .
            " GROUP by g.id, con2.contract_number UNION ALL
                SELECT a.id,a.number AS NUMBER,con3.contract_date,con3.contract_number,a.company_inn, a.partner_inn as contragent,a.partner_company_name AS contragent_company,a.doc_status,a.created_at ,'act' AS doctype ,a.company_name,a.date AS date,a.owner,SUM(ai.amount) as amount, SUM(ai.quantity) as quantity,a.parties_owner
                FROM acts a
                left JOIN contracts con3 ON con3.id=a.contract_id
                left JOIN act_items ai ON ai.act_id=a.id
                WHERE a.status!=9 AND a.owner={$options['owner']} AND a.company_id IN({$company_ids})  " . self::getCondition('a', $options['where']) . self::getCondition('a', $options['search']) .
            " GROUP by a.id, con3.contract_number UNION ALL
                SELECT d.id,d.number AS NUMBER,con.contract_date,con.contract_number,d.company_inn, d.partner_inn as contragent,d.partner_company_name AS contragent_company,d.doc_status,d.created_at ,'doc' AS doctype ,d.company_name,d.date AS date,d.owner,SUM(di.amount) as amount, SUM(di.quantity) as quantity,d.parties_owner
                FROM docs d
                left JOIN contracts con ON con.id=d.contract_id
                left JOIN doc_items di ON di.doc_id=d.id
                WHERE d.status!=9 AND d.owner={$options['owner']} AND d.company_id IN({$company_ids})  " . self::getCondition('d', $options['where']) . self::getCondition('d', $options['search']) .
            " GROUP by d.id, con.contract_number
                ) AS res
                ORDER BY res.{$sort['column']} {$sort['direction']} LIMIT {$page}," . self::pageLimit;

        $data = DB::select($query);

        return $data;
    }

    public static function getCondition($alias, $where)
    {
        if (!$where) return '';
        return ' AND ' . $alias . '.' . $where['column'] . $where['op'] . $where['value'];
    }

    public static function getMenuItems($currentMenuType)
    {

        $menuItems = [];
        switch ($currentMenuType) {
            case 'guarant':
                $menuItems[0] = ['title' => __('main.' . $currentMenuType),'type'=>'guarant'];
                $menuItems[1] = ['title' => __('main.contract'), 'url' => localeRoute('frontend.profile.modules.contract.create')];
                $menuItems[2] = ['title' => __('main.factura'), 'url' => localeRoute('frontend.profile.modules.product.create')];
                $menuItems[3] = ['title' => __('main.act'), 'url' => localeRoute('frontend.profile.modules.act.create')];
                $menuItems[4] = ['title' => __('main.doc'), 'url' => localeRoute('frontend.profile.modules.doc.create')];
                break;
            case 'factura':
                $menuItems[0] = ['title' => __('main.' . $currentMenuType),'type'=>'factura'];
                $menuItems[1] = ['title' => __('main.contract'), 'url' => localeRoute('frontend.profile.modules.contract.create')];
                $menuItems[2] = ['title' => __('main.guarant'), 'url' => localeRoute('frontend.profile.modules.guarant.create')];
                $menuItems[3] = ['title' => __('main.act'), 'url' => localeRoute('frontend.profile.modules.act.create')];
                $menuItems[4] = ['title' => __('main.doc'), 'url' => localeRoute('frontend.profile.modules.doc.create')];
                break;
            case 'contract':
                $menuItems[0] = ['title' => __('main.' . $currentMenuType),'type'=>'contract'];
                $menuItems[1] = ['title' => __('main.factura'), 'url' => localeRoute('frontend.profile.modules.product.create')];
                $menuItems[2] = ['title' => __('main.guarant'), 'url' => localeRoute('frontend.profile.modules.guarant.create')];
                $menuItems[3] = ['title' => __('main.act'), 'url' => localeRoute('frontend.profile.modules.act.create')];
                $menuItems[4] = ['title' => __('main.doc'), 'url' => localeRoute('frontend.profile.modules.doc.create')];
                break;
            case 'act':
                $menuItems[0] = ['title' => __('main.' . $currentMenuType),'type'=>'act'];
                $menuItems[1] = ['title' => __('main.factura'), 'url' => localeRoute('frontend.profile.modules.product.create')];
                $menuItems[2] = ['title' => __('main.guarant'), 'url' => localeRoute('frontend.profile.modules.guarant.create')];
                $menuItems[3] = ['title' => __('main.contract'), 'url' => localeRoute('frontend.profile.modules.contract.create')];
                $menuItems[4] = ['title' => __('main.doc'), 'url' => localeRoute('frontend.profile.modules.doc.create')];
                break;
            case 'doc':
                $menuItems[0] = ['title' => __('main.' . $currentMenuType),'type'=>'doc'];
                $menuItems[1] = ['title' => __('main.factura'), 'url' => localeRoute('frontend.profile.modules.product.create')];
                $menuItems[2] = ['title' => __('main.guarant'), 'url' => localeRoute('frontend.profile.modules.guarant.create')];
                $menuItems[3] = ['title' => __('main.act'), 'url' => localeRoute('frontend.profile.modules.act.create')];
                $menuItems[4] = ['title' => __('main.contract'), 'url' => localeRoute('frontend.profile.modules.contract.create')];
                break;
        }

        return $menuItems;
    }

    /** получить документы из очереди */
    public static function getQueueDocuments($documents)
    {

        $documentsQueues = null;
        foreach ($documents as $id => $document) {
            $documentsQueues[$id] = json_decode($document->params, true);
            $documentsQueues[$id]['company_name'] = $document->company->name;
            $documentsQueues[$id]['contragent_company'] = $documentsQueues[$id]['partner_company_name'];
            $documentsQueues[$id]['contragent'] = $documentsQueues[$id]['partner_inn'];
            $documentsQueues[$id]['status'] = 0;

            switch ($document->doctype) {
                case '002': // factura
                    $documentsQueues[$id]['doctype'] = 'product';
                    $documentsQueues[$id]['number'] = $documentsQueues[$id]['number'];
                    $documentsQueues[$id]['date'] = $documentsQueues[$id]['date'];
                    $info = ProductDocument::getProductItemsInfo($documentsQueues[$id]['product_items']);
                    break;
                case '007': // contract
                    $documentsQueues[$id]['doctype'] = 'contract';
                    $documentsQueues[$id]['number'] = $documentsQueues[$id]['contract_number'];
                    $documentsQueues[$id]['date'] = $documentsQueues[$id]['contract_date'];
                    $info = ContractDocument::getProductItemsInfo($documentsQueues[$id]['product_items']);
                    break;
                case '006': // guarant
                    $documentsQueues[$id]['doctype'] = 'guarant';
                    $documentsQueues[$id]['number'] = $documentsQueues[$id]['guarant_number'];
                    $documentsQueues[$id]['date'] = $documentsQueues[$id]['guarant_date'];
                    $info = GuarantDocument::getProductItemsInfo($documentsQueues[$id]['product_items']);
                    break;
                case '005': // act
                    $documentsQueues[$id]['doctype'] = 'act';
                    $documentsQueues[$id]['number'] = $documentsQueues[$id]['number'];
                    $documentsQueues[$id]['date'] = $documentsQueues[$id]['date'];
                    $info = ActDocument::getProductItemsInfo($documentsQueues[$id]['product_items']);
                    break;
                case '000': // doc
                    $documentsQueues[$id]['doctype'] = 'doc';
                    $documentsQueues[$id]['number'] = $documentsQueues[$id]['number'];
                    $documentsQueues[$id]['date'] = $documentsQueues[$id]['date'];
                    $info = null;
                    break;
            }
            $documentsQueues[$id]['quantity'] = $info['quantity'] ?? 0;
            $documentsQueues[$id]['amount'] = $info['amount'] ?? 0;
        }
        return $documentsQueues;

    }

    public static function getSearchCondition($request)
    {
        // TODO возможно нужно поменять компанию и контрагента
        if ($request->has('q')) {
            $q = $request->get('q');
            if ($request->owner == 'outgoing') { // swap
                $field = is_numeric($q) ? 'partner_inn' : 'partner_company_name';
            } else {
                $field = is_numeric($q) ? 'company_inn' : 'company_name';
            }
            return ['column' => $field, 'op' => ' LIKE ', 'value' => "'%{$request->get('q')}%'"];
        }
        return null;
    }

    public static function getOptions(&$request, $draft = false)
    {
        $company_id = Company::getCurrentCompanyId();
        $op = $draft ? '=' : '!=';
        $options = [];
        $options['owner'] = $request->owner == 'incoming' ? 0 : 1;
        $options['where'] = ['column' => 'doc_status', 'op' => $op, 'value' => 0];
        $options['search'] = self::getSearchCondition($request);
        $options['company_ids'] = $company_id ? [$company_id] : Company::getMyCompaniesIds();
        if (empty($options['company_ids'])) $options['company_ids'] = [0];
        return $options;
    }

    /** получение компании/контраегнта */
    public static function isMyCompany(&$document,$myCompaniesInn){
        $result = false;
        if(in_array($document->company_inn, $myCompaniesInn) ){
            if(in_array($document->contragent, $myCompaniesInn)){
                if ($document->owner == 0) {
                    $result = false;
                } else {
                    $result = true;
                }
            }else{
                $result = true;
            }

        }elseif(in_array($document->contragent, $myCompaniesInn)){
            $result = false;
        }
        return $result;
    }

    public static function getDocumentText($response)
    {
        $result = json_decode($response);
        if (!empty($result->document_json->parts)) {
            foreach ($result->document_json->parts as $part) {
                $text[] = $part->title . "\n" . $part->body;
            }
            return implode("\n", $text);
        }
        return '';

    }

}
