<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $table = 'package_features';

    protected $fillable = [
        'pakage_id',
        'feature_id',
        'access_lavel',
        'custom_tag',
        'custom_value',
        'description',
        'status',
    ];

    protected $casts = [
        'pakage_id' => 'integer',
        'feature_id' => 'integer',
        'access_lavel' => 'string',
        'custom_tag' => 'string',
        'custom_value' => 'integer',
        'description' => 'string',
        'status' => 'string',
    ];

     protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
