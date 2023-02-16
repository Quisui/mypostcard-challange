<?php

namespace Tests\Unit;

use App\Services\Api\V1\ResizeImage\BaseResizeImage;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageResizeLogicTest extends TestCase
{
    use RefreshDatabase;
    
    public function testImageCannotBeSaved()
    {
        $this->expectExceptionMessage('Error Processing Request, missing values = [imageId?, externalImage?]');
        $this->expectExceptionCode(400);
        (new BaseResizeImage)->saveInternalImage([]);
    }

    public function testImageCanBeSaved()
    {
        $option = [
            'imageId' => rand(1, 1000) . '.jpg',
            'externalImage' => (new BaseResizeImage)->requestExternalImage('https://www.google.com/images/branding/googlelogo/1x/googlelogo_light_color_272x92dp.png'),
        ];
        (new BaseResizeImage)->saveInternalImage($option);
        $this->assertTrue(Storage::exists($option['imageId']));
    }

    public function testImageResizedMethodValidation()
    {
        $this->expectExceptionMessage('Error Processing Request, missing values = [imageId?]');
        $this->expectExceptionCode(400);
        (new BaseResizeImage)->resizeImage('', []);
    }

    public function testImageCanBeResized()
    {
        $option = [
            'imageId' => rand(1, 1000) . '.jpg',
            'externalImage' => (new BaseResizeImage)->requestExternalImage('https://www.google.com/images/branding/googlelogo/1x/googlelogo_light_color_272x92dp.png'),
        ];
        (new BaseResizeImage)->saveInternalImage($option);
        $option['height'] = 200;
        $option['width'] = 200;
        $response = (new BaseResizeImage)->resizeImage('', $option);
        $this->assertTrue($response);
    }
}
