<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverLatterTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'cover_letter_id',
        'tag_id',
    ];

    protected $casts = [
        'cover_letter_id' => 'integer',
        'tag_id' => 'integer',
    ];

    public function coverLetter()
    {
        return $this->belongsTo(CoverLatter::class, 'cover_letter_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
