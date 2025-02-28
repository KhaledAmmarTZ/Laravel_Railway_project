<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainupdownsTable extends Migration
{
    public function up()
    {
        Schema::create('trainupdowns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainid');
            $table->time('tarrtime');
            $table->time('tdeptime');
            $table->date('tarrdate');
            $table->date('tdepdate');
            $table->string('tsource');
            $table->string('tdestination');
            $table->foreign('trainid')->references('trainid')->on('train')->onDelete('cascade');  // Corrected line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainupdowns');
    }
}
