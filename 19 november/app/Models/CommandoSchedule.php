<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandoSchedule extends Model
{
    use HasFactory;

    protected $table = 'commando_schedules';

    protected $fillable = [
        'day',
        'dawn_title',
        'dawn_subtitle',
        'dawn_time',
        'cadet_title',
        'cadet_subtitle',
        'cadet_time',
        'combat_title',
        'combat_subtitle',
        'combat_time',
        'order_no',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order_no' => 'integer',
    ];
}
