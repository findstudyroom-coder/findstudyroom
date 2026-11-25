<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandoPlan extends Model
{
    use HasFactory;

    protected $table = 'commando_plans';

    protected $fillable = [
        'coaching_id',
        'coaching_name',
        'title',
        'tag',
        'subtitle',
        'price',
        'billing_period',
        'features',
        'is_featured',
        'featured_badge',
        'button_text',
        'color_theme',
        'order_no',
        'status',
    ];

    protected $casts = [
        'coaching_id' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'order_no' => 'integer',
    ];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class, 'coaching_id');
    }

    /**
     * Get features as a clean array
     */
    public function getFeaturesListAttribute(): array
    {
        if (empty($this->features)) {
            return [];
        }

        // Split by newlines or comma
        $lines = preg_split('/[\r\n]+/', (string)$this->features);
        return array_values(array_filter(array_map('trim', $lines)));
    }

    /**
     * Theme config
     */
    public function getThemeConfigAttribute(): array
    {
        $theme = strtolower($this->color_theme ?: 'slate');

        $themes = [
            'slate' => [
                'tag_color'    => 'text-slate-500',
                'check_color'  => 'text-blue-600',
                'button_class' => 'bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-800',
            ],
            'amber' => [
                'tag_color'    => 'text-amber-600',
                'check_color'  => 'text-amber-600',
                'button_class' => 'bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-800',
            ],
            'blue' => [
                'tag_color'    => 'text-blue-600',
                'check_color'  => 'text-blue-700 font-bold',
                'button_class' => 'bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-800 text-white',
            ],
        ];

        return $themes[$theme] ?? $themes['slate'];
    }
}
