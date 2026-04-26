<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;

class Categories extends Component
{
    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'description']);
        $this->showModal = true;
    }

    public function openEdit(Category $category): void
    {
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|min:2|max:100',
            'description' => 'nullable|max:255',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        if ($this->editingId) {
            Category::find($this->editingId)->update($data);
        } else {
            Category::create($data);
        }

        $this->showModal = false;
        $this->reset(['name', 'description', 'editingId']);
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    public function render()
    {
        $categories = Category::query()
            ->withCount('products')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get();

        return view('livewire.admin.categories', compact('categories'))
            ->layout('layouts.admin', ['title' => 'Categorías']);
    }
}
