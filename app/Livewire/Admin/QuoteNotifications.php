<?php

namespace App\Livewire\Admin;

use App\Models\Quote;
use Livewire\Attributes\Poll;
use Livewire\Component;

class QuoteNotifications extends Component
{
    public int $unseenCount = 0;

    public bool $showBanner = false;

    public bool $showToast = false;

    public ?string $latestFolio = null;

    public ?string $latestCustomer = null;

    public function mount(): void
    {
        $this->unseenCount = Quote::whereNull('seen_at')->count();
    }

    #[Poll(3000)]
    public function checkNew(): void
    {
        $newCount = Quote::whereNull('seen_at')->count();

        if ($newCount > $this->unseenCount) {
            $latest = Quote::with('customer')->whereNull('seen_at')->latest()->first();
            $this->latestFolio = $latest?->folio;
            $this->latestCustomer = $latest?->customer?->name;
            $this->showToast = true;
            $this->showBanner = true;
        }

        $this->unseenCount = $newCount;
    }

    public function dismissBanner(): void
    {
        $this->showBanner = false;
    }

    public function dismissToast(): void
    {
        $this->showToast = false;
    }

    public function render()
    {
        return view('livewire.admin.quote-notifications');
    }
}
