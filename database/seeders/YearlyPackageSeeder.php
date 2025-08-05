<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class YearlyPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Define the monthly plans to base the yearly ones on
        $monthlyPlans = [
            'Basic Plan' => 19.99,
            'Standard Plan' => 34.99,
            'Premium Plan' => 145.00,
        ];

        $yearlyPackages = [];

        foreach ($monthlyPlans as $title => $monthlyPrice) {
            $slug = Str::slug($title) . '-yearly';
            $yearlyPrice = round(($monthlyPrice * 12) * 0.8, 2); // 20% discount

            $yearlyPackages[] = [
                'title' => $title,
                'slug' => $slug,
                'price' => $yearlyPrice,
                'billing_cycle' => 'yearly',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert yearly packages
        DB::table('packages')->insert($yearlyPackages);

        // Get package IDs
        $packageIds = DB::table('packages')->pluck('id', 'slug');

        // Get all feature IDs
        $featureIds = DB::table('features')->pluck('id', 'title');

        // Step 2: Clone features from monthly packages
        $monthlySlugs = [
            'basic-plan',
            'standard-plan',
            'premium-plan',
        ];

        foreach ($monthlySlugs as $monthlySlug) {
            $yearlySlug = $monthlySlug . '-yearly';

            $monthlyPackageId = $packageIds[$monthlySlug] ?? null;
            $yearlyPackageId = $packageIds[$yearlySlug] ?? null;

            if ($monthlyPackageId && $yearlyPackageId) {
                $features = DB::table('package_features')
                    ->where('package_id', $monthlyPackageId)
                    ->get();

                foreach ($features as $feature) {
                    DB::table('package_features')->insert([
                        'package_id' => $yearlyPackageId,
                        'feature_id' => $feature->feature_id,
                        'access_lavel' => $feature->access_lavel,
                        'custom_tag' => $feature->custom_tag,
                        'custom_value' => is_numeric($feature->custom_value) ? $feature->custom_value * 12 : $feature->custom_value,
                        'description' => $feature->description,
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
