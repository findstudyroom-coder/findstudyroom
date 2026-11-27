<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin;
use App\Models\Coaching;
use App\Models\CoachingLead;
use App\Models\CommandoTrial;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CoachingSetupService
{
    /**
     * Ensure database schema, Spatie roles, and seed demo credentials if needed.
     */
    public static function ensureSchema(): void
    {
        try {
            // 1. Add coaching_id to admins table if missing
            if (Schema::hasTable('admins') && !Schema::hasColumn('admins', 'coaching_id')) {
                Schema::table('admins', function (Blueprint $table) {
                    $table->unsignedBigInteger('coaching_id')->nullable()->after('id')->index();
                });
            }

            // 2. Create coaching_leads table if missing
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
                });
            }

            // 3. Ensure Role 'Coaching Admin' exists
            $coachingAdminRole = Role::firstOrCreate(['name' => 'Coaching Admin', 'guard_name' => 'web']);
            
            // Assign essential permissions
            $permissions = [
                'coaching.view',
                'coaching.edit',
                'commando.view',
                'commando.trials',
            ];
            foreach ($permissions as $perm) {
                Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            }
            $coachingAdminRole->givePermissionTo($permissions);

            // 4. Import existing commando_trials into coaching_leads if empty
            if (Schema::hasTable('commando_trials') && Schema::hasTable('coaching_leads')) {
                if (CoachingLead::count() === 0 && CommandoTrial::count() > 0) {
                    $trials = CommandoTrial::all();
                    foreach ($trials as $t) {
                        $mappedStatus = match ($t->status) {
                            'Confirmed' => CoachingLead::STATUS_CONTACTED,
                            'Attended'  => CoachingLead::STATUS_CONVERTED,
                            'Cancelled' => CoachingLead::STATUS_CLOSED,
                            default     => CoachingLead::STATUS_NEW,
                        };

                        CoachingLead::create([
                            'coaching_id'       => $t->coaching_id ?: 1,
                            'student_name'      => $t->name,
                            'phone'             => $t->phone,
                            'course_interested' => $t->discipline,
                            'slot'              => $t->slot,
                            'enquiry_date'      => $t->date,
                            'status'            => $mappedStatus,
                            'notes'             => $t->notes,
                            'source'            => 'Commando Trial Booking',
                            'created_at'        => $t->created_at ?: now(),
                            'updated_at'        => $t->updated_at ?: now(),
                        ]);
                    }
                }
            }

            // 5. Ensure at least one demo Coaching Admin exists for the first coaching
            $firstCoaching = Coaching::first();
            if ($firstCoaching) {
                $email = 'themovement@findstudyroom.com';
                $existingAdmin = Admin::where('coaching_id', $firstCoaching->id)->first() 
                               ?: Admin::where('email', $email)->first();
                if (!$existingAdmin) {
                    $admin = Admin::create([
                        'coaching_id' => $firstCoaching->id,
                        'name'        => $firstCoaching->coaching_name . ' Admin',
                        'email'       => $email,
                        'mobile'      => $firstCoaching->mobile_number ?: '9876543210',
                        'status'      => 1,
                        'password'    => Hash::make('Coaching@123'),
                        'image'       => '',
                    ]);
                    $admin->assignRole('Coaching Admin');
                } else {
                    // Safety check: Never reassign Super Admin
                    if (!$existingAdmin->hasRole('Super Admin')) {
                        if (!$existingAdmin->coaching_id) {
                            $existingAdmin->update(['coaching_id' => $firstCoaching->id]);
                        }
                        if (!$existingAdmin->hasRole('Coaching Admin')) {
                            $existingAdmin->assignRole('Coaching Admin');
                        }
                    }
                }
            }

            // 6. Auto-clean any local file:/// paths in gallery and coachings
            if (Schema::hasTable('commando_galleries')) {
                \Illuminate\Support\Facades\DB::table('commando_galleries')
                    ->where('image', 'like', 'file:%')
                    ->get()
                    ->each(function ($row) {
                        $clean = ltrim(preg_replace('/^file:\/\/\/?/i', '', $row->image), '/');
                        \Illuminate\Support\Facades\DB::table('commando_galleries')->where('id', $row->id)->update(['image' => $clean]);
                    });
            }
            if (Schema::hasTable('coachings')) {
                \Illuminate\Support\Facades\DB::table('coachings')
                    ->where('logo', 'like', 'file:%')
                    ->orWhere('cover_image', 'like', 'file:%')
                    ->get()
                    ->each(function ($row) {
                        $updates = [];
                        if (!empty($row->logo) && str_starts_with($row->logo, 'file:')) {
                            $updates['logo'] = ltrim(preg_replace('/^file:\/\/\/?/i', '', $row->logo), '/');
                        }
                        if (!empty($row->cover_image) && str_starts_with($row->cover_image, 'file:')) {
                            $updates['cover_image'] = ltrim(preg_replace('/^file:\/\/\/?/i', '', $row->cover_image), '/');
                        }
                        if (!empty($updates)) {
                            \Illuminate\Support\Facades\DB::table('coachings')->where('id', $row->id)->update($updates);
                        }
                    });
            }

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('CoachingSetupService error: ' . $e->getMessage());
        }
    }
}
