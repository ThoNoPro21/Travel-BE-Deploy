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
        Schema::create('place_reviews', function (Blueprint $table) {
            $table->increments('place_reviews_id');
            $table->string('content', 500);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('place_id');

            $table->foreign('user_id')->references('users_id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('place_id')->references('places_id')->on('places')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_reviews');
    }
};
