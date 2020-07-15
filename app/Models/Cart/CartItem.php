<?php


namespace App\Models\Cart;


use App\Http\Resources\CartItemResource;
use App\Models\Product;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class CartItem implements Arrayable, Jsonable
{

    /**
     * The unique identifier of the cart item and its options.
     *
     * Used to identify shopping cart items with the same id, but with different
     * options (e.g. different color).
     *
     * @var string
     */
    private $uniqueId;

    /**
     * The ID of the cart item.
     *
     * @var int|string
     */
    public $id;
    /**
     * The quantity for this cart item.
     *
     * @var int|float
     */
    public $quantity;


    /**
     * The price without TAX of the cart item.
     *
     * @var int eg. 19.99€ = 1999
     */
    public $price;
    /**
     * The options for this cart item.
     *
     * @var array
     */
    public $options;

    /**
     * Actual model for the item
     */
    public $product;


    /**
     * CartItem constructor.
     * @param Product $product
     * @param $price
     * @param int $quantity
     * @param array $options
     */
    public function __construct(Product $product, int $price, int $quantity = 1, $options = [])
    {
        $this->id = $product->id;
        $this->product = $product;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->options = $options;

        $this->uniqueId = $this->generateUniqueId();
    }


    public function toArray()
    {
        return [
            'id' => $this->id,
            'product' => new CartItemResource($this->product),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'options' => !is_array($this->options) ? $this->options->toArray() : $this->options,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode(!is_array($this) ? $this->toArray() : $this, $options);
    }


    public function show()
    {
        return route('products.show', optional($this->options)->slug);
    }

    private function generateUniqueId()
    {
        return md5($this->product->name . serialize($this->options));
    }

    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    public function getTotal()
    {
        return $this->price * $this->quantity;
    }
}
