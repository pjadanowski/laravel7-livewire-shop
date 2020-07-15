@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-10 order-2 oder-md-1">
                <div class="row">
                    @foreach(\App\Models\Product::all() as $product)
                    <div class="col-md-3 mb-4">
                        <livewire:shop.product :product="$product" />
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-md-2 flex-nowrap order-1 order-md-2 mb-3">
                <livewire:shop.cart />
            </div>
        </div>
    </div>
@endsection
