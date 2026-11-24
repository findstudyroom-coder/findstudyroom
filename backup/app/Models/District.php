<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name', 'state_id'];

    // Many Districts → One State
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    // One District → Many Tehsils
    public function tehsils()
    {
        return $this->hasMany(Tehsil::class);
    }

    // One District → Many Libraries
    public function libraries()
    {
        return $this->hasMany(Library::class, 'city_id');
    }
}