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
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('carts_id');
            $table->integer('quantity')->default(1);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('product_id');

            $table->foreign('user_id')->references('users_id')->on('users')
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
        Schema::dropIfExists('carts');
    }
};
