<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coach_id',
        'booking_id',
        'rating',
        'comment',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'coach_id' => 'integer',
        'booking_id' => 'integer',
        'rating' => 'integer',
        'comment' => 'string',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
