<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Shop;
use App\Models\Order;
use App\Models\ShopReviews;
use App\Models\FavoriteShop;
use App\Models\FavoriteProduct;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'firstName',
        'lastName'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function hasRole($roles)
    {
        return null !== $this->role()->whereIn('roleName', (array) $roles)->first();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favoriteShops()
    {
        return $this->hasMany(FavoriteShop::class);
    }

    public function favoriteProducts()
    {
        return $this->hasMany(FavoriteProduct::class);
    }

    public function shopReviews()
    {
        return $this->hasMany(ShopReviews::class);
    }
}
