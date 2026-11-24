<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained('libraries')->onDelete('cascade');
            $table->string('seat_number'); // e.g., A-01, B-05, W-1
            $table->string('zone'); // A, B, C
            $table->integer('row');
            $table->integer('col');
            $table->string('floor'); // Ground Floor, 1st Floor, 2nd Floor
            $table->string('room'); // Room A-1, Room A-2, Quiet Zone
            $table->boolean('is_premium')->default(false);
            $table->enum('status', ['available', 'occupied', 'reserved', 'blocked'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
