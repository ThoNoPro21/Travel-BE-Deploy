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
            $table->increments('orders_id');
            $table->string('address');
            $table->string('phone_number');
            $table->integer('total_amount')->default(0);
            $table->string('note')->nullable();
            $table->integer('status')->default(0);
            $table->unsignedInteger('user_id');

            $table->foreign('user_id')->references('users_id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
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