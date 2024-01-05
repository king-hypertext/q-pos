<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoices extends Model
{
    use HasFactory;
    protected $table = "supplier_invoices";
    protected $fillable =[
        "supplier_id",
        "product",
        "quantity",
        "price",
        "amount"
    ];
}
