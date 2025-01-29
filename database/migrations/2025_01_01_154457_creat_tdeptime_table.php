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
        Schema::create('tdeptime', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tid');
            $table->time('deptime');
            $table->foreign('tid')->references('tid')->on('train')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdeptime');
    }
};
