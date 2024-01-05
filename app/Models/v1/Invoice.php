<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = "invoice";
    protected $fillable = [
        "invoice_number",
        "type",
        "for",
        "for_id",
        "token",
        "name",
        "amount"
    ];
}
