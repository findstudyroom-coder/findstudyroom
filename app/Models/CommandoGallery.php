<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandoGallery extends Model
{
    use HasFactory;

    protected $table = 'commando_galleries';

    protected $fillable = [
        'coaching_id',
        'coaching_name',
        'title',
        'category',
        'image',
        'order_no',
        'status',
    ];

    protected $casts = [
        'coaching_id' => 'integer',
        'status' => 'boolean',
        'order_no' => 'integer',
    ];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class, 'coaching_id');
    }

    public function setImageAttribute($value): void
    {
        if (is_string($value)) {
            $value = trim($value);
            $value = preg_replace('/^file:\/\/\/?/i', '', $value);
            $this->attributes['image'] = $value;
        } else {
            $this->attributes['image'] = $value;
        }
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://images.unsplash.com/photo-1555597673-b21d5c935865?auto=format&fit=crop&w=800&q=80';
        }

        $image = trim($this->image);

        // Strip file:/// or file:// protocol prefix if present
        $image = preg_replace('/^file:\/\/\/?/i', '', $image);
        $image = ltrim($image, '/');

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return asset($image);
    }
}
