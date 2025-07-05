<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'avatar',
        'bio',
        'email',
        'calendly_user_id', // This field is used to store the Calendly user ID
        'calendly_link',
        'status',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'name' => 'string',
        'avatar' => 'string',
        'bio' => 'string',
        'email' => 'string',
        'calendly_user_id' => 'string',
        'calendly_link' => 'string',
        'status' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function socials()
    {
        return $this->hasMany(CoachSocial::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAvatarAttribute($value): string|null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        // Check if the request is an API request
        if (request()->is('api/*') && !empty($value)) {
            // Return the full URL for API requests
            return url($value);
        }

        // Return only the path for web requests
        return $value;
    }
}
