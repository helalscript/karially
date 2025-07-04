<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Category::factory()->count(5)->create();
         $categories = [
            'AI Expert',
            'Software Engineer',
            'Architecture',
            'Art & Media',
            'Urban Designer',
            'Metropolitan Architect',
            'Digital Marketing',
            'Data Analyst',
            'Cyber Security',
            'Cloud Computing',
            'Web Developer',
            'DevOps Engineer',
            'Graphic Designer',
            'UX Designer',
            'Product Manager',
            'Data Scientist',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
    }
}
