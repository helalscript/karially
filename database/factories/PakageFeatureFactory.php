<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PakageFeatureFactory extends Factory
{
    protected $model = \App\Models\PakageFeature::class;

    public function definition(): array
    {
        return [
            'pakage_id' => \App\Models\Pakage::factory(),
            'feature_id' => \App\Models\Feature::factory(),
            'access_lavel' => $this->faker->randomElement(['view_only', 'read_only', 'unlimited', 'custom']),
            'custom_tag' => $this->faker->word,
            'custom_value' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
