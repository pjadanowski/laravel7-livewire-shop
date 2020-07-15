<?php


namespace App\Facades;


use App\Models\Cart\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CartItem add(Product $product, int $price, int $quantity = 1, $options = [])
 * @method static int totalWithCoupons()
 *
 * @see \App\Models\Cart\Cart
 */
class CartFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'cart';
    }
//    protected static function resolveFacade($name)
//    {
//        return app()[$name];
//    }
//
//    public static function __callStatic($method, $args)
//    {
//        return (self::resolveFacade('cart'))->$method(...$args);
//    }
}
