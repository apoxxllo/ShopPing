<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable =[
        'fromUser_id',
        'toUser_id',
        'description',
        'orderNumber'
    ];
}
