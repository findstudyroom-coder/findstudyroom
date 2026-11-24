<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamPreparation extends Model
{
    protected $table = 'exam_preparations';

    protected $fillable = [
        'title',
        'is_active',
    ];
}