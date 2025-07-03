<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roadmap_name',
        'target_week',
        'description',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'roadmap_name' => 'string',
        'target_week' => 'integer',
        'description' => 'string',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function careerPlans()
    {
        return $this->hasMany(CareerPlan::class);
    }
}
