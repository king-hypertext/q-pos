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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_number')->unique();
            $table->longText('order_token')->unique();
            $table->integer('customer_id');
            $table->string('customer');
            $table->string('product');
            $table->integer('quantity');
            $table->integer('return_quantity')->default(0)->nullable();
            $table->decimal('price');
            $table->decimal('amount');
            $table->string('day');
            $table->date('created_at');
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
