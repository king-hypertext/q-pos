<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInvoices extends Model
{
    use HasFactory;
    protected $table = "customer_invoices";
    protected $fillable = [
        "customer_id",
        "product",
        "quantity",
        "price",
        "amount"
    ];
}
