<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('library_details', function (Blueprint $table) {
            if (! Schema::hasColumn('library_details', 'plan_id')) {
                $table->unsignedBigInteger('plan_id')->nullable()->after('location');
            }

            if (! Schema::hasColumn('library_details', 'validity')) {
                $table->integer('validity')->nullable()->after('plan_id');
            }

            if (! Schema::hasColumn('library_details', 'start_date')) {
                $table->date('start_date')->nullable()->after('validity');
            }

            if (! Schema::hasColumn('library_details', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('library_details', function (Blueprint $table) {
            if (Schema::hasColumn('library_details', 'end_date')) {
                $table->dropColumn('end_date');
            }

            if (Schema::hasColumn('library_details', 'start_date')) {
                $table->dropColumn('start_date');
            }

            if (Schema::hasColumn('library_details', 'validity')) {
                $table->dropColumn('validity');
            }

            if (Schema::hasColumn('library_details', 'plan_id')) {
                $table->dropColumn('plan_id');
            }
        });
    }
};
