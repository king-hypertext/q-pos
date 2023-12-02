<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $table = "customers";
    protected $fillable = [
        "customer_name",
        "gender",
        "date_of_birth",
        "contact",
        "address",
        "date_time",
    ];
}
