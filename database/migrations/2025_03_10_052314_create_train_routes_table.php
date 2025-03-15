<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('train_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainid');
            $table->unsignedBigInteger('station_id');
            $table->integer('sequence');
            $table->foreign('trainid')->references('trainid')->on('train')->onDelete('cascade');
            $table->foreign('station_id')->references('stid')->on('stations')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('train_routes');
    }
};
