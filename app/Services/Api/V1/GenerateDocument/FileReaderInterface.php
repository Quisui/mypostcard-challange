<?php


namespace App\Services\Api\V1\GenerateDocument;

interface FileReaderInterface
{
    public function getFileDocument($file);

    public function generateFileDocument($file);

    public function downloadDocument($file);
}
