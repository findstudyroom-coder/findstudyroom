<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('seat_id')->nullable()->constrained('seats')->onDelete('set null');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('restrict');
            $table->foreignId('plan_id')->constrained('plans')->onDelete('restrict');
            $table->string('membership_number')->unique(); // e.g., L-2024-128
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['CURRENT', 'COMPLETED', 'EXPIRED', 'CANCELLED'])->default('CURRENT');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_memberships');
    }
};
