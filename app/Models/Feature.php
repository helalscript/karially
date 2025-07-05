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

    public function packageFeatures()
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_features')
            ->withPivot('access_lavel', 'custom_tag', 'custom_value', 'status')
            ->withTimestamps();
    }
}
