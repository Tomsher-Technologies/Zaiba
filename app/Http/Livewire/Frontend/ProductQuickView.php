<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Livewire\Component;

class ProductQuickView extends Component
{
    public $showLoading = true;
    public $product;

    protected $listeners = ['showQuickViewModal' => 'showModal'];

    public function mount()
    {
        $this->showLoading = true;
    }

    public function showModal($id)
    {
        $this->showLoading = true;
        $this->product = null;
        $this->product = Product::with(['brand'])->find($id);
        $this->showLoading = false;
    }

    public function render()
    {
        return view('livewire.frontend.product-quick-view');
    }
}
