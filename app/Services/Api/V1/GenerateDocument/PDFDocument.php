<?php

namespace App\Services\Api\V1\GenerateDocument;

use App\Services\Api\V1\ResizeImage\BaseResizeImage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use TCPDF;

class PDFDocument implements FileReaderInterface
{
    public function getFileDocument($file)
    {
    }

    public function generateFileDocument($file, $options = [], $extraOptions = [])
    {
        if (!empty($options)) {
            if ($options['type'] === 'images') {
                $this->placeImageInPdf($file, $options, $extraOptions);
            }
        }
    }

    public function downloadDocument($file)
    {
    }

    protected function placeImageInPdf($images, $options = [], $extraOptions = [])
    {
        if (!is_array($images)) {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Carlos Alvarado');
            $pdf->SetTitle('Image PDF Generator');
            $pdf->SetSubject('Code Challange');
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 009', PDF_HEADER_STRING);

            $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->AddPage();
            if (isset($extraOptions['id'])) {
                if (app()->environment('testing')) {
                    $image = $extraOptions['imageGenerated'];
                } else {
                    $image = (new BaseResizeImage)->resizeImage('', [
                        'imageId' => $extraOptions['id'] . '.jpg',
                        'height' => 200,
                        'width' => 200,
                    ]);
                }
            }
            $pdf->Image('@' . $image ?? $images);
            $filename = 'testImage.pdf';
            $path = public_path('app') . '/' . $filename;
            $pdf->Output($path, 'F');
        }
    }
}
