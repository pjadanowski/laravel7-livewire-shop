<?php

namespace Tests\Unit\Cart;

use App\Facades\CartFacade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartTest extends TestCase
{
    use CartFactory, RefreshDatabase;


    public function setUp() : void
    {
        parent::setUp();

        $req = $this->app['request'];
        $sessionProp = new \ReflectionProperty($req, 'session');
        $sessionProp->setAccessible(true);
        $sessionProp->setValue($req, $this->app['session']->driver('array'));
    }

    public function testAddItemToCart()
    {
        $this->addDefaultItemToCart();

        $this->assertEquals(1, CartFacade::count());

        $this->addDefaultItemToCart();

        $this->assertEquals(2, CartFacade::count());
    }

    public function test_adds_two_different_items_to_cart()
    {
        $prod1 = $this->generateProduct();
        $prod2 = $this->generateProduct($id = 11, $name = 'Product2', $price = 1111);

        $this->addItemToCart($prod1);
        $this->addItemToCart($prod2);

        $this->assertCount(2, CartFacade::content());

    }

    public function testRemoveItemFromCart()
    {
        $this->addItemsToCart(5);

        CartFacade::remove(10);

        $this->assertCount(0, CartFacade::content());
    }

    public function testCountMethod()
    {
        $this->addItemsToCart(5);
        $this->addDefaultItemToCart(5);

        $this->assertEquals(10, CartFacade::count());
    }

    public function testTotalMethod()
    {
        $this->addItemsToCart(5);

        $this->assertEquals(5 * 1999, CartFacade::total());
    }

    public function testTotalWithCouponMethod()
    {
        $this->addItemsToCart(5);
        $this->addCoupon(); // by default fixed 30€ off

        $this->assertEquals(5 * 1999 - 3000, CartFacade::totalWithCoupons());
    }

    public function testTotalWithPercentageCoupon()
    {
        $this->addItemsToCart(5);
        $coupon = $this->generateCoupon('percentage', 'percent', 10);
        $this->addCoupon($coupon);

        $this->assertEqualsWithDelta(round(0.9*(5 * 1999)), CartFacade::totalWithCoupons(), 0.1);
    }
}
