<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;

it('requires authentication', function () {
    $this->getJson('/api/subscription')->assertStatus(401);
});


