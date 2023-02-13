<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Services\Api\V1\ExternalRequestService;
use App\Services\Api\V1\GenerateDocument\GenerateDocument;
use App\Services\Api\V1\PaginateData;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
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
        $paginatedRequest = $this->paginate($listDesigns['content'], 25, (int) request()->query('page') ?? 1);
        $currentItems = $paginatedRequest->toArray();
        return view('livewire.bootstrap-table', [
            'tableData' => $paginatedRequest,
            'tableItems' => [
                'Toggle Price',
                'Thumbnail',
                'Title',
                'Price'
            ],
            'currentItems' => $currentItems['data'],
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

    public function export(string $documentType, $items, $multiple = false)
    {
        abort_if(!in_array($documentType, ['pdf']), Response::HTTP_NOT_FOUND);
        $fileIds = [];
        if (is_array($items)) {
            foreach ($items as $key => $item) {
                $fileIds[$item['id']] = $item['id'];
            }
            $files = $this->getLocalImages($fileIds);
            return (new GenerateDocument)->generateDocument(strtolower($documentType), $files);
        } else {
            $image = $this->getLocalImages($items);
            return (new GenerateDocument)->generateDocument(strtolower($documentType), $image, ['id' => $items]);
        }
    }

    protected function getLocalImages($ids)
    {
        $images = [];
        if (is_array($ids)) {
            foreach ($ids as $key => $id) {
                $image = $id . '.jpg';
                if (Storage::disk('public')->exists($image)) {
                    $images[] = Storage::disk('public')->get($image);
                }
            }
            return $images;
        } else {
            $image = (int) $ids . '.jpg';
            abort_if(!Storage::disk('public')->exists($image), Response::HTTP_NOT_FOUND);
            return Storage::disk('public')->get($image);
        }
    }
}
