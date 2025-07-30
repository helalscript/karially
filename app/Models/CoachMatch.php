<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'navigating_career',
        'session_help',
        'current_role',
        'career_goal',
        'preferred_time',
        'additional_info',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'navigating_career' => 'string',
        'session_help' => 'string',
        'current_role' => 'string',
        'career_goal' => 'string',
        'preferred_time' => 'string',
        'additional_info' => 'string',
        'status' => 'string',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
