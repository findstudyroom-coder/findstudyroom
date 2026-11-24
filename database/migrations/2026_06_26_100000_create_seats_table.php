<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained('libraries')->onDelete('cascade');
            $table->unsignedSmallInteger('seat_number'); // 1–100
            $table->enum('seat_type', ['half_day', 'full_day', 'both'])->default('both');
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->timestamps();

            // Each library has unique seat numbers
            $table->unique(['library_id', 'seat_number']);
            $table->index('library_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
