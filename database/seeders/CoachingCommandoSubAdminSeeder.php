<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CoachingCommandoSubAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. All Coaching & Commando permissions
        $permissions = [
            'coaching.view',
            'coaching.create',
            'coaching.edit',
            'coaching.delete',
            'commando.view',
            'commando.create',
            'commando.edit',
            'commando.delete',
            'commando.trials',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // 2. Assign to Super Admin and Sub Admin roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $subAdminRole = Role::firstOrCreate(['name' => 'Sub Admin', 'guard_name' => 'web']);

        $superAdminRole->givePermissionTo($permissions);
        $subAdminRole->givePermissionTo($permissions);

        // 3. Create or update the dedicated Sub Admin
        $subadmin = Admin::firstOrCreate(
            ['email' => 'commando@findstudyroom.com'],
            [
                'name'     => 'Coaching & Commando Admin',
                'mobile'   => '9876543210',
                'password' => Hash::make('Commando@123'),
                'status'   => 1,
                'image'    => '',
            ]
        );

        // If it already existed, make sure password and status are set
        $subadmin->update([
            'status' => 1,
            'password' => Hash::make('Commando@123'),
        ]);

        // Assign Sub Admin role
        if (!$subadmin->hasRole('Sub Admin')) {
            $subadmin->assignRole('Sub Admin');
        }

        // Directly sync the permissions to the subadmin as well
        $subadmin->syncPermissions($permissions);

        // 4. Also grant full coaching permissions to other subadmins
        $otherSubadmins = Admin::whereHas('roles', function ($q) {
            $q->where('name', 'Sub Admin');
        })->get();

        foreach ($otherSubadmins as $admin) {
            $admin->givePermissionTo($permissions);
        }
    }
}
