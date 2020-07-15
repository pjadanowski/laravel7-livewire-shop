<?php

namespace Tests\Feature\Cart;

use App\Facades\CartFacade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Unit\Cart\CartFactory;

class CartTest extends TestCase
{
    use CartFactory, RefreshDatabase;

    public function testAddItemToCart()
    {
        $this->addDefaultItemToCart();

        $this->assertEquals(1, CartFacade::count());

        $this->addDefaultItemToCart();

        $this->assertEquals(2, CartFacade::count());
    }
}
