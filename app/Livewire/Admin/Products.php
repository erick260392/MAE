<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class Products extends Component
{
    use WithFileUploads;

    public string $search = '';

    public string $filterCategory = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $application = '';

    public string $price = '';

    public string $stock = '';

    public string $unit = 'pieza';

    public ?int $category_id = null;

    public bool $active = true;

    public $image;

    public ?string $currentImage = null;

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'description', 'application', 'price', 'stock', 'category_id', 'image', 'currentImage']);
        $this->unit = 'pieza';
        $this->active = true;
        $this->showModal = true;
    }

    public function openEdit(Product $product): void
    {
        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->application = $product->application ?? '';
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->unit = $product->unit;
        $this->category_id = $product->category_id;
        $this->active = $product->active;
        $this->currentImage = $product->image;
        $this->image = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|min:2|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|max:500',
            'application' => 'nullable|max:500',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'application' => $this->application,
            'price' => $this->price,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'category_id' => $this->category_id,
            'active' => $this->active,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        if ($this->editingId) {
            Product::find($this->editingId)->update($data);
        } else {
            Product::create($data);
        }

        $this->showModal = false;
        $this->reset(['name', 'description', 'application', 'price', 'stock', 'category_id', 'image', 'currentImage', 'editingId']);
    }

    public function toggleActive(Product $product): void
    {
        $product->update(['active' => ! $product->active]);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function render()
    {
        $products = Product::with('category')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn ($q) => $q->where('category_id', $this->filterCategory))
            ->orderBy('name')
            ->get();

        return view('livewire.admin.products', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Productos']);
    }
}
