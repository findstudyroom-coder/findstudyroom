<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions list
        $permissions = [
            'library.view', 'library.create', 'library.edit', 'library.delete',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'coaching.view', 'coaching.create', 'coaching.edit', 'coaching.delete',
            'plans.view', 'plans.create', 'plans.edit', 'plans.delete',
            'libraryDetails.view', 'libraryDetails.create', 'libraryDetails.edit', 'libraryDetails.delete',
            'students.view', 'students.create', 'students.edit', 'students.delete',
            'bookings.view', 'bookings.create', 'bookings.edit', 'bookings.delete',
            'blogs.view', 'blogs.create', 'blogs.edit', 'blogs.delete',
            'subjects.view', 'subjects.create', 'subjects.edit', 'subjects.delete',
            'classes.view', 'classes.create', 'classes.edit', 'classes.delete',
            'tutors.view', 'tutors.create', 'tutors.edit', 'tutors.delete',
            'boards.view', 'boards.create', 'boards.edit', 'boards.delete',
            'examPreparations.view', 'examPreparations.create', 'examPreparations.edit', 'examPreparations.delete',
            'location.view', 'location.create', 'location.edit', 'location.delete',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Super Admin role
        $superAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        
        // Super Admin gets all permissions implicitly via a Gate later or directly here:
        $superAdmin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // Create Sub Admin role
        $subAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Sub Admin', 'guard_name' => 'web']);
    }
}
