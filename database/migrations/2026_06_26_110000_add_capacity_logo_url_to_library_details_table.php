<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('library_details', function (Blueprint $table) {
            if (! Schema::hasColumn('library_details', 'capacity')) {
                $table->integer('capacity')->nullable()->after('end_date');
            }

            if (! Schema::hasColumn('library_details', 'logo_url')) {
                $table->string('logo_url')->nullable()->after('capacity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('library_details', function (Blueprint $table) {
            if (Schema::hasColumn('library_details', 'logo_url')) {
                $table->dropColumn('logo_url');
            }

            if (Schema::hasColumn('library_details', 'capacity')) {
                $table->dropColumn('capacity');
            }
        });
    }
};
