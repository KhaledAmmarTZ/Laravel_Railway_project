<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trainupdowns', function (Blueprint $table) {
            $table->datetime('tarrtime')->change();
            $table->datetime('tdeptime')->change();
        });
    }

    public function down()
    {
        Schema::table('trainupdowns', function (Blueprint $table) {
            $table->time('tarrtime')->change();
            $table->time('tdeptime')->change();
        });
    }
};
