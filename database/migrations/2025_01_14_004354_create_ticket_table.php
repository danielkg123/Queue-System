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
        Schema::create('ticket', function (Blueprint $table) {
            $table->id()->unique(); // Automatically creates an auto-incrementing primary key named 'id'
            $table->string('no_antrian'); // Number resets daily
            $table->string('loket');
            $table->date('tanggal_antrian');
            $table->boolean('status')->default(0); // 0 for waiting, 1 for selesai
            $table->string('user');
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
