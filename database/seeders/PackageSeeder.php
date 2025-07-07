<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Insert Packages
        $packages = [
            ['title' => 'Free Plan', 'slug' => Str::slug('Free Plan'), 'price' => 0.00, 'billing_cycle' => 'monthly'],
            ['title' => 'Basic Plan', 'slug' => Str::slug('Basic Plan'), 'price' => 19.99, 'billing_cycle' => 'monthly'],
            ['title' => 'Standard Plan', 'slug' => Str::slug('Standard Plan'), 'price' => 34.99, 'billing_cycle' => 'monthly'],
            ['title' => 'Premium Plan', 'slug' => Str::slug('Premium Plan'), 'price' => 145.00, 'billing_cycle' => 'monthly'],
        ];

        foreach ($packages as &$package) {
            $package['status'] = 'active';
            $package['created_at'] = now();
            $package['updated_at'] = now();
        }

        DB::table('packages')->insert($packages);

        // Fetch package IDs
        $packageIds = DB::table('packages')->pluck('id', 'slug');

        // Step 2: Insert Features
        $features = [
            'Career Roadmap',
            'Progress Tracker',
            'Resume Builder',
            'Cover Letter',
            'Slack Community',
            'Priority Feature Access',
            'AI Career Prompts',
            '1:1 Coaching Call',
            'Cancel Anytime',
        ];

        $featureMap = [];
        foreach ($features as $feature) {
            $id = DB::table('features')->insertGetId([
                'title' => $feature,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $featureMap[$feature] = $id;
        }

        // Step 3: Map Package Features (pivot table)
        $packageFeatures = [

            // Free Plan
            ['package' => 'free-plan', 'feature' => 'Career Roadmap', 'access_lavel' => 'view_only', 'description' => 'View-only access to Career Roadmap'],
            ['package' => 'free-plan', 'feature' => 'Progress Tracker', 'access_lavel' => 'custom', 'custom_tag' => 'goal', 'custom_value' => 1, 'description' => 'Track 1 goal'],
            ['package' => 'free-plan', 'feature' => 'Resume Builder', 'access_lavel' => 'custom', 'custom_tag' => 'resume', 'custom_value' => 1, 'description' => 'Build 1 resume'],
            ['package' => 'free-plan', 'feature' => 'Cover Letter', 'access_lavel' => 'custom', 'custom_tag' => 'cover_letter', 'custom_value' => 1, 'description' => 'Create 1 cover letter'],
            ['package' => 'free-plan', 'feature' => 'Slack Community', 'access_lavel' => 'read_only', 'description' => 'Read-only access to community'],

            // Basic Plan
            ['package' => 'basic-plan', 'feature' => 'Career Roadmap', 'access_lavel' => 'unlimited', 'description' => 'Full access to Career Roadmap'],
            ['package' => 'basic-plan', 'feature' => 'Progress Tracker', 'access_lavel' => 'custom', 'custom_tag' => 'goal', 'custom_value' => 3, 'description' => 'Track up to 3 goals'],
            ['package' => 'basic-plan', 'feature' => 'Resume Builder', 'access_lavel' => 'custom', 'custom_tag' => 'resume', 'custom_value' => 2, 'description' => '2 resumes/month'],
            ['package' => 'basic-plan', 'feature' => 'Cover Letter', 'access_lavel' => 'custom', 'custom_tag' => 'cover_letter', 'custom_value' => 2, 'description' => '2 cover letters/month'],
            ['package' => 'basic-plan', 'feature' => 'Slack Community', 'access_lavel' => 'read_only', 'description' => 'Read-only access to Slack'],
            ['package' => 'basic-plan', 'feature' => 'Cancel Anytime', 'access_lavel' => 'view_only', 'description' => 'Cancel anytime'],

            // Standard Plan
            ['package' => 'standard-plan', 'feature' => 'Career Roadmap', 'access_lavel' => 'unlimited', 'description' => 'Access with Smart Milestones'],
            ['package' => 'standard-plan', 'feature' => 'Progress Tracker', 'access_lavel' => 'unlimited', 'description' => 'Unlimited goal tracking'],
            ['package' => 'standard-plan', 'feature' => 'Resume Builder', 'access_lavel' => 'unlimited', 'description' => 'Unlimited resume building'],
            ['package' => 'standard-plan', 'feature' => 'Cover Letter', 'access_lavel' => 'unlimited', 'description' => 'Unlimited cover letters'],
            ['package' => 'standard-plan', 'feature' => 'Slack Community', 'access_lavel' => 'unlimited', 'description' => 'Full access with live events'],
            ['package' => 'standard-plan', 'feature' => 'Priority Feature Access', 'access_lavel' => 'view_only', 'description' => 'Early access to new features'],
            ['package' => 'standard-plan', 'feature' => 'Cancel Anytime', 'access_lavel' => 'view_only', 'description' => 'Cancel anytime'],

            // Premium Plan
            ['package' => 'premium-plan', 'feature' => 'Career Roadmap', 'access_lavel' => 'view_only', 'description' => 'Strategy-guided roadmap (view-only)'],
            ['package' => 'premium-plan', 'feature' => 'Progress Tracker', 'access_lavel' => 'unlimited', 'description' => 'Unlimited goal tracking'],
            ['package' => 'premium-plan', 'feature' => 'Resume Builder', 'access_lavel' => 'unlimited', 'description' => 'Unlimited resume building'],
            ['package' => 'premium-plan', 'feature' => 'Cover Letter', 'access_lavel' => 'unlimited', 'description' => 'Unlimited cover letters'],
            ['package' => 'premium-plan', 'feature' => 'Slack Community', 'access_lavel' => 'unlimited', 'description' => 'VIP channel access'],
            ['package' => 'premium-plan', 'feature' => 'AI Career Prompts', 'access_lavel' => 'view_only', 'description' => 'Personalized career prompts'],
            ['package' => 'premium-plan', 'feature' => '1:1 Coaching Call', 'access_lavel' => 'custom', 'custom_tag' => 'coaching_call', 'custom_value' => 1, 'description' => '1 coaching call/month (60 min)'],
            ['package' => 'premium-plan', 'feature' => 'Priority Feature Access', 'access_lavel' => 'view_only', 'description' => 'Priority access to all features'],
            ['package' => 'premium-plan', 'feature' => 'Cancel Anytime', 'access_lavel' => 'view_only', 'description' => 'Cancel anytime'],
        ];


        foreach ($packageFeatures as $pf) {
            DB::table('package_features')->insert([
                'package_id' => $packageIds[$pf['package']],
                'feature_id' => $featureMap[$pf['feature']],
                'access_lavel' => $pf['access_lavel'],
                'custom_tag' => $pf['custom_tag'] ?? null,
                'custom_value' => $pf['custom_value'] ?? null,
                'description' => $pf['description'] ?? null, // 👈 Add this line
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
