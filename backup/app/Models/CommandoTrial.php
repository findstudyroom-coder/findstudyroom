<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandoTrial extends Model
{
    use HasFactory;

    protected $table = 'commando_trials';

    protected $fillable = [
        'coaching_id',
        'coaching_name',
        'name',
        'phone',
        'discipline',
        'slot',
        'date',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'coaching_id' => 'integer',
    ];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class, 'coaching_id');
    }

    /**
     * Clean phone for WhatsApp links
     */
    public function getWhatsappPhoneAttribute(): string
    {
        $clean = preg_replace('/[^0-9]/', '', (string)$this->phone);
        if (strlen($clean) === 10) {
            return '91' . $clean;
        }
        return $clean;
    }
}
