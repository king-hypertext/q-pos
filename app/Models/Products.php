<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = [
        "product_name",
        "price",
        "quantity",
        "batch_number",     
        "supplier",
        "prod_date",
        "expiry_date", 
        "date_time",
    ];
    
}
