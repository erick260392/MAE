<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalProducts' => Product::count(),
            'totalCustomers' => Customer::count(),
            'pendingQuotes' => Quote::where('status', 'pendiente')->count(),
            'totalQuotes' => Quote::count(),
            'recentQuotes' => Quote::with('customer')->latest()->take(5)->get(),
        ])->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
