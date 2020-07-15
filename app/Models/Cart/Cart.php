<?php

namespace App\Models\Cart;

use App\Models\Product;
use Closure;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class Cart
{

    /**
     * Default instance name.
     */
    const DEFAULT_INSTANCE_NAME = 'default';

    /**
     * Current instance name.
     *
     * User can several instances of the cart. For example, regular shopping
     * cart, wishlist, etc.
     *
     * @var string
     */
    private $instanceName;

    /**
     * Shopping cart content.
     *
     * @var Collection
     */
    private $content;

    /**
     * Coupons.
     *
     * @var Collection
     */
    private $coupons;

    /**
     * @var String
     */
    private $token;


    public function __construct()
    {
        $this->token = Str::uuid();
        $this->instance(self::DEFAULT_INSTANCE_NAME);
        $this->content = new Collection();
        $this->coupons = new Collection();
    }

    /**
     * @param Product $product ,  price : int, $quantity = 1, $options
     * @param int $price
     * @param int $quantity
     * @param array $options
     * @return CartItem
     * @throws Exception
     */
    public function add(Product $product, int $price, int $quantity = 1, $options = [])
    {
        $cartItem = new CartItem($product, $price, $quantity, $options);
        $uniqueId = $cartItem->getUniqueId();

        if ($this->content->has($uniqueId)) {
            $cartItem->quantity += $this->content->get($uniqueId)->quantity;
        }

        $this->content->put($uniqueId, $cartItem); // collection
//        $content = $this->getContent();
//        if ($content->has($uniqueId)) {
//            $cartItem->quantity += $content->get($uniqueId)->quantity;
//        }
//
//        $content->put($uniqueId, $cartItem); // collection
        $this->updateCartInSession();

        return $cartItem;
    }


    public function content()
    {
        return $this->getContent();
    }

    protected function getContent(): Collection
    {
        return $this->content = collect(session()->get('cart'));
    }


    public function remove($product_id)
    {
        $key = $this->getContentKeyByProductId($product_id);
        $this->content->forget($key);
        $this->updateCartInSession();
    }


    /**
     * @param $product_id : int
     * @return Product product from cart content by given id
     */
    public function searchProductById($product_id)
    {
        $content = $this->content();
        $id = $this->content->search(function ($item, $key) use ($product_id) {
            return $item->product->id == $product_id;
        });

        if ($id) {
            return $content[$id];
        }
        return null;
    }


    /**
     * looking for a key in collection for a given product_id
     * @param $product_id
     * @return bool|int|mixed|string $key;
     */
    public function getContentKeyByProductId($product_id)
    {
        $cartId = $this->content()->search(function ($item, $key) use ($product_id) {
            return $item->product->id == $product_id;
        });
        return $cartId;
    }


    public function get($key)
    {
        $content = $this->content();
        if (!$content->has($key))
            throw new Exception("The cart does not contain rowId = {$key}.");
        return $content->get($key);
    }


    /**
     * Get the number of items in the cart.
     *
     * @return int|float
     */

    public function count()
    {
        $content = $this->content();
        return $content->sum('quantity');
    }

    /**
     * Get the total price of the items in the cart.
     *
     */
    public function total()
    {
        return $this->totalRaw();
        // return formatPriceWithCurrency($total*100); // must be as int eg 2999 = 29.99
    }

    public function totalRaw()
    {
        return $this->getContent()->sum(function (CartItem $cartItem) {
            return $cartItem->getTotal();
        });
//        $content = $this->content();
//        $total = $content->reduce(function ($total, CartItem $cartItem) {
//            return $total + ($cartItem->quantity * $cartItem->price);
//        }, 0);
//        return $total;
    }

    public function couponsTotal($cartSubTotal)
    {
        $couponsTotal = 0;
        $this->coupons->each(function (Coupon $coupon) use ($cartSubTotal, &$couponsTotal) {
            $couponsTotal += $coupon->off($cartSubTotal);
        });
        return $couponsTotal;
    }

    public function totalWithCoupons(): int
    {
        $total = $this->total();
        $couponsTotal = $this->couponsTotal($total);

        return round($total - $couponsTotal);
    }


    /**
     * Add coupon.
     *
     * @param Coupon $coupon
     */
    public function addCoupon(Coupon $coupon) : void
    {
        $this->coupons->push($coupon);
        $this->updateSession('coupons', $this->coupons);
    }

    /**
     * Get coupons.
     *
     * @return Collection
     */
    public function getCoupons() : Collection
    {
        return $this->coupons = collect(session()->get('coupons'));
    }

    /**
     * Search the cart content for a cart item matching the given search closure.
     */
    public function search(Closure $search)
    {
        return $this->content()->filter($search);
    }


    public function clear() : void
    {
        $this->content = new Collection();
        $this->updateCartInSession();
    }


    private function numberFormat($value, $decimals, $decimalPoint, $thousandSeperator)
    {
        if (is_null($decimals)) {
            $decimals = is_null(config('cart.format.decimals')) ? 2 : config('cart.format.decimals');
        }
        if (is_null($decimalPoint)) {
            $decimalPoint = is_null(config('cart.format.decimal_point')) ? '.' : config('cart.format.decimal_point');
        }
        if (is_null($thousandSeperator)) {
            $thousandSeperator = is_null(config('cart.format.thousand_seperator')) ? ',' : config('cart.format.thousand_seperator');
        }

        return number_format($value, $decimals, $decimalPoint, $thousandSeperator) . config('cart.format.symbol');
    }

    public function instance($name)
    {
        $partialInstanceName = $name ?: self::DEFAULT_INSTANCE_NAME;

        $this->instanceName = sprintf('%s.%s', 'shopping-cart', $partialInstanceName);

        return $this;
    }

    private function updateSession(string $key, Collection $value) : void
    {
        session()->put($key, $value);
    }

    private function updateCartInSession()
    {
        $this->updateSession('cart', $this->content);
    }
}
