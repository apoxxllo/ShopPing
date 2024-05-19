<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory
{
    public $orderNumber;
    public $id;
    public $orderedProducts;
    public $status;
    public $estimateDate;
    public $total;

    public function __construct($orderNumber, $orderedProducts, $status, $estimateDate, $total, $id)
    {
        $this->orderNumber = $orderNumber;
        $this->orderedProducts = $orderedProducts;
        $this->status = $status;
        $this->estimateDate = $estimateDate;
        $this->total = $total;
        $this->id = $id;

    }
}

