<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'status',
        'start_date',
        'due_date',
        'is_active'
    ];
}
