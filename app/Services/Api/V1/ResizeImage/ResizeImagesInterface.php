<?php

namespace App\Services\Api\V1\ResizeImage;

interface ResizeImagesInterface
{
    public function resizeImage(string $url, array $options = []);

    public function retrieveImage(string $url);

    public function requestExternalImage(string $url);

    public function saveInternalImage(array $options);
}
