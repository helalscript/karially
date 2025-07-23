<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverLatter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_profile_id',
        'template_id',
        'title',
        'template_name',
        'ai_prompt_id',
        'uploaded_doc',
        'generated_html',
        'status',
        'version',
        'is_final',
        'feedback_notes',
    ];

    protected $casts = [
        'user_profile_id' => 'integer',
        'template_id' => 'integer',
        'title' => 'string',
        'template_name' => 'string',
        'ai_prompt_id' => 'integer',
        'uploaded_doc' => 'string',
        'generated_html' => 'string',
        'status' => 'string',
        'version' => 'integer',
        'is_final' => 'boolean',
        'feedback_notes' => 'string',
    ];

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function aiPrompt()
    {
        return $this->belongsTo(AiPrompt::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'cover_latter_tags', 'cover_letter_id');
    }

}
