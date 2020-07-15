<?php


namespace Cart;


use App\Facades\CartFacade;
use App\Models\Cart\Coupon;
use App\Models\Product;
use Illuminate\Support\Str;

trait CartFactory
{
    protected function addItemToCart($item = null, $quantity = 1) {
        $product  = $item != null ? $item : $this->generateProduct();

        return CartFacade::add($product, $product->price, $quantity);
    }

    protected function addDefaultItemToCart($quantity = 1) {
        $product  = $this->generateProduct();

        return CartFacade::add($product, $product->price, $quantity);
    }

    protected function addItemsToCart($amount = 5)
    {

        foreach (range(1, $amount) as $i) {
            $this->addDefaultItemToCart();
        }

        return CartFacade::content();
    }

    public function generateProduct($id = 10, $name = 'Testowy Product', $price = 1999)
    {
        return factory(Product::class)->make([
            'id' => $id,
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $price,
        ]);
    }

    protected function addCoupon(Coupon $coupon = null)
    {
        $coupon = is_null($coupon) ? $this->generateCoupon() : $coupon;
        return CartFacade::addCoupon($coupon);
    }

    protected function generateCoupon($code = 'ABC', $type = 'fixed', $value = 3000)
    {
        return factory(Coupon::class)->make([
            'code' => $code,
            'type' => $type,
            'value' => $value,
        ]);
    }
}
