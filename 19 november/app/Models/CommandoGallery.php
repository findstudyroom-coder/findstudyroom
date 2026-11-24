<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandoGallery extends Model
{
    use HasFactory;

    protected $table = 'commando_galleries';

    protected $fillable = [
        'title',
        'image',
        'order_no',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order_no' => 'integer',
    ];

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://images.unsplash.com/photo-1555597673-b21d5c935865?auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset($this->image);
    }
}
