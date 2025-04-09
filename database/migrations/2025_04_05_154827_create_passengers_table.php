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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id('pnr');
            $table->string('tsource');
            $table->string('tdest');
            $table->date('arrdate');
            $table->time('arrtime');
            $table->time('dptime');
            $table->string('tclass');
            $table->boolean('mealop');
            $table->string('pstatus');
            $table->float('price');

            // Foreign key: uid from new_users
            $table->unsignedBigInteger('id');
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // Foreign key: trainid from train
            $table->unsignedBigInteger('trainid');
            $table->foreign('trainid')->references('trainid')->on('train');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
