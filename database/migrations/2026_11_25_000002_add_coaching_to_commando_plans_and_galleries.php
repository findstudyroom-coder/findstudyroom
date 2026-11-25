<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. commando_plans
        if (Schema::hasTable('commando_plans')) {
            Schema::table('commando_plans', function (Blueprint $table) {
                if (!Schema::hasColumn('commando_plans', 'coaching_id')) {
                    $table->unsignedBigInteger('coaching_id')->nullable()->after('id')->index();
                }
                if (!Schema::hasColumn('commando_plans', 'coaching_name')) {
                    $table->string('coaching_name', 255)->nullable()->after('coaching_id');
                }
            });

            // If coachings exist, attach existing plans to coaching_id = 1 or first coaching
            $firstCoaching = DB::table('coachings')->first();
            if ($firstCoaching) {
                DB::table('commando_plans')->whereNull('coaching_id')->update([
                    'coaching_id'   => $firstCoaching->id,
                    'coaching_name' => $firstCoaching->coaching_name,
                ]);
            }
        }

        // 2. commando_galleries
        if (Schema::hasTable('commando_galleries')) {
            Schema::table('commando_galleries', function (Blueprint $table) {
                if (!Schema::hasColumn('commando_galleries', 'coaching_id')) {
                    $table->unsignedBigInteger('coaching_id')->nullable()->after('id')->index();
                }
                if (!Schema::hasColumn('commando_galleries', 'coaching_name')) {
                    $table->string('coaching_name', 255)->nullable()->after('coaching_id');
                }
                if (!Schema::hasColumn('commando_galleries', 'category')) {
                    $table->string('category', 100)->nullable()->after('title');
                }
            });

            // If coachings exist, attach existing galleries to coaching_id = 1 or first coaching
            $firstCoaching = DB::table('coachings')->first();
            if ($firstCoaching) {
                DB::table('commando_galleries')->whereNull('coaching_id')->update([
                    'coaching_id'   => $firstCoaching->id,
                    'coaching_name' => $firstCoaching->coaching_name,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('commando_plans')) {
            Schema::table('commando_plans', function (Blueprint $table) {
                if (Schema::hasColumn('commando_plans', 'coaching_id')) {
                    $table->dropColumn('coaching_id');
                }
                if (Schema::hasColumn('commando_plans', 'coaching_name')) {
                    $table->dropColumn('coaching_name');
                }
            });
        }

        if (Schema::hasTable('commando_galleries')) {
            Schema::table('commando_galleries', function (Blueprint $table) {
                if (Schema::hasColumn('commando_galleries', 'coaching_id')) {
                    $table->dropColumn('coaching_id');
                }
                if (Schema::hasColumn('commando_galleries', 'coaching_name')) {
                    $table->dropColumn('coaching_name');
                }
                if (Schema::hasColumn('commando_galleries', 'category')) {
                    $table->dropColumn('category');
                }
            });
        }
    }
};
