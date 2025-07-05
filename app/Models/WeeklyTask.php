<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_id',
        'title',
        'description',
        'is_completed',
        'due_date',
        'status',
    ];

    protected $casts = [
        'week_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'is_completed' => 'boolean',
        'due_date' => 'date',
        'status' => 'string',
    ];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function topics()
    {
        return $this->hasMany(TaskTopic::class);
    }
}
