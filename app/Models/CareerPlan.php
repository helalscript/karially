<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'career_goal_id',
        'target_week',
        'description',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'career_goal_id' => 'integer',
        'target_week' => 'integer',
        'description' => 'string',
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

    public function weeks()
    {
        return $this->hasMany(Week::class);
    }
}
