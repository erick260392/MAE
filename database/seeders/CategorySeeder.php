<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Mangueras', 'slug' => 'mangueras', 'description' => 'Mangueras hidráulicas y neumáticas'],
            ['name' => 'Conexiones', 'slug' => 'conexiones', 'description' => 'Conexiones y adaptadores hidráulicos'],
            ['name' => 'Accesorios', 'slug' => 'accesorios', 'description' => 'Accesorios y refacciones'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
