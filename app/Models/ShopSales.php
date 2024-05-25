<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSales
{
    public $shopName;
    public $sales;

    public function __construct($shopName, $sales)
    {
        $this->shopName  = $shopName;
        $this->sales = $sales;
    }
}
