<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogProductSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('storage/app/public/products/catalogo-gates.csv');

        if (! file_exists($csvPath)) {
            $csvPath = base_path('storage/app/public/products/catalogo-with-images.csv');
        }

        if (! file_exists($csvPath)) {
            $csvPath = base_path('storage/app/public/products/catalogo.csv');
        }

        if (! file_exists($csvPath)) {
            $this->command?->warn("CSV de catálogo no encontrado: {$csvPath}");

            return;
        }

        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            $this->command?->warn("No se pudo abrir el CSV de catálogo: {$csvPath}");

            return;
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);
            $this->command?->warn('CSV de catálogo vacío.');

            return;
        }

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            if (! $data || empty($data['code']) || empty($data['name'])) {
                continue;
            }

            $name = trim($data['name']);
            $code = trim($data['code']);
            $unit = $this->normalizeUnit($data['unit'] ?? '');
            $categorySlug = $this->resolveCategorySlug($data, $name, $code);
            $categoryId = $this->categoryIdForSlug($categorySlug);
            $image = $this->resolveImage(trim($data['image'] ?? ''), trim($data['page'] ?? ''), $name);

            $product = Product::firstOrNew(['sku' => $code]);
            $product->slug = $this->ensureUniqueSlug(Str::slug("{$code} {$name}"), $product->id);
            $product->category_id = $categoryId;
            $product->name = $name;
            $product->description = $name;
            $product->unit = $unit;
            $product->image = $image;
            $product->active = true;
            $catalogPrice = $this->catalogPriceFor($code, $name);

            if ($catalogPrice !== null) {
                $product->price = $catalogPrice;
            }

            if (! $product->exists) {
                $product->price ??= 0.00;
                $product->stock = 0;
            }

            $product->save();
        }

        fclose($handle);
    }

    protected function normalizeUnit(string $unit): string
    {
        $unit = mb_strtolower(trim($unit));

        if (str_contains($unit, 'rollo')) {
            return 'rollo';
        }

        if (str_contains($unit, 'mts') || str_contains($unit, 'm ') || str_contains($unit, 'm"') || str_contains($unit, 'metro') || str_contains($unit, 'm$')) {
            return 'metro';
        }

        if (str_contains($unit, 'pieza') || str_contains($unit, 'pza') || str_contains($unit, 'pz')) {
            return 'pieza';
        }

        return 'pieza';
    }

    protected function resolveCategorySlug(array $data, string $name, string $code): string
    {
        $categorySlug = trim($data['category'] ?? '');

        if ($categorySlug !== '') {
            $categorySlug = Str::slug($categorySlug);

            if (Category::where('slug', $categorySlug)->exists()) {
                return $categorySlug;
            }
        }

        return $this->guessCategorySlug($name, $code);
    }

    protected function guessCategorySlug(string $name, string $code): string
    {
        $text = mb_strtoupper($name.' '.$code);

        if (str_contains($text, 'CILINDR') || str_contains($text, 'ACTUADOR')) {
            return 'cilindros-actuadores';
        }

        if (str_contains($text, 'NEUMATIC') || str_contains($text, 'AIRE') || str_contains($text, 'PU') || str_contains($text, 'POLIURETANO')) {
            return 'mangueras-neumaticas';
        }

        if (str_contains($text, 'MANGUERA')) {
            return 'mangueras-hidraulicas';
        }

        if (str_contains($text, 'VALVULA') || str_contains($text, 'VÁLVULA') || str_contains($text, 'VÁLVULAS')) {
            return 'accesorios-sellos';
        }

        if (str_contains($text, 'MANOMETRO') || str_contains($text, 'REGULADOR') || str_contains($text, 'FILTR') || str_contains($text, 'FILTRO')) {
            return 'frl-manometros';
        }

        if (str_contains($text, 'CAM LOCK')) {
            return 'cam-lock';
        }

        if (str_contains($text, 'COPLE') || str_contains($text, 'UNION Y') || str_contains($text, 'UNIÓN Y') || str_contains($text, 'RACOR')) {
            return 'coples-rapidos';
        }

        if (str_contains($text, 'TUBO') || str_contains($text, 'UJ') || str_contains($text, 'CONEXION') || str_contains($text, 'CONECTOR') || str_contains($text, 'ADAPTADOR') || str_contains($text, 'JIC') || str_contains($text, 'NPT') || str_contains($text, 'BSP')) {
            return str_contains($text, 'MM') || str_contains($text, '4MM') || str_contains($text, '6MM') || str_contains($text, '8MM') || str_contains($text, '10MM') ? 'conexiones-neumaticas' : 'conexiones-hidraulicas';
        }

        return 'accesorios';
    }

    protected function categoryIdForSlug(string $slug): int
    {
        $category = Category::firstWhere('slug', $slug);

        if ($category) {
            return $category->id;
        }

        return Category::firstWhere('slug', 'accesorios')->id;
    }

    protected function catalogPriceFor(string $code, string $name): ?float
    {
        $text = mb_strtoupper($code.' '.$name);

        if (str_contains($text, 'ADAPTAPIPE')) {
            return $this->adaptapipePriceFor($text);
        }

        return match (true) {
            str_contains($text, 'AIR MASTER') && str_contains($text, 'DIVING UMBILICAL') => 1250.00,
            str_contains($text, 'AG MASTER') && str_contains($text, 'SPRAY 800') => 219.00,
            str_contains($text, 'AG MASTER') && str_contains($text, 'SPRAY 600') => 169.00,
            str_contains($text, 'AG MASTER') && str_contains($text, 'SOLUTION') => 285.00,
            str_contains($text, 'AG MASTER') && str_contains($text, 'SCUBA') => 420.00,
            str_contains($text, 'AIR MASTER') && str_contains($text, 'RESPIRATOR') => 340.00,
            str_contains($text, 'AG MASTER') => 155.00,
            str_contains($text, 'HTS') => 980.00,
            str_contains($text, 'ADS-2') => 520.00,
            str_contains($text, 'BLACK WIND') => 690.00,
            str_contains($text, 'ADS AIR FLEX') => 610.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'STEAM PLUS') => 1100.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'STEAM') => 890.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'XTREME') && str_contains($text, '501') => 355.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'XTREME') && str_contains($text, '315') => 310.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'XTREME') && str_contains($text, '300 LOCK') => 210.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'XTREME') && str_contains($text, '300') => 285.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'XTREME') => 245.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'PLUS') && str_contains($text, 'LOCK') => 185.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'PLUS') => 165.00,
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'NC') => 160.00,
            str_contains($text, 'PLANT MASTER') => 145.00,
            str_contains($text, 'CHEM MASTER') && str_contains($text, 'FEP') && str_contains($text, 'CR') => 1850.00,
            str_contains($text, 'CHEM MASTER') && str_contains($text, 'FEP') => 1650.00,
            str_contains($text, 'CHEM MASTER') && str_contains($text, 'XLPE') && str_contains($text, 'CR') => 890.00,
            str_contains($text, 'CHEM MASTER') && str_contains($text, 'XLPE') => 750.00,
            str_contains($text, 'CHEM MASTER') && str_contains($text, 'UHMWPE') => 980.00,
            str_contains($text, 'CHEM MASTER') && str_contains($text, 'CPE') => 1080.00,
            str_contains($text, 'CHEM MASTER') && str_contains($text, 'EPDM') => 680.00,
            str_contains($text, 'CHEM MASTER') && str_contains($text, 'PAINT') => 520.00,
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'XTREME') => 1400.00,
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'BEVERAGE') && str_contains($text, '250') => 960.00,
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'BEVERAGE') => 780.00,
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'OILS') && str_contains($text, 'MEGAFLEX') => 760.00,
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'OILS') && str_contains($text, ' SD') => 680.00,
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'OILS') => 520.00,
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'DRY GOODS') && str_contains($text, ' SD') => 620.00,
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'DRY GOODS') => 360.00,
            str_contains($text, 'FOOD KRYSTAL LIGHT') => 95.00,
            str_contains($text, 'FOOD KRYSTAL') => 130.00,
            str_contains($text, 'DOCK MASTER') => 1450.00,
            str_contains($text, 'FUEL MASTER') && str_contains($text, 'XTREME') => 920.00,
            str_contains($text, 'FUEL MASTER') && str_contains($text, 'CURB') => 460.00,
            str_contains($text, 'FUEL MASTER') && str_contains($text, 'PVC') => 185.00,
            str_contains($text, 'MULTI MASTER') => 390.00,
            str_contains($text, 'CLEAN MASTER') && str_contains($text, '1000') => 380.00,
            str_contains($text, 'CLEAN MASTER') => 160.00,
            str_contains($text, 'MINE MASTER') && str_contains($text, 'LONGWALL') => 1750.00,
            str_contains($text, 'MINE MASTER') && str_contains($text, 'AIR DRILL 600') => 980.00,
            str_contains($text, 'MINE MASTER') && str_contains($text, 'AIR DRILL 500 FR') => 820.00,
            str_contains($text, 'MINE MASTER') && str_contains($text, 'AIR DRILL 500') => 720.00,
            str_contains($text, 'MINE MASTER') && str_contains($text, 'ROCK DUST') => 540.00,
            str_contains($text, 'MINE MASTER') => 690.00,
            str_contains($text, 'MASTERCONCRET') => 950.00,
            str_contains($text, 'ELEPHANT TRUNK') => 780.00,
            str_contains($text, 'MASTERFLEX TPU') => 520.00,
            str_contains($text, 'MASTERFLEX ACERO') => 210.00,
            str_contains($text, 'MASTER FLEX NARANJA') => 65.00,
            str_contains($text, 'MASTERFLEX VERDE') => 70.00,
            str_contains($text, 'INDUSTRIAL TRAMADA PVC') => 60.00,
            str_contains($text, 'INDUSTRIAL PVC') => 45.00,
            str_contains($text, 'BLUE MASTER') => 115.00,
            str_contains($text, 'AIR MASTER') => 110.00,
            str_contains($text, 'ADAPTAMINE') => 360.00,
            str_contains($text, '429W') => 420.00,
            str_contains($text, '230W') => 280.00,
            str_contains($text, '319MB') => 430.00,
            str_contains($text, '24HW MEGAFLEX') => 760.00,
            str_contains($text, '24HW') => 690.00,
            str_contains($text, '20BHB') => 580.00,
            str_contains($text, '47HW') => 850.00,
            str_contains($text, '150SB') => 420.00,
            str_contains($text, '200SB') => 500.00,
            str_contains($text, '300SB') => 650.00,
            str_contains($text, '301SB') => 690.00,
            str_contains($text, '45HW') => 740.00,
            str_contains($text, '45W') => 620.00,
            str_contains($text, '19W') => 125.00,
            str_contains($text, '14W') => 105.00,
            str_contains($text, '78B') => 145.00,
            str_contains($text, '18B') => 135.00,
            str_contains($text, '22B') => 210.00,
            str_contains($text, '17HP') => 240.00,
            str_contains($text, '25HB') => 295.00,
            str_contains($text, '11W') => 120.00,
            str_contains($text, '100SB') => 330.00,
            str_contains($text, '35WL') => 160.00,
            str_contains($text, '35W') => 140.00,
            str_contains($text, '35B') => 135.00,
            str_contains($text, '16B') => 115.00,
            str_contains($text, '2B') => 95.00,
            str_contains($text, 'CAPRI') => 95.00,
            str_contains($text, 'PUERTA DE HORNOS') => 260.00,
            str_contains($text, 'ORCA') => 180.00,
            str_contains($text, 'FLAT BLUE') => 80.00,
            str_contains($text, 'WATERFLEX') => 110.00,
            str_contains($text, 'CONEXIONES') || str_contains($text, 'FÉRULAS') => 35.00,
            default => null,
        };
    }

    protected function adaptapipePriceFor(string $text): float
    {
        $prices = str_contains($text, '3/8')
            ? [25 => 55.00, 50 => 70.00, 100 => 90.00, 150 => 110.00, 200 => 135.00, 250 => 160.00, 300 => 190.00]
            : [25 => 45.00, 50 => 55.00, 100 => 70.00, 150 => 85.00, 200 => 105.00, 250 => 125.00, 300 => 145.00];

        if (preg_match('/ADAPTAPIPE[^0-9]+([0-9]+)/', $text, $matches) === 1) {
            return $prices[(int) $matches[1]] ?? 85.00;
        }

        return 85.00;
    }

    protected function ensureUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $original = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function resolveImage(string $image, string $page, string $name): ?string
    {
        $officialImage = $this->officialImageFor($name);

        if ($officialImage !== null) {
            return $officialImage;
        }

        if ($page !== '') {
            $pageImage = $this->pageImageForNumber($page);

            if ($pageImage !== null) {
                return $pageImage;
            }
        }

        if ($image !== '' && $this->imageExists($image)) {
            return $image;
        }

        return $this->guessImage($name);
    }

    protected function officialImageFor(string $name): ?string
    {
        $text = mb_strtoupper($name);

        $image = match (true) {
            str_contains($text, 'AG MASTER') || str_contains($text, 'AIR MASTER') => 'products/gates_official/ag-master-spray.jpg',
            str_contains($text, 'PLANT MASTER') && str_contains($text, 'XTREME') => 'products/gates_official/plant-master-xtreme.jpg',
            str_contains($text, 'CHEM MASTER') => 'products/gates_official/chem-master-fep.jpg',
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'XTREME') => 'products/gates_official/food-master-xtreme.jpg',
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'BEVERAGE') => 'products/gates_official/food-master-beverage.jpg',
            str_contains($text, 'FOOD MASTER') && str_contains($text, 'DRY GOODS') => 'products/gates_official/food-master-dry-goods.jpg',
            str_contains($text, 'FUEL MASTER') => 'products/gates_official/fuel-master-xtreme.jpg',
            str_contains($text, 'CLEAN MASTER') && str_contains($text, '1000') => 'products/gates_official/clean-master-washdown-1000.jpg',
            str_contains($text, 'CLEAN MASTER') => 'products/gates_official/clean-master-washdown.jpg',
            str_contains($text, 'MINE MASTER') => 'products/gates_official/mine-master-air-drill.jpg',
            str_contains($text, 'MULTI MASTER') => 'products/gates_official/multi-master-gmv.jpg',
            default => null,
        };

        if ($image !== null && $this->imageExists($image)) {
            return $image;
        }

        return null;
    }

    protected function pageImageForNumber(string $page): ?string
    {
        $firstChoice = sprintf('products/catalog_images_assigned/page%s_01.jpeg', $page);

        if ($this->imageExists($firstChoice)) {
            return $firstChoice;
        }

        $files = glob(storage_path("app/public/products/catalog_images_assigned/page{$page}_*.jpeg"));

        if ($files !== false && count($files) > 0) {
            return 'products/catalog_images_assigned/'.basename($files[0]);
        }

        return null;
    }

    protected function imageExists(string $image): bool
    {
        return file_exists(storage_path("app/public/{$image}"));
    }

    protected function guessImage(string $name): ?string
    {
        $text = mb_strtoupper($name);

        if (str_contains($text, 'CILINDR')) {
            return 'products/cilindro-neumatico.jpg';
        }

        if (str_contains($text, 'MANOMETR') || str_contains($text, 'REGULADOR')) {
            return 'products/manometro.jpg';
        }

        if (str_contains($text, 'VALVUL') || str_contains($text, 'VÁLVULA') || str_contains($text, 'VÁLVULAS')) {
            return 'products/valvula-bola.jpg';
        }

        if (str_contains($text, 'SELLO') || str_contains($text, 'SELL')) {
            return 'products/sellos.jpg';
        }

        if (str_contains($text, 'MANGUERA')) {
            if (str_contains($text, 'NEUMATIC') || str_contains($text, 'AIRE') || str_contains($text, 'PU')) {
                return 'products/manguera-neumatica.jpg';
            }

            return 'products/manguera-hidraulica.jpg';
        }

        if (str_contains($text, 'CONECTOR') || str_contains($text, 'CONEXION') || str_contains($text, 'RACOR') || str_contains($text, 'ADAPTADOR') || str_contains($text, 'JIC') || str_contains($text, 'NPT') || str_contains($text, 'BSP')) {
            return str_contains($text, 'MM') || str_contains($text, '4MM') || str_contains($text, '6MM') || str_contains($text, '8MM') || str_contains($text, '10MM') ? 'products/conexiones-neumaticas.jpg' : 'products/conexiones-hidraulicas.jpg';
        }

        return 'products/accesorios.jpg';
    }
}
