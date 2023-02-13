<?php

namespace App\Services\Api\V1;

use Illuminate\Support\Facades\Http;

trait ExternalRequestService
{
    public function getExternalDesignRequestData(string $url, array $options = [])
    {
        $response = Http::get($url, $options ?? []);
        $response->throwIf(fn ($response) => true);
        return $response->json($key = null, $default = null);
    }
}
