<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_stock', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_number')->unique();
            $table->bigInteger('invoice_number');
            $table->longText('order_token');
            $table->integer('supplier_id');
            $table->string('supplier');
            $table->string('category');
            $table->string('product');
            $table->integer('quantity');
            $table->decimal('price');
            $table->decimal('amount');
            $table->string('day');
            $table->string('invoice_time');
            $table->date('created_at');
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_stock');
    }
};
