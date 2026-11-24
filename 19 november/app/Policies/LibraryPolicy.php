<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Library;
use Illuminate\Auth\Access\Response;

class LibraryPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(Admin $admin, string $ability): bool|null
    {
        if ($admin->hasRole('Super Admin')) {
            return true;
        }
        return null; // fall through
    }

    public function viewAny(Admin $admin): bool
    {
        return $admin->can('library.view');
    }

    public function view(Admin $admin, Library $library): bool
    {
        return $admin->can('library.view');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('library.create');
    }

    public function update(Admin $admin, Library $library): bool
    {
        return $admin->can('library.edit');
    }

    public function delete(Admin $admin, Library $library): bool
    {
        return $admin->can('library.delete');
    }
}
