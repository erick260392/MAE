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
    public string $rfc = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public string $city = '';
    public string $zip_code = '';
    public string $notes = '';

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'company', 'rfc', 'phone', 'email', 'address', 'city', 'zip_code', 'notes']);
        $this->showModal = true;
    }

    public function openEdit(Customer $customer): void
    {
        $this->editingId = $customer->id;
        $this->name = $customer->name;
        $this->company = $customer->company ?? '';
        $this->rfc = $customer->rfc ?? '';
        $this->phone = $customer->phone;
        $this->email = $customer->email ?? '';
        $this->address = $customer->address ?? '';
        $this->city = $customer->city ?? '';
        $this->zip_code = $customer->zip_code ?? '';
        $this->notes = $customer->notes ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'     => 'required|min:2|max:150',
            'phone'    => 'required|max:20',
            'company'  => 'nullable|max:150',
            'rfc'      => 'nullable|max:20',
            'email'    => 'nullable|email|max:150',
            'address'  => 'nullable|max:255',
            'city'     => 'nullable|max:100',
            'zip_code' => 'nullable|max:10',
            'notes'    => 'nullable|max:500',
        ]);

        $data = [
            'name'     => $this->name,
            'company'  => $this->company ?: null,
            'rfc'      => $this->rfc ?: null,
            'phone'    => $this->phone,
            'email'    => $this->email ?: null,
            'address'  => $this->address ?: null,
            'city'     => $this->city ?: null,
            'zip_code' => $this->zip_code ?: null,
            'notes'    => $this->notes ?: null,
        ];

        if ($this->editingId) {
            Customer::find($this->editingId)->update($data);
        } else {
            Customer::create($data);
        }

        $this->showModal = false;
        $this->reset(['name', 'company', 'rfc', 'phone', 'email', 'address', 'city', 'zip_code', 'notes', 'editingId']);
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
