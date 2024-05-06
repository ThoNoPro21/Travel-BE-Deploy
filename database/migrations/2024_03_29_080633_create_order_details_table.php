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
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('order_details_id');
            $table->integer('quantity');
            $table->integer('total_amount');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');

            $table->foreign('order_id')->references('orders_id')->on('orders')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('product_id')->references('products_id')->on('products')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
