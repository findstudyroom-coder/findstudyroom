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
        Schema::create('coachings', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('coaching_name');
            $table->string('slug')->unique();
            $table->string('coaching_type', 100)->nullable(); // Tuition, Coaching Institute, Home Tuition, Online Coaching
            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->year('established_year')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery_images')->nullable();
            
            // Contact Details
            $table->string('contact_person')->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            
            // Location
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('area_locality')->nullable();
            $table->text('full_address')->nullable();
            $table->string('landmark')->nullable();
            $table->string('pincode', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('google_map_url')->nullable();
            
            // Course Details
            $table->json('courses_offered')->nullable();
            $table->json('class_level')->nullable();
            $table->json('subjects')->nullable();
            $table->string('mode', 100)->nullable(); // Offline, Online, Hybrid
            
            // Fees
            $table->decimal('starting_fees', 10, 2)->nullable();
            $table->decimal('monthly_fees', 10, 2)->nullable();
            $table->decimal('course_fees', 10, 2)->nullable();
            $table->decimal('registration_fees', 10, 2)->nullable();
            $table->boolean('scholarship_available')->default(false);
            
            // Batch Details
            $table->boolean('morning_batch')->default(false);
            $table->boolean('afternoon_batch')->default(false);
            $table->boolean('evening_batch')->default(false);
            $table->boolean('weekend_batch')->default(false);
            $table->string('batch_size', 100)->nullable();
            $table->boolean('demo_class_available')->default(false);
            
            // Faculty
            $table->json('faculty_names')->nullable();
            $table->text('faculty_experience')->nullable();
            $table->text('qualification')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coachings');
    }
};
