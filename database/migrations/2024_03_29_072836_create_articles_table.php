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
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('articles_id');
            $table->string('title');
            $table->longText('content');
            $table->json('images');
            $table->unsignedInteger('topic_id');
            $table->integer('status')->default(0);
            $table->unsignedInteger('place_id')->nullable();
            $table->unsignedInteger('festival_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('locations_id')->on('locations')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('place_id')->references('places_id')->on('places')
                ->onUpdate('cascade')->nullOnDelete();
            $table->foreign('festival_id')->references('festivals_id')->on('festivals')
                ->onUpdate('cascade')->nullOnDelete();
            $table->foreign('user_id')->references('users_id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('topic_id')->references('topics_id')->on('topics')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
