<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pakage;
use App\Models\Feature;
use App\Models\PakageFeature;

class PakageSeeder extends Seeder
{
    public function run(): void
    {
        $features = Feature::factory()->count(5)->create();

        Pakage::factory()
            ->count(3)
            ->create()
            ->each(function (Pakage $pakage) use ($features) {
                $assign = $features->random(rand(1, 3));
                foreach ($assign as $feature) {
                    PakageFeature::factory()->create([
                        'pakage_id' => $pakage->id,
                        'feature_id' => $feature->id,
                    ]);
                }
            });
    }
}
