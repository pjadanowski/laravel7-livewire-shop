<div class="card-body bg-white shadow-sm">
    <h5>Cart content</h5>
{{--    {{collect($cartContent)}}--}}
    @foreach(collect($cartContent) as $item)
        <p>{{ $item->product->name }} | {{ $item->quantity }}</p>
{{--        <p>{{ $item['product']['name'] }} | {{ $item['quantity'] }}</p>--}}
    @endforeach
    <hr>
    <h4>Total: {{ \Cart::total() }} €</h4>
</div>
