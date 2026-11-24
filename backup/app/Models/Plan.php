<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    protected $fillable = ['library_id', 'name', 'duration_months', 'price', 'description'];

    public function library(): BelongsTo { return $this->belongsTo(Library::class); }
}
