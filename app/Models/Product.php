<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{

    use HasPrice;

    protected $fillable = [
        'name', 'slug', 'price', 'discount_price', 'short_description', 'description', 'quantity', 'priority',
        'weight', 'size', 'image', 'woocommerce_id', 'category_id'
    ];

    //Make it available in the json response
    protected $appends = [
//        'show'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('priority', 'desc');
        });
    }

//    public function category()
//    {
//        return $this->belongsTo(Category::class);
//    }

//    public function images()
//    {
//        return $this->hasMany('App\Image');
//    }

//    public function img()
//    {
//        return $this->images->where('is_main', true)->first();
//    }

//    public function imgSrc()
//    {
//        $img = $this->img();
//        if ($img) {
//            return $img->name;
//        }
//        return asset('/img/placeholder.png');
//    }



    public function scopePrice($query, $priceRangeArray)
    {
        return $query->whereBetween('price', $priceRangeArray)
            ->orWhereBetween('discount_price', $priceRangeArray);
    }


    public function isOnSale()
    {
        return $this->discount_price > 0 && $this->discount_price != null;
    }


    public function formatPrice()
    {
        return number_format($this->price , 2, ',', '.') . env('CURRENCY_SYMBOL', "€");
    }

    public function formatDiscountPrice()
    {
        return number_format($this->discount_price , 2, ',', '.') . ' ' . env('CURRENCY_SYMBOL', "€");
    }


    /**
     * returns discount_price or regular as int
     */
    public function getValue()
    {
        return $this->isOnSale() ? $this->discount_price : $this->price;
    }


    public function show()
    {
        return route('products.show', $this->slug);
    }

    public function getShowAttribute()
    {
        return $this->show();
    }


    public function getImgSrcAttribute()
    {
        return $this->imgSrc();
    }


    public function quantityColor()
    {
        if ($this->quantity > 5) return 'badge-soft-success';
        if ($this->quantity <= 5 && $this->quantity > 1) return 'badge-soft-warning';
        if ($this->quantity < 1) return 'badge-soft-danger';
        return 'badge-soft-primary';
    }
}
