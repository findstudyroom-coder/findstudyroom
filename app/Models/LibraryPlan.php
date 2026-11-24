<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryPlan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'library_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'duration',
        'price',
        'description',
        'status',
    ];
}
