<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $commandoPerms = [
            'commando.view',
            'commando.create',
            'commando.edit',
            'commando.delete',
            'commando.trials',
        ];

        Permission::whereIn('name', $commandoPerms)->delete();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
