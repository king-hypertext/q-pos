<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;
    protected $fillable = [
        "customer",
        "customer_id",
        "product",        
        "product_id",        
        "quantity",
        "price",
        "amount",
        "order_id"
    ];
}
