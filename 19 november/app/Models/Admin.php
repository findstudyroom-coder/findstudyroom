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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function libraries()
    {
        return $this->belongsToMany(Library::class, 'admin_library_access', 'admin_id', 'library_id');
    }
}
