<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "price",
        "quantity",
        "batch_number",
        "image",
        "supplier",
        "prod_date",
        "expiry_date",
    ];

    function suppliers(){
        return $this->hasMany(Suppliers::class);
    }
    function orders(){
        return $this->hasMany(Orders::class);
    }
}
