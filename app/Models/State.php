<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name'];

    // One State → Many Districts
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    // One State → Many Libraries
    public function libraries()
    {
        return $this->hasMany(Library::class, 'state_id');
    }
}