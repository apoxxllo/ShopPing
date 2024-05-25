<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FavoriteShop extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'shop_id'
    ];
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
