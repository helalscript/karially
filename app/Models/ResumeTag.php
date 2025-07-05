<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id',
        'tag_id',
    ];

    protected $casts = [
        'resume_id' => 'integer',
        'tag_id' => 'integer',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
