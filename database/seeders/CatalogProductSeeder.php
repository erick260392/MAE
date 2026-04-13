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
        $csvPath = base_path('storage/app/public/products/catalogo-with-images.csv');

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
            $categorySlug = $this->guessCategorySlug($name, $code);
            $categoryId = $this->categoryIdForSlug($categorySlug);
            $image = trim($data['image'] ?? '');
            if ($image === '') {
                $image = $this->guessImage($name);
            }
            $slug = Str::slug("{$code} {$name}");

            $product = Product::firstOrNew(['slug' => $slug]);
            $product->sku = $code;
            $product->category_id = $categoryId;
            $product->name = $name;
            $product->description = $name;
            $product->unit = $unit;
            $product->image = $image;
            $product->active = true;

            if (! $product->exists) {
                $product->price = 0.00;
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
