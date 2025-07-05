<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'name' => 'string',
        'status' => 'string',
    ];

    public function coaches()
    {
        return $this->hasMany(Coach::class);
    }
}
