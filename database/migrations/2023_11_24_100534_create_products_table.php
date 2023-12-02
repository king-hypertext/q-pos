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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price');
            $table->integer('quantity')->nullable()->default(0);
            $table->string('quantity_note')->nullable()->default('');
            $table->string('batch_number')->nullable()->default('null');
            $table->string('supplier');
            $table->string('image')->nullable()->default('');
            $table->date('prod_date')->nullable();
            $table->date('expiry_date');
            $table->date('created_at');
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
