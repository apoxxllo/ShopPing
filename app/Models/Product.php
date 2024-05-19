<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Category;
use App\Models\OrderedProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'shop_id',
        'stock'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function orders()
    {
        return $this->hasMany(OrderedProduct::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }


}
