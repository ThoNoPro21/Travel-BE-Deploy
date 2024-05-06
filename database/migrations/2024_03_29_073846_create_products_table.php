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
            $table->increments('products_id');
            $table->string('name');
            $table->longText('description');
            $table->integer('price')->default(0);
            $table->integer('price_sale')->nullable()->default(0);
            $table->integer('quantity')->default(999);
            $table->json('images');
            $table->unsignedInteger('location_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();

            $table->foreign('location_id')->references('locations_id')->on('locations')
                ->onUpdate('cascade')->nullOnDelete();

            $table->foreign('category_id')->references('categories_id')->on('categories')
                ->onUpdate('cascade')->nullOnDelete();

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
