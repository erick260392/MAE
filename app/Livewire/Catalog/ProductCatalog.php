<?php

namespace App\Livewire\Catalog;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductCatalog extends Component
{
    public string $search = '';
    public ?int $activeCategory = null;
    public ?int $selectedProductId = null;

    public function setCategory(?int $id): void
    {
        $this->activeCategory = $id;
        $this->search = '';
    }

    public function openDetail(int $productId): void
    {
        $this->selectedProductId = $productId;
    }

    public function closeDetail(): void
    {
        $this->selectedProductId = null;
    }

    public function addToCart(int $productId): void
    {
        $this->dispatch('cart-add', productId: $productId);
    }

    public function render()
    {
        $categories = Category::withCount(['products' => fn($q) => $q->where('active', true)])->get();

        $products = Product::with('category')
            ->where('active', true)
            ->when($this->activeCategory, fn($q) => $q->where('category_id', $this->activeCategory))
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get();

        $selectedProduct = $this->selectedProductId
            ? Product::with('category')->find($this->selectedProductId)
            : null;

        return view('livewire.catalog.product-catalog', compact('categories', 'products', 'selectedProduct'))
            ->layout('layouts.public');
    }
}
