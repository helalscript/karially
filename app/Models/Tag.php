<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $casts = [
        'name' => 'string',
    ];

    public function resumes()
    {
        return $this->belongsToMany(Resume::class, 'resume_tags');
    }

    public function coverLatters()
    {
        return $this->belongsToMany(CoverLatter::class, 'cover_latter_tags', 'tag_id', 'cover_letter_id');
    }
}
