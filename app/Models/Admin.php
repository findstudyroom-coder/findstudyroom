<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable, \Spatie\Permission\Traits\HasRoles;

    protected $table = 'admins'; // make sure table name is correct

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'status',
        'password',
        'image',
        'coaching_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function libraries()
    {
        return $this->belongsToMany(Library::class, 'admin_library_access', 'admin_id', 'library_id');
    }

    public function coaching()
    {
        return $this->belongsTo(Coaching::class, 'coaching_id');
    }

    public function isCoachingAdmin(): bool
    {
        return !empty($this->coaching_id) || $this->hasRole('Coaching Admin');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin') || ($this->role_id ?? 0) === 1;
    }
}
