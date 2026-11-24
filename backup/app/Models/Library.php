<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Library extends Model
{
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
        'image_gallery' => 'array',
        'facilities' => 'array',
        'is_verified' => 'boolean',
    ];

    public function users(): HasMany { return $this->hasMany(User::class); }
    public function shifts(): HasMany { return $this->hasMany(Shift::class); }
    public function plans(): HasMany { return $this->hasMany(Plan::class); }
    public function seats(): HasMany { return $this->hasMany(Seat::class); }
    public function students(): HasMany { return $this->hasMany(Student::class); }

    public function state() { return $this->belongsTo(State::class); }
    public function district() { return $this->belongsTo(District::class, 'city_id'); }
}