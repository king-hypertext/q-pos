<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        "product_name",
        "quantity",
        "day",
        "customer_name",
        "price",
        "total",
        "date"
    ];
}
