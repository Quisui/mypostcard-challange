<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ImageComponent extends Component
{
    public $dataItem;

    public function mount($dataItem)
    {
        $this->dataItem = $dataItem;
    }

    public function render()
    {
        return view('livewire.image-component', ['dataItem' => $this->dataItem]);
    }
}
