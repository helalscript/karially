<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'career_plan_id',
        'week_number',
        'title',
        'goal',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'career_plan_id' => 'integer',
        'week_number' => 'integer',
        'title' => 'string',
        'goal' => 'string',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function careerPlan()
    {
        return $this->belongsTo(CareerPlan::class);
    }

    public function weeklyTasks()
    {
        return $this->hasMany(WeeklyTask::class);
    }
}
