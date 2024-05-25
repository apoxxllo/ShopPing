<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShopReviews extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id',
        'user_id',
        'comment',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
