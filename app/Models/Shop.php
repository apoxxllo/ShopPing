<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\ShopReviews;
use App\Models\FavoriteShop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = [
        'shopName',
        'address',
        'shopLogo',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function favorites()
    {
        return $this->hasMany(FavoriteShop::class);
    }

    public function reviews()
    {
        return $this->hasMany(ShopReviews::class);
    }

}
