<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'html_body',
        'is_default',
        'status',
    ];

    protected $casts = [
        'type' => 'string',
        'html_body' => 'string',
        'is_default' => 'boolean',
        'status' => 'string',
    ];

    public function sections()
    {
        return $this->hasMany(TemplateSection::class);
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
