<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'career_goal_id',
        'week_number',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'career_goal_id' => 'integer',
        'week_number' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function careerGoal()
    {
        return $this->belongsTo(CareerGoal::class);
    }

    public function weeklyTasks()
    {
        return $this->hasMany(WeeklyTask::class);
    }
}
