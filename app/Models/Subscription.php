<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'plan_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
