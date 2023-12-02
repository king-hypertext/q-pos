<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    public function customers(){
        return $this->belongsTo(Customers::class);
    }
    public function products(){
        return $this->hasMany(Products::class);
    }
}
