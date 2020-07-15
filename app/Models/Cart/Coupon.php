<?php


namespace App\Models\Cart;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', // fixed, percent, allows_free_shipping
        'value', 'percent_off',
        'combine_with_others', 'valid_until'
    ];

    protected $appends = [
        'is_valid'
    ];

    public static function findByCode($code)
    {
        return self::where('code', $code)->first();
    }

    public function discount($total)
    {
        if ($this->type == 'fixed') {
            return $this->value;
        } elseif ($this->type == 'percent') {
            return ($this->percent_off / 100) * $total;
        } else { return 0; }
    }

    public function off($total)
    {
        $discount = $this->discount($total);
        if ($discount > $total) return $total;
        return $discount;
    }

    // zwraca wartosc kuponu w zaleznosci od typu
    public function value()
    {
        if ($this->type == 'fixed') {
            return $this->value;
        } elseif ($this->type == 'percent') {
            return $this->percent_off;
        } else {
            return 0;
        }

    }

    public function getIsValidAttribute()
    {
        return $this->isValid();
    }

    public function isValid()
    {
        return now()->diffInMinutes(Carbon::parse($this->valid_until), false) > 0;
    }
}

