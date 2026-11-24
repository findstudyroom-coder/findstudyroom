<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tutor extends Model
{
    protected $table = 'tutors'; 

    protected $fillable = [
        'full_name',
        'slug',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'hourly_rate',
        'demo_price',
        'experience_years',
        'total_students',
        'location',
        'location_area',
        'pincode',
        'full_address',
        'image',
        'travel_radius',
        'group_teaching_available',
        'preferred_library_id',
        'rating',
        'is_verified',
        'availability',
        'is_featured',
        'student_level',
        'demo_class_available',
        'teaching_mode',
        'bio',
        'languages',
        'qualification',
        'certifications',
        'achievements',
        'subject',
        'classes',
        'boards',
        'exam_preparation',
        'state_id',
        'district_id',
        'tehsil_id',
    ];

    protected $casts = [
        'subject' => 'array',
        'classes' => 'array',
        'boards' => 'array',
        'exam_preparation' => 'array',
        'achievements' => 'array',
        'group_teaching_available' => 'boolean',
        'is_verified' => 'boolean',
        'demo_class_available' => 'boolean',
        'rating' => 'float',
    ];
    
     public function library()
    {
        return $this->belongsTo(Library::class, 'preferred_library_id');
    }

    // Tutor → Classes (if applicable)
    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'tutor_classes');
    }

    public function getPublicSlugAttribute()
    {
        return $this->slug ?? Str::slug($this->full_name);
    }
}
