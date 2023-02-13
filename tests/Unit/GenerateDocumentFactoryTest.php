<?php

namespace Tests\Unit;

use App\Services\Api\V1\GenerateDocument\GenerateDocument;
use App\Services\Api\V1\ResizeImage\BaseResizeImage;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Intervention\Image\Facades\Image;

class GenerateDocumentFactoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDocumentCanBeGeneratedPdf()
    {
        $id = rand(1, 1000);
        $option = [
            'id' => $id,
            'imageId' => $id . '.jpg',
            'externalImage' => (new BaseResizeImage)->requestExternalImage('https://www.google.com/images/branding/googlelogo/1x/googlelogo_light_color_272x92dp.png'),
        ];
        (new BaseResizeImage)->saveInternalImage($option);
        $image = Storage::get($option['imageId']);
        $imageOriginalName = $option['imageId'];
        $imageInternalPath = (new BaseResizeImage)->getImagePath($imageOriginalName);
        $image = Image::cache(function ($image) use ($imageInternalPath) {
            $image->make($imageInternalPath)->resize(200, 200);
        });
        (new GenerateDocument)->generateDocument('pdf', $image, ['id' => $option['id'], 'imageGenerated' => $image]);
        $path = public_path('app') . '/testImage.pdf';
        $this->assertTrue(file_exists($path));
    }
}
