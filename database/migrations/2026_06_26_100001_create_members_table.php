<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained('libraries')->onDelete('cascade');
            $table->string('name');
            $table->string('mobile', 15);
            $table->string('location')->nullable();
            $table->string('photo')->nullable();       // path to uploaded photo
            $table->string('document')->nullable();    // path to uploaded document

            $table->foreignId('seat_id')->nullable()->constrained('seats')->onDelete('set null');
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('set null');

            $table->enum('shift_type', ['half_day_morning', 'half_day_evening', 'full_day'])->default('full_day');
            // half_day_morning = 6am-2pm, half_day_evening = 2pm-10pm
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['library_id', 'status']);
            $table->index('mobile');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
