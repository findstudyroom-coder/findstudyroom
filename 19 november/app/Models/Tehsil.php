<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tehsil extends Model
{
    protected $fillable = ['name', 'district_id'];

    // Many Tehsils → One District
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}