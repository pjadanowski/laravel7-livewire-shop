<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'discount_price' => $this->discount_price > 0 ? formatPriceWithCurrency($this->discount_price) : "",
            'price' => formatPriceWithCurrency($this->price),
            'category' => optional($this->category)->name,
            'imgSrc' => 'img',
            'images' => [],
            'tags' => [],
        ];
    }
}

