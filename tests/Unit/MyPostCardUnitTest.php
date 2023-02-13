<?php

namespace Tests\Unit;

use App\Services\Api\V1\ExternalRequestService;
use App\Services\Api\V1\PaginateData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyPostCardUnitTest extends TestCase
{
    use ExternalRequestService;
    use RefreshDatabase;
    use PaginateData;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCanRequestAnything()
    {
        $result = $this->getExternalDesignRequestData('https://appmsds-6aa0.kxcdn.com/content.php');
        $this->assertIsArray($result);
        $this->assertContainsEquals($result['content'][0]['title'], ['title' => 'Happy Birthday! Too old for Leo']);
    }

    public function testRequestCanHaveOptions()
    {
        $result = $this->getExternalDesignRequestData('https://appmsds-6aa0.kxcdn.com/content.php', [
            'lang' => 'de',
            'json' => 1,
            'search_text' => 'berlin',
            'currencyiso' => 'EUR'
        ]);

        $this->assertIsArray($result);
        $this->assertContainsEquals($result['content'][0]['title'], ['title' => 'Berlin – Brandenburger Tor - Grüße aus Berlin']);
    }

    public function testCanPaginateJsonArray()
    {
        $result = $this->getExternalDesignRequestData('https://appmsds-6aa0.kxcdn.com/content.php', [
            'lang' => 'de',
            'json' => 1,
            'search_text' => 'berlin',
            'currencyiso' => 'EUR'
        ]);
        $paginatedResult = $this->paginate($result, 25, 1);
        $this->assertIsObject($paginatedResult);
        $paginatedResultArray = $paginatedResult->toArray();
        $this->assertContainsEquals($paginatedResultArray['data']['results'], ['results' => 101]);
        $this->assertArrayHasKey('total', $paginatedResult->toArray());
    }
}
