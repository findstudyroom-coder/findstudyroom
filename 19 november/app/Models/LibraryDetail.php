<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;

class LibraryDetail extends Model
{
    use HasFactory;

    protected $table = 'library_details';

    protected $fillable = [
        'library_id',
        'name',
        'phone',
        'location',
        'plan_id',
        'validity',
        'start_date',
        'end_date',
        'capacity',
        'logo_url'
    ];

    public function library()
    {
        return $this->belongsTo(Library::class, 'library_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
