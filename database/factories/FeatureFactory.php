<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeatureFactory extends Factory
{
    protected $model = \App\Models\Feature::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
