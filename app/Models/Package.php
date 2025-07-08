<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'trial_period_days',
        'billing_cycle',
        'price',
        'status',
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'image' => 'string',
        'description' => 'string',
        'trial_period_days' => 'integer',
        'billing_cycle' => 'string',
        'price' => 'decimal:2',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subcription::class);
    }

    public function packageFeatures()
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'package_features')
            ->withPivot('access_lavel', 'custom_tag', 'custom_value', 'description', 'status');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
