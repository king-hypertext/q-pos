<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "address",
        "contact",
    ] ;

    public function orders(){
        return $this->hasMany(Orders::class);
    }
    // public function customers(){
    //     return $this->belongsTo(Customers::class);
    // }
}
