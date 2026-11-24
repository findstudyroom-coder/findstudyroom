<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandoTrial extends Model
{
    use HasFactory;

    protected $table = 'commando_trials';

    protected $fillable = [
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
    ];

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
