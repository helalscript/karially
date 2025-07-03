<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_task_id',
        'title',
        'description',
        'type',
        'status',
    ];

    protected $casts = [
        'weekly_task_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'type' => 'string',
        'status' => 'string',
    ];

    public function weeklyTask()
    {
        return $this->belongsTo(WeeklyTask::class);
    }
}
