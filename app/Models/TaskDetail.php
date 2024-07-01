<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_header_id',
        'sub_task_name',
        'is_ticked',
        'is_active'
    ];
}
