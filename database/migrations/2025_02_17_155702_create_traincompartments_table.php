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
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->integer('booked_seats');
            $table->string('compartmentname');
            $table->string('compartmenttype')->nullable();  // Corrected line
            $table->double('price')->nullable();  // Corrected line
            $table->foreign('trainid')->references('trainid')->on('train')->onDelete('cascade');  // Corrected line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traincompartments');
    }
}
