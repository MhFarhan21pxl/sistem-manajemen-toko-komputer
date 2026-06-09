<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Keyboard Mechanical'],
            ['name' => 'Mouse Gaming'],
            ['name' => 'Monitor'],
            ['name' => 'Komponen PC'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}