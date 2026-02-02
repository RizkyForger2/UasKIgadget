<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Flagship', 'slug' => 'flagship', 'description' => 'HP flagship kelas premium', 'icon' => 'fa-crown'],
            ['name' => 'Mid Range', 'slug' => 'mid-range', 'description' => 'HP kelas menengah', 'icon' => 'fa-star'],
            ['name' => 'Budget', 'slug' => 'budget', 'description' => 'HP budget-friendly', 'icon' => 'fa-dollar-sign'],
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'HP gaming khusus', 'icon' => 'fa-gamepad'],
            ['name' => 'Foldable', 'slug' => 'foldable', 'description' => 'HP lipat/foldable', 'icon' => 'fa-mobile-screen'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}