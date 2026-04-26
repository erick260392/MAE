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
    protected const MINIMUM_METER_QUANTITY = 0.1;

    public array $items = [];

    public bool $showCart = false;

    public int $currentStep = 1;

    public bool $submitted = false;

    public ?string $submittedQuoteFolio = null;

    public string $name = '';

    public string $phone = '';

    public string $company = '';

    public string $email = '';

    public string $city = '';

    public string $notes = '';

    #[On('cart-add')]
    public function addProduct(int $productId, float $quantity = 1): void
    {
        $product = Product::find($productId);

        if (! $product) {
            return;
        }

        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] = $this->normalizeQuantity(
                $product->unit,
                $this->items[$productId]['quantity'] + $quantity
            );
        } else {
            $this->items[$productId] = $this->buildCartItem($product, $quantity);
        }

        $this->submitted = false;
        $this->currentStep = 1;
        $this->showCart = true;
    }

    public function setQuantity(int $productId, float $quantity): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        $minimum = $this->items[$productId]['unit'] === 'metro' ? self::MINIMUM_METER_QUANTITY : 1;

        if ($quantity < $minimum) {
            $this->remove($productId);

            return;
        }

        $this->items[$productId]['quantity'] = $this->normalizeQuantity($this->items[$productId]['unit'], $quantity);
    }

    public function updateQuantity(int $productId, string $quantity): void
    {
        $this->setQuantity($productId, (float) $quantity);
    }

    public function updateItemNotes(int $productId, string $notes): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        $this->items[$productId]['notes'] = trim($notes);
    }

    public function increment(int $productId): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        $step = $this->items[$productId]['unit'] === 'metro' ? self::MINIMUM_METER_QUANTITY : 1;
        $this->items[$productId]['quantity'] = $this->normalizeQuantity(
            $this->items[$productId]['unit'],
            $this->items[$productId]['quantity'] + $step
        );
    }

    public function decrement(int $productId): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        $step = $this->items[$productId]['unit'] === 'metro' ? self::MINIMUM_METER_QUANTITY : 1;

        if ($this->items[$productId]['quantity'] <= $step) {
            $this->remove($productId);

            return;
        }

        $this->items[$productId]['quantity'] = $this->normalizeQuantity(
            $this->items[$productId]['unit'],
            $this->items[$productId]['quantity'] - $step
        );
    }

    public function remove(int $productId): void
    {
        unset($this->items[$productId]);

        if (empty($this->items)) {
            $this->showCart = false;
            $this->currentStep = 1;
        }
    }

    public function getTotal(): float
    {
        return collect($this->items)->sum(fn ($i) => $i['price'] * $i['quantity']);
    }

    public function restoreStateFromBrowser(array $state): void
    {
        $persistedItems = collect($state['items'] ?? [])
            ->mapWithKeys(function (array $item, int|string $productId): array {
                $product = Product::find((int) $productId);

                if (! $product) {
                    return [];
                }

                return [
                    $product->id => $this->buildCartItem(
                        $product,
                        (float) ($item['quantity'] ?? 1),
                        (string) ($item['notes'] ?? '')
                    ),
                ];
            })
            ->all();

        $this->items = $persistedItems;
        $this->name = $this->sanitizePersistedText($state['name'] ?? '', 150);
        $this->phone = $this->sanitizePersistedText($state['phone'] ?? '', 20);
        $this->company = $this->sanitizePersistedText($state['company'] ?? '', 150);
        $this->email = $this->sanitizePersistedText($state['email'] ?? '', 150);
        $this->city = $this->sanitizePersistedText($state['city'] ?? '', 100);
        $this->notes = $this->sanitizePersistedText($state['notes'] ?? '', 500);
        $this->submitted = false;
        $this->submittedQuoteFolio = null;
        $this->showCart = (bool) ($state['showCart'] ?? false) && ! empty($this->items);
        $this->currentStep = (int) ($state['currentStep'] ?? 1) === 2 && ! empty($this->items) ? 2 : 1;
    }

    public function goToContactStep(): void
    {
        if (empty($this->items)) {
            $this->addError('items', 'Agrega al menos un producto para solicitar tu cotización.');

            return;
        }

        $this->resetErrorBag('items');
        $this->currentStep = 2;
    }

    public function backToSummaryStep(): void
    {
        $this->currentStep = 1;
    }

    public function startNewQuote(): void
    {
        $this->items = [];
        $this->reset(['name', 'phone', 'company', 'email', 'city', 'notes']);
        $this->submitted = false;
        $this->submittedQuoteFolio = null;
        $this->currentStep = 1;
        $this->showCart = true;
    }

    public function submitQuote(): void
    {
        if (empty($this->items)) {
            $this->addError('items', 'Agrega al menos un producto para solicitar tu cotización.');

            return;
        }

        $this->validate([
            'name' => 'required|min:2|max:150',
            'phone' => 'required|max:20',
            'company' => 'nullable|max:150',
            'email' => 'nullable|email|max:150',
            'city' => 'nullable|max:100',
            'notes' => 'nullable|max:500',
        ]);

        $customer = Customer::firstOrNew(['phone' => $this->phone]);
        $customer->fill([
            'name' => $this->name,
            'company' => $this->company ?: null,
            'email' => $this->email ?: null,
            'city' => $this->city ?: null,
        ]);
        $customer->save();

        $lineItems = collect($this->items)
            ->mapWithKeys(function (array $item, int|string $productId): array {
                $product = Product::find((int) $productId);

                if (! $product) {
                    return [];
                }

                return [
                    $product->id => [
                        'product' => $product,
                        'quantity' => $this->normalizeQuantity($product->unit, (float) $item['quantity']),
                        'notes' => ! empty($item['notes']) ? trim($item['notes']) : null,
                    ],
                ];
            });

        if ($lineItems->isEmpty()) {
            $this->addError('items', 'No pudimos restaurar los productos de tu cotización. Vuelve a agregarlos.');

            return;
        }

        $quote = Quote::create([
            'customer_id' => $customer->id,
            'notes' => $this->notes ?: null,
            'total' => $lineItems->sum(fn (array $item) => $item['product']->price * $item['quantity']),
        ]);

        foreach ($lineItems as $productId => $item) {
            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'unit_price' => $item['product']->price,
                'subtotal' => $item['product']->price * $item['quantity'],
                'notes' => $item['notes'],
            ]);
        }

        $this->items = [];
        $this->currentStep = 3;
        $this->submitted = true;
        $this->submittedQuoteFolio = $quote->folio;
        $this->reset(['name', 'phone', 'company', 'email', 'city', 'notes']);

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

    protected function buildCartItem(Product $product, float $quantity, string $notes = ''): array
    {
        return [
            'name' => $product->name,
            'unit' => $product->unit,
            'price' => (float) $product->price,
            'quantity' => $this->normalizeQuantity($product->unit, $quantity),
            'notes' => trim($notes),
        ];
    }

    protected function normalizeQuantity(string $unit, float $quantity): float|int
    {
        if ($unit === 'metro') {
            return max(self::MINIMUM_METER_QUANTITY, round($quantity, 1));
        }

        return max(1, (int) round($quantity));
    }

    protected function sanitizePersistedText(mixed $value, int $maxLength): string
    {
        return str((string) $value)->trim()->limit($maxLength, '')->toString();
    }
}
