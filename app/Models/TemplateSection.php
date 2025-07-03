<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'section_key',
        'placeholder_tag',
    ];

    protected $casts = [
        'template_id' => 'integer',
        'section_key' => 'string',
        'placeholder_tag' => 'string',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
