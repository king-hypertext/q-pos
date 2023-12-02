<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "address",
        "contact",
        "product",
    ];

    function products(){
        return $this->belongsTo(Products::class);
    }
}
