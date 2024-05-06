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
        Schema::create('places', function (Blueprint $table) {
            $table->increments('places_id');
            $table->string('name');
            $table->json('description');
            $table->string('address', 255);
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->json('images');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('locations_id')->on('locations')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
