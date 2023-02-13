<?php

namespace App\Services\Api\V1\ResizeImage;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BaseResizeImage implements ResizeImagesInterface
{
    public function retrieveImage(string $url, array $options = [])
    {
        if (array_key_exists('id', $options)) {
            $imageOriginalName = $options['imageId'];
            if (!Storage::disk('public')->exists($imageOriginalName)) {
                $options['externalImage'] = $this->requestExternalImage($url);
                $this->saveInternalImage($options);
            }
            $options['height'] = 200;
            $options['width'] = 200;
            return $this->resizeImage($url, $options);
        }
    }

    public function resizeImage(string $url, array $options = [])
    {
        if (array_key_exists('imageId', $options)) {
            $imageOriginalName = $options['imageId'];
            $imageInternalPath = $this->getImagePath($imageOriginalName);
            $image = Image::cache(function ($image) use ($imageInternalPath, $options) {
                $image->make($imageInternalPath)->resize($options['height'], $options['width']);
            });
            if (!empty($image)) {
                if (app()->environment('testing')) {
                    return true;
                }
                return $image;
            }
        } else {
            throw new Exception("Error Processing Request, missing values = [imageId?]", 400);
        }
    }

    public function saveInternalImage(array $options): void
    {
        if (array_key_exists('imageId', $options) && array_key_exists('externalImage', $options)) {
            $imageOriginalName = $options['imageId'];
            $externalImage = $options['externalImage'];
            Storage::disk('public')->put($imageOriginalName, $externalImage);
        } else {
            throw new Exception("Error Processing Request, missing values = [imageId?, externalImage?]", 400);
        }
    }

    public function getImagePath(string $imageInternalPath)
    {
        return Storage::disk('public')->path($imageInternalPath);
    }

    public function requestExternalImage(string $url)
    {
        if (empty($url)) return '';
        return file_get_contents($url);
    }
}
