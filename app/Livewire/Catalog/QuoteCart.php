<?php

namespace App\Livewire\Catalog;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Notifications\NewQuoteReceived;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\On;
use Livewire\Component;

class QuoteCart extends Component
{
    public array $items = [];

    public bool $showCart = false;

    public bool $showForm = false;

    public bool $submitted = false;

    public string $name = '';

    public string $phone = '';

    public string $company = '';

    public string $notes = '';

    #[On('cart-add')]
    public function addProduct(int $productId, float $quantity = 1): void
    {
        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] += $quantity;
        } else {
            $product = Product::find($productId);
            $this->items[$productId] = [
                'name' => $product->name,
                'unit' => $product->unit,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        }
        $this->showCart = true;
    }

    public function setQuantity(int $productId, float $quantity): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        $minimum = $this->items[$productId]['unit'] === 'metro' ? 0.1 : 1;

        if ($quantity < $minimum) {
            $this->remove($productId);

            return;
        }

        if ($this->items[$productId]['unit'] === 'metro') {
            $quantity = round($quantity, 1);
        }

        $this->items[$productId]['quantity'] = $quantity;
    }

    public function increment(int $productId): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        $step = $this->items[$productId]['unit'] === 'metro' ? 0.1 : 1;
        $this->items[$productId]['quantity'] = round($this->items[$productId]['quantity'] + $step, $this->items[$productId]['unit'] === 'metro' ? 1 : 0);
    }

    public function decrement(int $productId): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        $step = $this->items[$productId]['unit'] === 'metro' ? 0.1 : 1;

        if ($this->items[$productId]['quantity'] <= $step) {
            $this->remove($productId);

            return;
        }

        $this->items[$productId]['quantity'] = round($this->items[$productId]['quantity'] - $step, $this->items[$productId]['unit'] === 'metro' ? 1 : 0);
    }

    public function remove(int $productId): void
    {
        unset($this->items[$productId]);
        if (empty($this->items)) {
            $this->showCart = false;
            $this->showForm = false;
        }
    }

    public function getTotal(): float
    {
        return collect($this->items)->sum(fn ($i) => $i['price'] * $i['quantity']);
    }

    public function submitQuote(): void
    {
        $this->validate([
            'name' => 'required|min:2|max:150',
            'phone' => 'required|max:20',
            'company' => 'nullable|max:150',
            'notes' => 'nullable|max:500',
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => $this->phone],
            ['name' => $this->name, 'company' => $this->company ?: null]
        );

        $quote = Quote::create([
            'customer_id' => $customer->id,
            'notes' => $this->notes ?: null,
            'total' => $this->getTotal(),
        ]);

        foreach ($this->items as $productId => $item) {
            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        $this->items = [];
        $this->showForm = false;
        $this->submitted = true;
        $this->reset(['name', 'phone', 'company', 'notes']);

        try {
            Notification::route('mail', 'conexiones.mangueras@hotmail.com')
                ->notifyNow(new NewQuoteReceived($quote));
        } catch (\Throwable $e) {
            Log::error('Error enviando notificación: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.catalog.quote-cart', [
            'total' => $this->getTotal(),
            'count' => collect($this->items)->sum('quantity'),
        ]);
    }
}
