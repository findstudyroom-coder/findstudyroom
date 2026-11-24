<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Library;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SubAdminController extends Controller
{
    public function index()
    {
        // Only get admins that are Sub Admins (or exclude Super Admins)
        $subadmins = Admin::whereHas('roles', function($q) {
            $q->where('name', 'Sub Admin');
        })->get();

        return view('admin.subadmins.index', compact('subadmins'));
    }

    public function create()
    {
        $permissions = Permission::all();
        $libraries = Library::all();
        return view('admin.subadmins.create', compact('permissions', 'libraries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'mobile' => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
            'permissions' => 'array',
            'libraries' => 'array',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'status' => $request->status ? 1 : 0,
            'image' => '', // Fix for "Field 'image' doesn't have a default value"
        ]);

        $admin->assignRole('Sub Admin');

        if ($request->has('permissions')) {
            $admin->syncPermissions($request->permissions);
        }

        if ($request->has('assign_all_libraries') && $request->assign_all_libraries == '1') {
            $admin->libraries()->sync(Library::pluck('id')->toArray());
        } elseif ($request->has('libraries')) {
            $admin->libraries()->sync($request->libraries);
        }

        return redirect()->route('subadmins.index')->with('success', 'Sub Admin created successfully.');
    }

    public function edit($id)
    {
        $subadmin = Admin::findOrFail($id);
        $permissions = Permission::all();
        $libraries = Library::all();
        $assignedPermissions = $subadmin->permissions->pluck('name')->toArray();
        $assignedLibraries = $subadmin->libraries->pluck('id')->toArray();

        return view('admin.subadmins.edit', compact('subadmin', 'permissions', 'libraries', 'assignedPermissions', 'assignedLibraries'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$id,
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'permissions' => 'array',
            'libraries' => 'array',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'status' => $request->status ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        if ($request->has('permissions')) {
            $admin->syncPermissions($request->permissions);
        } else {
            $admin->syncPermissions([]);
        }

        if ($request->has('assign_all_libraries') && $request->assign_all_libraries == '1') {
            $admin->libraries()->sync(Library::pluck('id')->toArray());
        } elseif ($request->has('libraries')) {
            $admin->libraries()->sync($request->libraries);
        } else {
            $admin->libraries()->sync([]);
        }

        return redirect()->route('subadmins.index')->with('success', 'Sub Admin updated successfully.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'Cannot delete Super Admin.');
        }
        
        $admin->delete();
        return redirect()->back()->with('success', 'Sub Admin deleted successfully.');
    }
}
