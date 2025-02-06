<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraincompartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('traincompartments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainid');
            $table->integer('seatnumber');
            $table->string('compartmentname');
            $table->foreign('trainid')->references('trainid')->on('train')->onDelete('cascade');  // Corrected line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traincompartments');
    }
}
