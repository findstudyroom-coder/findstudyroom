<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentMembership extends Model
{
    protected $fillable = [
        'student_id', 'seat_id', 'shift_id', 'plan_id',
        'membership_number', 'start_date', 'end_date', 'price', 'status'
    ];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function seat(): BelongsTo { return $this->belongsTo(Seat::class); }
    public function shift(): BelongsTo { return $this->belongsTo(Shift::class); }
    public function plan(): BelongsTo { return $this->belongsTo(LibraryPlan::class, 'plan_id'); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
}
