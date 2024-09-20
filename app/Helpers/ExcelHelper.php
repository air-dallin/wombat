<?php
namespace App\Helpers;

use App\Models\Company;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelHelper
{

    public static function create($paymentOrder){

        $len = 90;

        $file = 'report_orig.xlsx';
        $inputFileName = 'report_'.$paymentOrder->vdate.'-'.$paymentOrder->num.'.xlsx';

        if (!copy($file, $inputFileName)) return '';

        $inputFileType = IOFactory::identify($inputFileName);

        $reader = IOFactory::createReader($inputFileType);

        $spreadsheet = $reader->load($inputFileName);

        $sheet = $spreadsheet->getActiveSheet();
        $vdate = date('d.m.Y',strtotime($paymentOrder->vdate));
        $sheet->setCellValue('E3', $paymentOrder->num);
        $sheet->setCellValue('C4', $paymentOrder->general_id);
        $sheet->setCellValue('C5', $vdate);
        $sheet->setCellValue('H5', date('d.m.y'));
        $sheet->setCellValue('C6', $paymentOrder->name_dt);
        $sheet->setCellValue('E6', $paymentOrder->inn_dt);
        $sheet->setCellValue('C7', $paymentOrder->acc_dt);
        $sheet->setCellValue('C8', Company::getByInn($paymentOrder->inn_dt,'bank_name'));
        $sheet->setCellValue('H8', $paymentOrder->mfo_dt);
        $sheet->setCellValue('C9', $paymentOrder->amount/100);
        $sheet->setCellValue('C11', $paymentOrder->name_ct);
        if(!empty($paymentOrder->inn_ct))     $sheet->setCellValue('E11', $paymentOrder->inn_ct);
        $sheet->setCellValue('C12', $paymentOrder->acc_ct);
        $sheet->setCellValue('C13', Company::getByInn($paymentOrder->inn_ct,'bank_name')); // адресс банка получателя
        $sheet->setCellValue('H13', $paymentOrder->mfo_ct);
        $sheet->setCellValue('C14', num2str($paymentOrder->amount/100));

        if(mb_strlen($paymentOrder->purp_code .' '. $paymentOrder->purpose)>$len){
            $row1 = '';
            $row2 = '';
            self::splitRows($paymentOrder->purpose,$len,$row1,$row2);
            $sheet->setCellValue('C15', $paymentOrder->purp_code .' '. $row1);
            $sheet->setCellValue('C16', $row2);
        }else{
            $sheet->setCellValue('C15', $paymentOrder->purp_code .' '. $paymentOrder->purpose);
        }

        $sheet->setCellValue('G23', $vdate);

        $writer = new Xlsx($spreadsheet);
        $writer->save($inputFileName);

        return $inputFileName;
    }

    private static function splitRows($str,$len,&$row1,&$row2){
        $parts = explode(' ',$str);
        $cnt=0;
        foreach($parts as $k => $part){
            $cnt+=mb_strlen($part);
            if($cnt>$len){
                $row2.= ' ' . $part;
            }else{
                $row1.= ' ' . $part;
            }
        }
    }


}
