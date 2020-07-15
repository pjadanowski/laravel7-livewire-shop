<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;

class Product extends Component
{
    public $product;


    public function mount($product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.shop.product');
    }

    public function addToCart()
    {
        \Cart::add($this->product, $this->product->price, 1);

        $this->emit('refreshCartContent');
    }
}
