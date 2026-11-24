<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained('libraries')->onDelete('cascade');
            $table->string('name'); // e.g., Morning, Afternoon, Evening, Full Day
            $table->string('key_name'); // e.g., morning, afternoon, evening, fullDay
            $table->time('start_time');
            $table->time('end_time');
            $table->string('icon')->default('light_mode');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
