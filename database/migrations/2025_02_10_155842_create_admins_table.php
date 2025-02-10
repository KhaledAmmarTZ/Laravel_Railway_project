<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('admin_name');
            $table->string('admin_email')->unique();
            $table->date('admin_date_of_birth');
            $table->enum('admin_role', ['super_admin', 'admin'])->default('admin');
            $table->string('admin_phoneNumber');
            $table->string('admin_place');
            $table->string('admin_password');
            $table->string('admin_nid')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};

