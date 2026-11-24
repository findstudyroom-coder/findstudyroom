<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coaching extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'gallery_images' => 'array',
        'courses_offered' => 'array',
        'class_level' => 'array',
        'subjects' => 'array',
        'faculty_names' => 'array',
        'scholarship_available' => 'boolean',
        'morning_batch' => 'boolean',
        'afternoon_batch' => 'boolean',
        'evening_batch' => 'boolean',
        'weekend_batch' => 'boolean',
        'demo_class_available' => 'boolean',
    ];
}
