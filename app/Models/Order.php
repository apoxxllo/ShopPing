<?php

namespace App\Models;

use App\Models\User;
use App\Models\OrderedProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'orderNumber',
        'user_id',
        'details',
        'total',
        'address',
        'payment',
        'status',
        'estimateDate'
    ];
    public function ordered()
    {
        return $this->hasMany(OrderedProduct::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
