<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPrompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_profile_id',
        'prompt_text',
        'used_for',
    ];

    protected $casts = [
        'user_profile_id' => 'integer',
        'prompt_text' => 'string',
        'used_for' => 'string',
    ];

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class);
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
