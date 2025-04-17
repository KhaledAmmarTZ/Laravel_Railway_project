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
            $table->unsignedBigInteger('user_id'); // Foreign key for users
            $table->unsignedBigInteger('trainid');
            $table->string('tsource');
            $table->string('tdest');
            $table->date('arrdate');
            $table->time('arrtime');
            $table->time('dptime');
            $table->string('tclass');
            $table->boolean('mealop');
            $table->string('pstatus');
            $table->float('price');

            $table->foreign('trainid')->references('trainid')->on('train')->onDelete('cascade')->onUpdate('cascade');
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
