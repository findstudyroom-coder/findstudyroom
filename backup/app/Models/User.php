<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['library_id', 'name', 'email', 'phone', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    public function library(): BelongsTo { return $this->belongsTo(Library::class); }
}
