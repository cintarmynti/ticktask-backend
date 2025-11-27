<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
        protected $fillable = [
        'user_id',
        'title',
        'description',
        'deadline',
        'start_at',
        'end_at',
        'is_done',
        'type',
        'is_priority'
    ];
}
