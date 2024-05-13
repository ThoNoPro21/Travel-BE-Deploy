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
        Schema::create('festivals', function (Blueprint $table) {
            $table->increments('festivals_id');
            $table->string('name');
            $table->json('description');
            $table->string('address', 255);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('price')->nullable()->default(0);
            $table->json('images');
            $table->integer('status');
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
        Schema::dropIfExists('festivals');
    }
};
