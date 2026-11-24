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
        Schema::create('library_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id', 100)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device', 50)->nullable();
            $table->string('browser', 50)->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('action_type', 50)->default('view')->index();
            $table->timestamps();

            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_views');
    }
};
