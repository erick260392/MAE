<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $mangueras = Category::where('slug', 'mangueras')->first()->id;
        $conexiones = Category::where('slug', 'conexiones')->first()->id;
        $accesorios = Category::where('slug', 'accesorios')->first()->id;

        $products = [
            // Mangueras
            ['category_id' => $mangueras, 'name' => 'Manguera Hidráulica 1/4"', 'slug' => 'manguera-hidraulica-1-4', 'description' => 'Manguera hidráulica de alta presión 1/4 pulgada', 'price' => 85.00, 'stock' => 500, 'unit' => 'metro'],
            ['category_id' => $mangueras, 'name' => 'Manguera Hidráulica 3/8"', 'slug' => 'manguera-hidraulica-3-8', 'description' => 'Manguera hidráulica de alta presión 3/8 pulgada', 'price' => 110.00, 'stock' => 400, 'unit' => 'metro'],
            ['category_id' => $mangueras, 'name' => 'Manguera Hidráulica 1/2"', 'slug' => 'manguera-hidraulica-1-2', 'description' => 'Manguera hidráulica de alta presión 1/2 pulgada', 'price' => 145.00, 'stock' => 300, 'unit' => 'metro'],
            ['category_id' => $mangueras, 'name' => 'Manguera Neumática 6mm', 'slug' => 'manguera-neumatica-6mm', 'description' => 'Manguera neumática poliuretano 6mm', 'price' => 18.00, 'stock' => 1000, 'unit' => 'metro'],
            ['category_id' => $mangueras, 'name' => 'Manguera Neumática 8mm', 'slug' => 'manguera-neumatica-8mm', 'description' => 'Manguera neumática poliuretano 8mm', 'price' => 22.00, 'stock' => 800, 'unit' => 'metro'],
            ['category_id' => $mangueras, 'name' => 'Manguera de Agua 1"', 'slug' => 'manguera-agua-1', 'description' => 'Manguera para agua de 1 pulgada', 'price' => 55.00, 'stock' => 600, 'unit' => 'metro'],

            // Conexiones
            ['category_id' => $conexiones, 'name' => 'Conector JIC 1/4" Macho', 'slug' => 'conector-jic-1-4-macho', 'description' => 'Conector JIC 37° 1/4 pulgada macho', 'price' => 45.00, 'stock' => 200, 'unit' => 'pieza'],
            ['category_id' => $conexiones, 'name' => 'Conector JIC 3/8" Macho', 'slug' => 'conector-jic-3-8-macho', 'description' => 'Conector JIC 37° 3/8 pulgada macho', 'price' => 55.00, 'stock' => 200, 'unit' => 'pieza'],
            ['category_id' => $conexiones, 'name' => 'Codo 90° NPT 1/4"', 'slug' => 'codo-90-npt-1-4', 'description' => 'Codo 90 grados NPT 1/4 pulgada', 'price' => 38.00, 'stock' => 150, 'unit' => 'pieza'],
            ['category_id' => $conexiones, 'name' => 'Tee NPT 1/2"', 'slug' => 'tee-npt-1-2', 'description' => 'Tee hidráulica NPT 1/2 pulgada', 'price' => 72.00, 'stock' => 100, 'unit' => 'pieza'],
            ['category_id' => $conexiones, 'name' => 'Adaptador BSP-NPT 1/4"', 'slug' => 'adaptador-bsp-npt-1-4', 'description' => 'Adaptador de BSP a NPT 1/4 pulgada', 'price' => 42.00, 'stock' => 120, 'unit' => 'pieza'],
            ['category_id' => $conexiones, 'name' => 'Niple Recto 3/8"', 'slug' => 'niple-recto-3-8', 'description' => 'Niple recto hidráulico 3/8 pulgada', 'price' => 35.00, 'stock' => 180, 'unit' => 'pieza'],

            // Accesorios
            ['category_id' => $accesorios, 'name' => 'Abrazadera de Acero 1"', 'slug' => 'abrazadera-acero-1', 'description' => 'Abrazadera de acero inoxidable 1 pulgada', 'price' => 12.00, 'stock' => 500, 'unit' => 'pieza'],
            ['category_id' => $accesorios, 'name' => 'Cinta Teflón 3/4"', 'slug' => 'cinta-teflon-3-4', 'description' => 'Cinta de teflón para sellado de roscas', 'price' => 8.00, 'stock' => 300, 'unit' => 'pieza'],
            ['category_id' => $accesorios, 'name' => 'Válvula de Bola 1/2"', 'slug' => 'valvula-bola-1-2', 'description' => 'Válvula de bola NPT 1/2 pulgada', 'price' => 185.00, 'stock' => 60, 'unit' => 'pieza'],
            ['category_id' => $accesorios, 'name' => 'Filtro de Línea 3/8"', 'slug' => 'filtro-linea-3-8', 'description' => 'Filtro de línea hidráulica 3/8 pulgada', 'price' => 320.00, 'stock' => 40, 'unit' => 'pieza'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
