<?php


namespace App\Models\Traits;



trait HasPrice
{
    public function priceToMoney($value)
    {
        return number_format($value, 2, ',', ' ');
    }

    public function getFormattedPriceAttribute()
    {
        return $this->priceToMoney(11);
    }

}
