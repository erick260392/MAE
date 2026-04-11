<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;

class Inventory extends Component
{
    public string $search = '';
    public string $filterCategory = '';

    public bool $showModal = false;
    public bool $showHistory = false;

    public ?int $productId = null;
    public string $productName = '';
    public int $currentStock = 0;

    public string $type = 'entrada';
    public string $quantity = '';
    public string $reason = '';
    public string $notes = '';

    public function openMovement(Product $product): void
    {
        $this->productId = $product->id;
        $this->productName = $product->name;
        $this->currentStock = $product->stock;
        $this->type = 'entrada';
        $this->quantity = '';
        $this->reason = '';
        $this->notes = '';
        $this->showModal = true;
        $this->showHistory = false;
    }

    public function openHistory(Product $product): void
    {
        $this->productId = $product->id;
        $this->productName = $product->name;
        $this->currentStock = $product->stock;
        $this->showHistory = true;
        $this->showModal = false;
    }

    public function save(): void
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'reason'   => 'required|max:150',
            'notes'    => 'nullable|max:500',
        ]);

        $product = Product::find($this->productId);

        if ($this->type === 'salida' && $this->quantity > $product->stock) {
            $this->addError('quantity', 'No hay suficiente stock. Stock actual: ' . $product->stock);
            return;
        }

        StockMovement::create([
            'product_id' => $this->productId,
            'type'       => $this->type,
            'quantity'   => $this->quantity,
            'reason'     => $this->reason,
            'notes'      => $this->notes ?: null,
        ]);

        $newStock = $this->type === 'entrada'
            ? $product->stock + $this->quantity
            : $product->stock - $this->quantity;

        $product->update(['stock' => $newStock]);

        $this->showModal = false;
        $this->reset(['productId', 'productName', 'currentStock', 'quantity', 'reason', 'notes']);
    }

    public function render()
    {
        $products = Product::with('category')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->orderBy('name')
            ->get();

        $history = $this->showHistory && $this->productId
            ? StockMovement::where('product_id', $this->productId)->latest()->take(20)->get()
            : collect();

        return view('livewire.admin.inventory', [
            'products'   => $products,
            'categories' => \App\Models\Category::orderBy('name')->get(),
            'history'    => $history,
        ])->layout('layouts.admin', ['title' => 'Inventario']);
    }
}
