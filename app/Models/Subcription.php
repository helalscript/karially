<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcription extends Model
{
    // Define the table name if it's not the default
    protected $table = 'subcriptions';

    // Fillable columns for mass assignment
    protected $fillable = [
        'user_id',
        'pakage_id',
        'stripe_subscription_id',
        'started_at',
        'ended_at',
        'status'
    ];

    // Column casts for type safety
    protected $casts = [
        'user_id' => 'integer',
        'pakage_id' => 'integer',
        'stripe_subscription_id' => 'string',
        'started_at' => 'date',
        'ended_at' => 'date',
        'status' => 'string',
    ];


    // A Subscription belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class); // Foreign key 'user_id'
    }

    public function pakage()
    {
        return $this->belongsTo(Pakage::class);
    }
}
