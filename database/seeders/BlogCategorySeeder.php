<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            'Cyber Security Tool',
            'Cyber Security',
            'Network Security',
            'Application Security',
            'Information or Data Security',
            'Cloud Security',
            'Mobile Security',
            'Endpoint Security',
            'Critical Infrastructure Security',
            'Internet of Things (IoT) Security',
        ];

        foreach ($categories as $category) {
            // Check if the category already exists in the database
            $existingCategory = BlogCategory::where('title', $category)->first();

            if (!$existingCategory) {

                $slug = Str::slug($category);

                // Category does not exist, so create it
                BlogCategory::create(['title' => $category, 'slug' => $slug]);
            }
        }
    }
}
