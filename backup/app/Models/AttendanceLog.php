<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    protected $fillable = ['student_id', 'seat_id', 'date', 'check_in', 'check_out'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function seat(): BelongsTo { return $this->belongsTo(Seat::class); }
}
