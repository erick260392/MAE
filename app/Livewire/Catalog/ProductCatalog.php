<?php

namespace App\Livewire\Catalog;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class ProductCatalog extends Component
{
    public string $search = '';

    public ?int $activeCategory = null;

    public function setCategory(?int $id): void
    {
        $this->activeCategory = $id;
        $this->search = '';
    }

    public function addToCart(int $productId, float $quantity = 1): void
    {
        $this->dispatch('cart-add', $productId, $quantity);
    }

    public function render()
    {
        $categories = Category::withCount(['products' => fn ($q) => $q->where('active', true)])->get();

        $products = Product::with('category')
            ->where('active', true)
            ->when($this->activeCategory, fn ($q) => $q->where('category_id', $this->activeCategory))
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get();

        $productsData = $products->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'category' => $p->category->name,
                'description' => $p->description,
                'application' => $p->application,
                'image' => $p->image ? Storage::url($p->image) : null,
                'detailCuerdas' => $p->description ? Str::limit($p->description, 80) : 'Cuerda reforzada para uso industrial',
                'detailConexion' => $p->application ? Str::limit($p->application, 80) : 'Entrada de conexión estándar 1/4 NPT',
                'hoseMaterial' => str_contains(strtolower($p->description ?? ''), 'pvc') ? 'PVC reforzado' : (str_contains(strtolower($p->description ?? ''), 'nylon') ? 'Nylon reforzado' : 'Compuesto sintético'),
                'hoseThickness' => '3/8" (9.5 mm)',
                'hoseType' => 'Manguera flexible',
            ];
        })->toArray();

        $selectedProduct = null;

        return view('livewire.catalog.product-catalog', compact('categories', 'products', 'productsData', 'selectedProduct'))
            ->layout('layouts.public');
    }
}
