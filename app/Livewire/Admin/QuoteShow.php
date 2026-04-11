<?php

namespace App\Livewire\Admin;

use App\Models\Quote;
use Livewire\Component;

class QuoteShow extends Component
{
    public Quote $quote;

    public function updateStatus(string $status): void
    {
        $this->quote->update(['status' => $status]);
        $this->quote->refresh();
    }

    public function render()
    {
        return view('livewire.admin.quote-show', [
            'quote' => $this->quote->load('customer', 'items.product'),
        ])->layout('layouts.admin', ['title' => 'Cotización ' . $this->quote->folio]);
    }
}
