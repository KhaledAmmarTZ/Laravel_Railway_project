<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('super_admins', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('admin_name');
            $table->string('admin_email')->unique();
            $table->date('admin_date_of_birth');
            $table->enum('admin_role', ['super admin']);
            $table->string('admin_phoneNumber');
            $table->string('admin_place');
            $table->string('admin_password'); // Password will be stored hashed
            $table->string('admin_nid')->unique();
            $table->string('admin_image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('super_admins');
    }
};
