<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name')->unique();
            $table->string('password');
            $table->string('role')->default('user'); // Add role column with a default value
            $table->integer('counter')->default('0');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        
        Schema::create('role', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name')->unique(); // Role name (e.g., Admin, User)
            $table->string('kode');
            $table->timestamps();
        });
                // Create running_texts table
        Schema::create('running_text', function (Blueprint $table) {
            $table->id();
            $table->string('content'); // The text content to be displayed in the running text
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status of the running text
            $table->timestamps();
        });

        // Create carousels table
        Schema::create('carousel', function (Blueprint $table) {
            $table->id();
            $table->string('image_path'); // Path to the image
            $table->string('link')->nullable(); // Optional link for the carousel item
            $table->integer('order')->default(0); // Order of the carousel item
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status of the carousel item
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('running_texts');
        Schema::dropIfExists('carousels');
        Schema::dropIfExists('role');
    }
};
