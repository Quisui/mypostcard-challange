<?php

namespace App\Services\Api\V1\GenerateDocument;


class GenerateDocument
{
    public function generateDocument(string $documentType, $files, $options = [])
    {
        $class = config('filereader.' . $documentType);
        return (new $class)->generateFileDocument($files, ['type' => 'images'], $options);
    }
}
