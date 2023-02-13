<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Services\Api\V1\ExternalRequestService;
use App\Services\Api\V1\PaginateData;
use Livewire\Component;
use Livewire\WithPagination;

class BootstrapTable extends Component
{
    use WithPagination;
    use ExternalRequestService;
    use PaginateData;

    public array $active = [];
    public array $activePrices = [];

    public function render()
    {
        //Url hardcoded by code challenge
        $listDesigns = $this->getExternalDesignRequestData('https://appmsds-6aa0.kxcdn.com/content.php', [
            'lang' => 'de',
            'json' => 1,
            'search_text' => 'berlin',
            'currencyiso' => 'EUR'
        ]);

        return view('livewire.bootstrap-table', [
            'tableData' => $this->paginate($listDesigns['content'], 25, (int) request()->query('page') ?? 1),
            'tableItems' => [
                'Toggle Price',
                'Thumbnail',
                'Title',
                'Price'
            ]
        ]);
    }

    public function toggleShowPriceV2(int $cardItemID)
    {
        $priceV2 = $this->getExternalDesignRequestData('https://www.mypostcard.com/mobile/product_prices.php', [
            'json' => 1,
            'type' => 'get_postcard_products',
            'currencyiso' => 'EUR',
            'store_id' => $cardItemID,
        ]);
        $productPrices = [];

        foreach ($priceV2['products'] as $key => $product) {
            if ($product['assignedtype'] === 'Greetcard') {
                foreach ($product['product_options'] as $key => $envelopePrice) {
                    if ($envelopePrice['name'] === 'Greetcard_Envelope') {
                        $productPrices[str_replace('_', ' ', $envelopePrice['name'])] = $envelopePrice['price'];
                    }
                }
            }
        }
        $this->activePrices[$cardItemID] = ['prices' => $productPrices];
    }
}
