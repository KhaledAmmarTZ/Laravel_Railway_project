<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tsource', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tid');
            $table->string('source');
            $table->foreign('tid')->references('tid')->on('train')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tsource');
    }
};
