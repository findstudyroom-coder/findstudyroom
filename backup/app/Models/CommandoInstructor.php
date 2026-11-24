<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandoInstructor extends Model
{
    use HasFactory;

    protected $table = 'commando_instructors';

    protected $fillable = [
        'coaching_id',
        'coaching_name',
        'name',
        'role_title',
        'rank_badge',
        'bio',
        'experience',
        'specialties',
        'image',
        'color_theme',
        'order_no',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order_no' => 'integer',
        'coaching_id' => 'integer',
    ];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class, 'coaching_id');
    }

    /**
     * Color theme styling classes for Tailwind
     */
    public function getThemeConfigAttribute(): array
    {
        $theme = strtolower($this->color_theme ?: 'blue');

        $themes = [
            'blue' => [
                'gradient'   => 'from-blue-50 to-indigo-100',
                'rank_badge' => 'bg-blue-600 text-white',
                'role_text'  => 'text-blue-600',
                'exp_badge'  => 'bg-blue-50 border-blue-200 text-blue-700',
            ],
            'amber' => [
                'gradient'   => 'from-amber-50 to-orange-100',
                'rank_badge' => 'bg-amber-600 text-white',
                'role_text'  => 'text-amber-600',
                'exp_badge'  => 'bg-amber-50 border-amber-200 text-amber-700',
            ],
            'teal' => [
                'gradient'   => 'from-teal-50 to-emerald-100',
                'rank_badge' => 'bg-teal-600 text-white',
                'role_text'  => 'text-teal-600',
                'exp_badge'  => 'bg-teal-50 border-teal-200 text-teal-700',
            ],
            'emerald' => [
                'gradient'   => 'from-emerald-50 to-teal-100',
                'rank_badge' => 'bg-emerald-600 text-white',
                'role_text'  => 'text-emerald-600',
                'exp_badge'  => 'bg-emerald-50 border-emerald-200 text-emerald-700',
            ],
            'purple' => [
                'gradient'   => 'from-purple-50 to-pink-100',
                'rank_badge' => 'bg-purple-600 text-white',
                'role_text'  => 'text-purple-600',
                'exp_badge'  => 'bg-purple-50 border-purple-200 text-purple-700',
            ],
            'rose' => [
                'gradient'   => 'from-rose-50 to-red-100',
                'rank_badge' => 'bg-rose-600 text-white',
                'role_text'  => 'text-rose-600',
                'exp_badge'  => 'bg-rose-50 border-rose-200 text-rose-700',
            ],
        ];

        return $themes[$theme] ?? $themes['blue'];
    }

    /**
     * Get array of specialties from comma-separated string
     */
    public function getSpecialtiesListAttribute(): array
    {
        if (empty($this->specialties)) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->specialties)));
    }

    /**
     * Get safe image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://images.unsplash.com/photo-1555597673-b21d5c935865?auto=format&fit=crop&w=400&q=80';
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset($this->image);
    }
}
