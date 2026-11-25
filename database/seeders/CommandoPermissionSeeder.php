<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class CommandoPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $commandoPerms = [
            'commando.view',
            'commando.create',
            'commando.edit',
            'commando.delete',
            'commando.trials',
        ];

        foreach ($commandoPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Grant to Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Grant to Sub Admin
        $subAdminRole = Role::firstOrCreate(['name' => 'Sub Admin', 'guard_name' => 'web']);
        $subAdminRole->givePermissionTo($commandoPerms);

        // Grant to existing Sub Admins
        $subAdmins = Admin::whereHas('roles', function($q) {
            $q->where('name', 'Sub Admin');
        })->get();

        foreach ($subAdmins as $subadmin) {
            $subadmin->givePermissionTo($commandoPerms);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
