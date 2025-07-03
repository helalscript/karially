<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'payable_type',
        'payable_id',
        'payment_method',
        'stripe_payment_id',
        'currency',
        'amount',
        'metadata',
        'type',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'payable_type' => 'string',
        'payable_id' => 'integer',
        'payment_method' => 'string',
        'stripe_payment_id' => 'string',
        'currency' => 'string',
        'amount' => 'integer',
        'metadata' => 'array',
        'type' => 'string', // since it's an ENUM stored as string
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relationship
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
