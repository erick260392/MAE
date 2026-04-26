<?php

namespace App\Livewire\Catalog;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
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

    public function addToCart(int $productId): void
    {
        $this->dispatch('cart-add', productId: $productId);
    }
    public function render()
    {
        $categories = Category::withCount(['products' => fn ($q) => $q->where('active', true)])->get();

        $products = Product::with('category')
            ->where('active', true)
            ->when($this->activeCategory, fn ($q) => $q->where('category_id', $this->activeCategory))
            ->when($this->search, fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%")
                ->orWhere('application', 'like', "%{$this->search}%")
                ->orWhere('sku', 'like', "%{$this->search}%")
            )
            ->orderBy('name')
            ->get();

        $productsData = $products->map(fn ($p) => [
            'id'          => $p->id,
            'name'        => $p->name,
            'category'    => $p->category->name,
            'description' => $p->description,
            'application' => $p->application,
            'image'       => $p->image ? Storage::url($p->image) : null,
            'stock'       => $p->stock,
            'unit'        => $p->unit,
        ])->toArray();

        return view('livewire.catalog.product-catalog', compact('categories', 'products', 'productsData'))
            ->layout('layouts.public');
    }
}
