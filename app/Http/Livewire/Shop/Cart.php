<?php

namespace App\Http\Livewire\Shop;

use App\Facades\CartFacade;
use Livewire\Component;

class Cart extends Component
{

//    public $cartContent;

    protected $listeners = [
        'refreshCartContent' => 'refreshCartContentParent'
    ];

//    public function mount()
//    {
//        $this->cartContent = CartFacade::content()->toArray();
//    }

    public function render()
    {
        return view('livewire.shop.cart', [
            'cartContent' => \Cart::content()
        ]);
    }

    public function refreshCartContentParent()
    {
//        $this->cartContent = CartFacade::content();
//        $this->render();
    }
}
