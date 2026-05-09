<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Console\Kernel;

$cat = Category::firstWhere('slug', 'mangueras-industriales');
echo 'products='.Product::count().PHP_EOL;
echo 'category='.($cat ? $cat->id : 'none').PHP_EOL;
echo 'mangueras='.Product::where('category_id', $cat->id)->count().PHP_EOL;
echo 'AG_MASTER='.Product::where('name', 'like', '%AG MASTER%')->count().PHP_EOL;
echo 'FOOD_MASTER='.Product::where('name', 'like', '%FOOD MASTER%')->count().PHP_EOL;
echo '200SB='.Product::where('sku', '200SB')->count().PHP_EOL;
