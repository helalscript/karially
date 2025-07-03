<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PakageFactory extends Factory
{
    protected $model = \App\Models\Pakage::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug,
            'image' => $this->faker->imageUrl(640, 480, 'business', true),
            'description' => $this->faker->paragraph,
            'trial_period_days' => $this->faker->numberBetween(0, 30),
            'billing_cycle' => $this->faker->randomElement(['monthly', 'yearly', 'lifetime']),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
