<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Mangueras Hidráulicas',  'slug' => 'mangueras-hidraulicas',  'description' => 'Mangueras de alta presión 1SN, 2SN, 4SP para sistemas hidráulicos industriales'],
            ['name' => 'Mangueras Neumáticas',   'slug' => 'mangueras-neumaticas',   'description' => 'Mangueras de poliuretano, nylon y espiral para aire comprimido'],
            ['name' => 'Mangueras Industriales', 'slug' => 'mangueras-industriales', 'description' => 'Mangueras para combustible, agua, silicón, alimentos y multipropósito'],
            ['name' => 'Conexiones Hidráulicas', 'slug' => 'conexiones-hidraulicas', 'description' => 'Crimpables JIC, NPT, BSP, cara plana y bridas para sistemas hidráulicos'],
            ['name' => 'Conexiones Neumáticas',  'slug' => 'conexiones-neumaticas',  'description' => 'Racores rápidos, conectores rectos, codos y tees para sistemas neumáticos'],
            ['name' => 'Cilindros y Actuadores', 'slug' => 'cilindros-actuadores', 'description' => 'Cilindros neumáticos e hidráulicos y actuadores para control de movimiento'],
            ['name' => 'Accesorios y Sellos',   'slug' => 'accesorios-sellos',   'description' => 'Accesorios, sellos y elementos de montaje para sistemas hidráulicos y neumáticos'],
            ['name' => 'Coples Rápidos',         'slug' => 'coples-rapidos',         'description' => 'Coples y niples rápidos neumáticos e hidráulicos en bronce y acero'],
            ['name' => 'Cam Lock',               'slug' => 'cam-lock',               'description' => 'Conexiones tipo Cam Lock en aluminio para transferencia de fluidos'],
            ['name' => 'FRL y Manómetros',       'slug' => 'frl-manometros',         'description' => 'Filtros, reguladores, lubricadores y manómetros con glicerina'],
            ['name' => 'Accesorios',             'slug' => 'accesorios',             'description' => 'Abrazaderas, fundas, equipos de crimpado y accesorios industriales'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
