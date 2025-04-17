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
        // Create users table
        Schema::connection('mysql1')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->date('date_of_birth');
            $table->enum('role', ['user']);
            $table->string('phoneNumber');
            $table->string('place');
            $table->string('user_nid')->unique();
            $table->string('user_image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Create password_reset_tokens table
        Schema::connection('mysql1')->create('password_reset_tokens', function (Blueprint $table) {
            $table->id(); // Use an auto-incrementing primary key
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->unique(['email', 'token']); // Add unique constraint on email and token
        });

        // Create sessions table (if you need it)
        Schema::connection('mysql1')->create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->index(); // Correct foreign key setup
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
