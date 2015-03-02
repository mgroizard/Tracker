<?php

namespace Aleste\TrackerBundle\Util;

use Symfony\Component\HttpKernel\Kernel;

class PdfService
{
    private $pdf;
    private $kernel;

    public function __construct($pdf, Kernel $kernel)
    {
        $this->pdf = $pdf;
        $this->kernel = $kernel;
    }

    public function getPdf($htmlContent, $outputFileName, $orientacion = 'p', $outputType = 'D')
    {
        $pdf = $this->pdf->create($orientacion);

        $pdf->SetCreator('Unidad Ejecutora Central');
        $pdf->SetAuthor("Sistema de Gestión de Documentación y Expedientes");
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 12));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetHeaderData('ministerio.jpg', 60, 'Unidad Ejecutora Central', 'Sistema de Gestión de Documentación y Expedientes', array(166,166,166), array(166,166,166));

        $pdf->addPage();
        $pdf->SetFont('helvetica', '', 9);

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = 20, $htmlContent, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        // Por default lo mandamos para descargar 'D'
        $pdf->Output($outputFileName, $outputType);
    }
}
