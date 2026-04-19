<?php

namespace App\Livewire\Admin;

use App\Models\Quote;
use Livewire\Component;

class Quotes extends Component
{
    public string $search = '';

    public string $filterStatus = '';

    public function mount(): void
    {
        Quote::whereNull('seen_at')->update(['seen_at' => now()]);
    }

    public function updateStatus(Quote $quote, string $status): void
    {
        $quote->update(['status' => $status]);
    }

    public function delete(Quote $quote): void
    {
        $quote->delete();
    }

    public function render()
    {
        $quotes = Quote::with('customer')
            ->when($this->search, fn ($q) => $q->whereHas('customer', fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('company', 'like', "%{$this->search}%"))
                ->orWhere('folio', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->get();

        return view('livewire.admin.quotes', compact('quotes'))
            ->layout('layouts.admin', ['title' => 'Cotizaciones']);
    }
}
