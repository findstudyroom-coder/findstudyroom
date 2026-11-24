<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('library_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id')->index();
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('library_details');
    }
};
