<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_feature');
    }
}
