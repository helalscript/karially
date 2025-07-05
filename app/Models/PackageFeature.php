<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $table = 'pakage_features';

    protected $fillable = [
        'pakage_id',
        'feature_id',
        'access_lavel',
        'custom_tag',
        'custom_value',
        'status',
    ];

    protected $casts = [
        'pakage_id' => 'integer',
        'feature_id' => 'integer',
        'access_lavel' => 'string',
        'custom_tag' => 'string',
        'custom_value' => 'integer',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
