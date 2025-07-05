<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'title',
        'logo',
        'link',
        'status',
    ];

    protected $casts = [
        'coach_id' => 'integer',
        'title' => 'string',
        'logo' => 'string',
        'link' => 'string',
        'status' => 'string',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}
