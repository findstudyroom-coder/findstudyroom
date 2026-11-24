<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('commando_classes')) {
            Schema::create('commando_classes', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('tag')->nullable();
                $table->text('description')->nullable();
                $table->string('duration')->default('60 MIN');
                $table->string('level')->default('ALL BELT TIERS');
                $table->string('color_theme')->default('blue');
                $table->text('icon')->nullable();
                $table->string('instructor_name')->nullable();
                $table->string('slot_window')->nullable();
                $table->string('price')->nullable();
                $table->text('location_address')->nullable();
                $table->string('timings')->nullable();
                $table->string('phone')->nullable();
                $table->text('map_url')->nullable();
                $table->integer('order_no')->default(0);
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('commando_instructors')) {
            Schema::create('commando_instructors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('role_title')->nullable();
                $table->string('rank_badge')->nullable();
                $table->text('bio')->nullable();
                $table->string('experience')->nullable();
                $table->text('specialties')->nullable();
                $table->text('image')->nullable();
                $table->string('color_theme')->default('blue');
                $table->integer('order_no')->default(0);
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('commando_schedules')) {
            Schema::create('commando_schedules', function (Blueprint $table) {
                $table->id();
                $table->string('day', 50);
                $table->string('dawn_title')->nullable();
                $table->string('dawn_subtitle')->nullable();
                $table->string('dawn_time', 50)->default('06:30 AM');
                $table->string('cadet_title')->nullable();
                $table->string('cadet_subtitle')->nullable();
                $table->string('cadet_time', 50)->default('04:30 PM');
                $table->string('combat_title')->nullable();
                $table->string('combat_subtitle')->nullable();
                $table->string('combat_time', 50)->default('07:00 PM');
                $table->integer('order_no')->default(0);
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('commando_plans')) {
            Schema::create('commando_plans', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('tag')->nullable();
                $table->text('subtitle')->nullable();
                $table->string('price', 100);
                $table->string('billing_period', 50)->default('/month');
                $table->text('features')->nullable();
                $table->boolean('is_featured')->default(0);
                $table->string('featured_badge', 100)->nullable();
                $table->string('button_text', 100)->default('Select Plan');
                $table->string('color_theme', 50)->default('blue');
                $table->integer('order_no')->default(0);
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('commando_galleries')) {
            Schema::create('commando_galleries', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('image');
                $table->integer('order_no')->default(0);
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('commando_trials')) {
            Schema::create('commando_trials', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone', 20);
                $table->string('discipline')->nullable();
                $table->string('slot')->nullable();
                $table->date('date')->nullable();
                $table->string('status')->default('Pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('commando_classes');
        Schema::dropIfExists('commando_instructors');
        Schema::dropIfExists('commando_schedules');
        Schema::dropIfExists('commando_plans');
        Schema::dropIfExists('commando_galleries');
        Schema::dropIfExists('commando_trials');
    }
};
