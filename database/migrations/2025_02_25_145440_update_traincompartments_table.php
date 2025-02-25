<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('traincompartments', function (Blueprint $table) {
            $table->string('compartmenttype')->after('compartmentname')->nullable();
        });
    }

    public function down()
    {
        Schema::table('traincompartments', function (Blueprint $table) {
            $table->dropColumn('compartmenttype');
        });
    }
};
