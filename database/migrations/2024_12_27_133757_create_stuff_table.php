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
        Schema::create('stuff', function (Blueprint $table) {
            $table->id('sid');
            $table->integer('snid');
            $table->string('stype');
            $table->string('sname');
            $table->string('semail');
            $table->string('spass');
            $table->date('sdob');
            $table->text('splace');
            $table->string('sphone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stuff');
    }
};
