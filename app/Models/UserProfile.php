<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'job_title',
        'email',
        'phone',
        'website',
        'address',
        'summary',
        'education',
        'experience',
        'certifications',
        'languages',
        'skills',
        'projects',
        'awards',
        'social_links',
        'interests',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'full_name' => 'string',
        'job_title' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'website' => 'string',
        'address' => 'string',
        'summary' => 'string',
        'education' => 'array',
        'experience' => 'array',
        'certifications' => 'array',
        'languages' => 'array',
        'skills' => 'array',
        'projects' => 'array',
        'awards' => 'array',
        'social_links' => 'array',
        'interests' => 'string',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiPrompts()
    {
        return $this->hasMany(AiPrompt::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function coverLatters()
    {
        return $this->hasMany(CoverLatter::class);
    }
}
