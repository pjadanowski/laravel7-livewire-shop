<?php




if (!function_exists('formatPrice')) {

    function formatPrice($price)
    {
        return number_format($price / 100, 2, ",", " ");
    }
}


if (!function_exists('formatPriceWithCurrency')) {

    function formatPriceWithCurrency($price)
    {
        return number_format($price, 2, ',',' ');
    }

}
