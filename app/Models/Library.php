<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Library extends Model
{
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
        'image_gallery' => 'array',
        'facilities' => 'array',
        'is_verified' => 'boolean',
    ];

    public function users(): HasMany { return $this->hasMany(User::class); }
    public function shifts(): HasMany { return $this->hasMany(Shift::class); }
    public function plans(): HasMany { return $this->hasMany(Plan::class); }
    public function seats(): HasMany { return $this->hasMany(Seat::class); }
    public function students(): HasMany { return $this->hasMany(Student::class); }
    public function views(): HasMany { return $this->hasMany(LibraryView::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }

    public function state() { return $this->belongsTo(State::class); }
    public function district() { return $this->belongsTo(District::class, 'city_id'); }
    
    public function assignedAdmins()
    {
        return $this->belongsToMany(Admin::class, 'admin_library_access', 'library_id', 'admin_id');
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(LibraryCallLog::class)->orderBy('created_at', 'desc');
    }

    public function latestCallLog()
    {
        return $this->hasOne(LibraryCallLog::class)->latestOfMany();
    }

    /**
     * Get unique views count (excludes duplicate IP visits)
     */
    public function getViewsCountAttribute($value = null)
    {
        if ($value !== null) {
            return (int) $value;
        }

        return (int) $this->views()
            ->distinct()
            ->count(DB::raw('COALESCE(NULLIF(ip_address, ""), session_id)'));
    }
}