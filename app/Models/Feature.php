<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'features';

    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pakageFeatures()
    {
        return $this->hasMany(PakageFeature::class);
    }

    public function pakages()
    {
        return $this->belongsToMany(Pakage::class, 'pakage_features')
            ->withPivot('access_lavel', 'custom_tag', 'custom_value', 'status')
            ->withTimestamps();
    }
}
