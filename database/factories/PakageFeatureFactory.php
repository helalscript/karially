<?php

namespace Database\Factories;

use App\Models\Feature;
use App\Models\Pakage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PakageFeatureFactory extends Factory
{
    protected $model = \App\Models\PakageFeature::class;

    public function definition(): array
    {
        return [
            'pakage_id' => Pakage::factory(),
            'feature_id' => Feature::inRandomOrder()->value('id') ?? Feature::factory()->create()->id,
            'access_lavel' => $this->faker->randomElement(['view_only', 'read_only', 'unlimited', 'custom']),
            'custom_tag' => $this->faker->word,
            'custom_value' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
