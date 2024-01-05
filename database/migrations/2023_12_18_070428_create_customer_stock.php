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
        Schema::create('customer_stock', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_number')->unique();
            $table->longText('order_token');
            $table->integer('customer_id');
            $table->string('customer');
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
        Schema::dropIfExists('customer_stock');
    }
};
