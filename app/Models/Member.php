<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'library_id',
        'name',
        'mobile',
        'location',
        'photo',
        'document',
        'seat_id',
        'plan_id',
        'shift_type',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // ------------------------------------------------------------------
    // Shift label helper
    // ------------------------------------------------------------------

    const SHIFT_LABELS = [
        'half_day_morning' => 'Half Day Morning (6am–2pm)',
        'half_day_evening' => 'Half Day Evening (2pm–10pm)',
        'full_day'         => 'Full Day (6am–10pm)',
    ];

    public function getShiftLabelAttribute(): string
    {
        return self::SHIFT_LABELS[$this->shift_type] ?? $this->shift_type;
    }

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------

    public function library()
    {
        return $this->belongsTo(Library::class, 'library_id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function plan()
    {
        return $this->belongsTo(LibraryPlan::class, 'plan_id');
    }

    // ------------------------------------------------------------------
    // Scopes
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                     ->orWhere('end_date', '<', Carbon::today());
    }

    public function scopeForLibrary($query, $libraryId)
    {
        return $query->where('library_id', $libraryId);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function daysRemaining(): int
    {
        if (! $this->end_date) {
            return 0;
        }
        return max(0, (int) now()->diffInDays($this->end_date, false));
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('uploads/default-member.png');
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document ? asset('storage/' . $this->document) : null;
    }
}
