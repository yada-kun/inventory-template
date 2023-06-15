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
            $table->string('product_name');
            $table->string('description');
            $table->integer('price');
            $table->integer('quantity');
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('category_id');

            // $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
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
