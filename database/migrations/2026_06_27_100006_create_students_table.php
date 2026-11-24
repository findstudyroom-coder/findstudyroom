<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained('libraries')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('parent_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->default('Male');
            $table->date('dob')->nullable();
            $table->string('exam_prep')->nullable(); // UPSC, SSC, Banking, etc.
            $table->string('college')->nullable();
            $table->string('course')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('id_proof_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
