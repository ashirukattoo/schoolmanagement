<?php

namespace App\Libraries;

class PdfService
{
    public function generate($html, $title = 'document.pdf', $header = '', $footer = '')
    {
        // clean buffer
        while (ob_get_level()) { ob_end_clean(); }

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 30,
            'margin_bottom' => 15
        ]);

        if ($header) {
            $mpdf->SetHTMLHeader($header);
        }

        if ($footer) {
            $mpdf->SetHTMLFooter($footer);
        }

        $mpdf->WriteHTML($html);
        $mpdf->Output($title, 'D');
        exit;
    }
}