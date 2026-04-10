<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Livewire\Component;

class Customers extends Component
{
    public string $search = '';
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $company = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public string $city = '';
    public string $notes = '';

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'company', 'phone', 'email', 'address', 'city', 'notes']);
        $this->showModal = true;
    }

    public function openEdit(Customer $customer): void
    {
        $this->editingId = $customer->id;
        $this->name = $customer->name;
        $this->company = $customer->company ?? '';
        $this->phone = $customer->phone;
        $this->email = $customer->email ?? '';
        $this->address = $customer->address ?? '';
        $this->city = $customer->city ?? '';
        $this->notes = $customer->notes ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'    => 'required|min:2|max:150',
            'phone'   => 'required|max:20',
            'company' => 'nullable|max:150',
            'email'   => 'nullable|email|max:150',
            'address' => 'nullable|max:255',
            'city'    => 'nullable|max:100',
            'notes'   => 'nullable|max:500',
        ]);

        $data = [
            'name'    => $this->name,
            'company' => $this->company ?: null,
            'phone'   => $this->phone,
            'email'   => $this->email ?: null,
            'address' => $this->address ?: null,
            'city'    => $this->city ?: null,
            'notes'   => $this->notes ?: null,
        ];

        if ($this->editingId) {
            Customer::find($this->editingId)->update($data);
        } else {
            Customer::create($data);
        }

        $this->showModal = false;
        $this->reset(['name', 'company', 'phone', 'email', 'address', 'city', 'notes', 'editingId']);
    }

    public function delete(Customer $customer): void
    {
        $customer->delete();
    }

    public function render()
    {
        $customers = Customer::withCount('quotes')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('company', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get();

        return view('livewire.admin.customers', compact('customers'))
            ->layout('layouts.admin', ['title' => 'Clientes']);
    }
}
