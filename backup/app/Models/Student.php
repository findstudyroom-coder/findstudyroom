<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'library_id', 'student_id', 'name', 'phone', 'parent_phone', 'email', 'gender',
        'dob', 'exam_prep', 'college', 'course', 'photo_url', 'id_proof_url', 'is_active'
    ];

    public function library(): BelongsTo { return $this->belongsTo(Library::class); }
    public function memberships(): HasMany { return $this->hasMany(StudentMembership::class); }
    
    public function activeMembership(): HasOne
    {
        return $this->hasOne(StudentMembership::class)->where('status', 'CURRENT');
    }
    
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function attendanceLogs(): HasMany { return $this->hasMany(AttendanceLog::class); }
}
