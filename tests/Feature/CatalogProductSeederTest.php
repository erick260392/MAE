<?php

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CatalogProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('catalog product seeder assigns researched catalog prices', function () {
    $fallbackCategory = Category::create(['name' => 'Accesorios', 'slug' => 'accesorios']);

    Category::create(['name' => 'Mangueras Industriales', 'slug' => 'mangueras-industriales']);

    Product::create([
        'category_id' => $fallbackCategory->id,
        'sku' => 'CHEM-MASTER-XTREME-FEP-125-200-SD',
        'name' => 'CHEM MASTER® XTREME™ FEP (125 - 200) SD',
        'slug' => 'chem-master-xtreme-fep-125-200-sd',
        'price' => 0,
        'stock' => 3,
        'unit' => 'metro',
        'active' => true,
    ]);

    $this->seed(CatalogProductSeeder::class);
    $slugAfterFirstSeed = Product::where('sku', 'CHEM-MASTER-XTREME-FEP-125-200-SD')->value('slug');

    $this->seed(CatalogProductSeeder::class);

    expect(Product::where('sku', 'AG-MASTER-SPRAY-600')->value('price'))->toBe('169.00')
        ->and(Product::where('sku', 'CHEM-MASTER-XTREME-FEP-125-200-SD')->value('price'))->toBe('1650.00')
        ->and(Product::where('sku', 'ADAPTAPIPE-300-CON-TUBO-3-8')->value('price'))->toBe('190.00')
        ->and(Product::where('sku', 'AG-MASTER-SPRAY-600')->value('image'))->toBe('products/gates_official/ag-master-spray.jpg')
        ->and(Product::where('sku', 'CHEM-MASTER-XTREME-FEP-125-200-SD')->value('image'))->toBe('products/gates_official/chem-master-fep.jpg')
        ->and(Product::where('sku', 'CHEM-MASTER-XTREME-FEP-125-200-SD')->value('slug'))->toBe($slugAfterFirstSeed);
});
