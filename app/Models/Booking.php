<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'user_id',
        'start_time',
        'calendly_link',
        'status',
    ];

    protected $casts = [
        'coach_id' => 'integer',
        'user_id' => 'integer',
        'start_time' => 'datetime',
        'calendly_link' => 'string',
        'status' => 'string',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
