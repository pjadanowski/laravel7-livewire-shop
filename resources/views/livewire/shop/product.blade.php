<div class="bg-white shadow-sm p-3">
    <h5>{{ $product->name }}</h5>
    <p>
        {{ $product->price }}
    </p>

    <button class="btn btn-outline-primary" wire:click="addToCart">
        Add to cart
    </button>
</div>
