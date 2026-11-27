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
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->coaching_name) . '&background=0ea5e9&color=fff';
        }
        $logo = ltrim(preg_replace('/^file:\/\/\/?/i', '', trim($this->logo)), '/');
        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            return $logo;
        }
        return asset($logo);
    }

    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return 'https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=1200&q=80';
        }
        $cover = ltrim(preg_replace('/^file:\/\/\/?/i', '', trim($this->cover_image)), '/');
        if (str_starts_with($cover, 'http://') || str_starts_with($cover, 'https://')) {
            return $cover;
        }
        return asset($cover);
    }

    public function classes()
    {
        return $this->hasMany(CommandoClass::class, 'coaching_id');
    }

    public function instructors()
    {
        return $this->hasMany(CommandoInstructor::class, 'coaching_id');
    }

    public function schedules()
    {
        return $this->hasMany(CommandoSchedule::class, 'coaching_id');
    }

    public function trials()
    {
        return $this->hasMany(CommandoTrial::class, 'coaching_id');
    }

    public function plans()
    {
        return $this->hasMany(CommandoPlan::class, 'coaching_id');
    }

    public function galleries()
    {
        return $this->hasMany(CommandoGallery::class, 'coaching_id');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class, 'coaching_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'coaching_id');
    }

    public function leads()
    {
        return $this->hasMany(CoachingLead::class, 'coaching_id');
    }
}
