<?php
namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfHelper
{

    public static function create($params,$save=false){

        PDF::setOption(['dpi' => 300, 'defaultFont' => 'DejaVu Sans', 'default_paper_orientation' => $params['orientation']]);

        $pdf = PDF::loadView($params['view'], $params['data']);

        $pdf->setPaper('a4', $params['orientation'])->setWarnings(false);

        if($save) {
            $pdf->save($params['filename']);
            return $params['filename'];
        }

        return $pdf->stream($params['filename']);

    }


}
