<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'duration',
        'status'
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'price' => 'decimal:2',
        'duration' => 'integer',
        'status' => 'string',
    ];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_feature');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
