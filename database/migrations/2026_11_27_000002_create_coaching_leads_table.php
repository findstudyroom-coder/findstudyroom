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
        if (!Schema::hasTable('coaching_leads')) {
            Schema::create('coaching_leads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('coaching_id')->index();
                $table->string('student_name');
                $table->string('phone', 25);
                $table->string('email')->nullable();
                $table->string('course_interested')->nullable();
                $table->string('slot', 100)->nullable();
                $table->date('enquiry_date')->nullable();
                $table->string('status', 30)->default('New')->index(); // New, Contacted, Interested, Converted, Closed
                $table->text('notes')->nullable();
                $table->string('source', 100)->default('Website');
                $table->timestamps();

                $table->foreign('coaching_id')->references('id')->on('coachings')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaching_leads');
    }
};
