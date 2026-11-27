<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachingLead extends Model
{
    use HasFactory;

    protected $table = 'coaching_leads';

    protected $fillable = [
        'coaching_id',
        'student_name',
        'phone',
        'email',
        'course_interested',
        'slot',
        'enquiry_date',
        'status',
        'notes',
        'source',
    ];

    protected $casts = [
        'enquiry_date' => 'date',
        'coaching_id'  => 'integer',
    ];

    const STATUS_NEW        = 'New';
    const STATUS_CONTACTED  = 'Contacted';
    const STATUS_INTERESTED = 'Interested';
    const STATUS_CONVERTED  = 'Converted';
    const STATUS_CLOSED     = 'Closed';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_NEW        => 'New',
            self::STATUS_CONTACTED  => 'Contacted',
            self::STATUS_INTERESTED => 'Interested',
            self::STATUS_CONVERTED  => 'Converted',
            self::STATUS_CLOSED     => 'Closed',
        ];
    }

    public function coaching()
    {
        return $this->belongsTo(Coaching::class, 'coaching_id');
    }

    public function scopeForCoaching($query, $coachingId)
    {
        return $query->where('coaching_id', $coachingId);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Clean phone for WhatsApp link
     */
    public function getWhatsappPhoneAttribute(): string
    {
        $clean = preg_replace('/[^0-9]/', '', (string)$this->phone);
        if (strlen($clean) === 10) {
            return '91' . $clean;
        }
        return $clean;
    }

    /**
     * Badge classes for UI
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_NEW        => 'bg-info text-dark',
            self::STATUS_CONTACTED  => 'bg-primary text-white',
            self::STATUS_INTERESTED => 'bg-warning text-dark',
            self::STATUS_CONVERTED  => 'bg-success text-white',
            self::STATUS_CLOSED     => 'bg-secondary text-white',
            default                 => 'bg-dark text-white',
        };
    }
}
