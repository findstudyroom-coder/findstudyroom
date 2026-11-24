<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryCallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_id',
        'admin_id',
        'status',
        'remark',
        'called_at',
        'next_followup_date',
        'next_followup_time',
    ];

    public function library()
    {
        return $this->belongsTo(Library::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
