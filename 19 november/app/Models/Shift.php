<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    protected $fillable = ['library_id', 'name', 'key_name', 'start_time', 'end_time', 'icon'];

    public function library(): BelongsTo { return $this->belongsTo(Library::class); }
}
