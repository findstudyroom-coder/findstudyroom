<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    protected $fillable = [
        'library_id', 'seat_number', 'zone', 'row', 'col',
        'floor', 'room', 'is_premium', 'status'
    ];

    public function library(): BelongsTo { return $this->belongsTo(Library::class); }
    public function memberships(): HasMany { return $this->hasMany(StudentMembership::class); }

    public function activeMembers(): HasMany
    {
        return $this->hasMany(StudentMembership::class)
                    ->whereDate('end_date', '>=', now());
    }

    public function scopeForLibrary($query, $libraryId)
    {
        return $query->where('library_id', $libraryId);
    }
}
