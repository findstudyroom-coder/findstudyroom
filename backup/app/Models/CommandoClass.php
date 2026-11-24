<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandoClass extends Model
{
    use HasFactory;

    protected $table = 'commando_classes';

    protected $fillable = [
        'coaching_id',
        'coaching_name',
        'title',
        'tag',
        'description',
        'duration',
        'level',
        'color_theme',
        'icon',
        'instructor_name',
        'slot_window',
        'price',
        'location_address',
        'timings',
        'phone',
        'map_url',
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
                'icon_bg'     => 'bg-blue-50 border-blue-100 text-blue-600',
                'hover_title' => 'group-hover:text-blue-600',
                'tag_text'    => 'text-blue-600',
                'badge'       => 'bg-blue-50 text-blue-700 border-blue-100',
            ],
            'amber' => [
                'icon_bg'     => 'bg-amber-50 border-amber-100 text-amber-600',
                'hover_title' => 'group-hover:text-amber-600',
                'tag_text'    => 'text-amber-600',
                'badge'       => 'bg-amber-50 text-amber-700 border-amber-100',
            ],
            'teal' => [
                'icon_bg'     => 'bg-teal-50 border-teal-100 text-teal-600',
                'hover_title' => 'group-hover:text-teal-600',
                'tag_text'    => 'text-teal-600',
                'badge'       => 'bg-teal-50 text-teal-700 border-teal-100',
            ],
            'emerald' => [
                'icon_bg'     => 'bg-emerald-50 border-emerald-100 text-emerald-600',
                'hover_title' => 'group-hover:text-emerald-600',
                'tag_text'    => 'text-emerald-600',
                'badge'       => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            ],
            'purple' => [
                'icon_bg'     => 'bg-purple-50 border-purple-100 text-purple-600',
                'hover_title' => 'group-hover:text-purple-600',
                'tag_text'    => 'text-purple-600',
                'badge'       => 'bg-purple-50 text-purple-700 border-purple-100',
            ],
            'rose' => [
                'icon_bg'     => 'bg-rose-50 border-rose-100 text-rose-600',
                'hover_title' => 'group-hover:text-rose-600',
                'tag_text'    => 'text-rose-600',
                'badge'       => 'bg-rose-50 text-rose-700 border-rose-100',
            ],
        ];

        return $themes[$theme] ?? $themes['blue'];
    }

    /**
     * Return SVG Icon string
     */
    public function getRenderedIconAttribute(): string
    {
        $raw = trim((string)$this->icon);

        if (str_starts_with($raw, '<svg')) {
            return $raw;
        }

        $iconKey = strtolower($raw ?: 'karate');

        if ($iconKey === 'judo') {
            return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>';
        }

        if ($iconKey === 'yoga') {
            return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>';
        }

        if ($iconKey === 'boxing' || $iconKey === 'fist') {
            return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
        }

        // Default: Karate / martial martial artist striking silhouette
        return '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a2 2 0 100 4 2 2 0 000-4zm2.5 5.5l-3 3-3-1.5-1.5 2 3.5 1.75V17l-3 4h2.5l2-3 2 3h2.5l-3-4.5V11l2-2-1.5-1.5z"/></svg>';
    }
}
