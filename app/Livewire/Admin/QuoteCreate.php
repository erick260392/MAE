<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Support\Collection;
use Livewire\Component;

class QuoteCreate extends Component
{
    public ?int $customer_id = null;

    public string $notes = '';

    public string $delivery_time = '';

    public string $conditions = 'Crédito 30 días';

    public string $discount_type = 'none';

    public string $discount_value = '0';

    public array $items = [];

    public function mount(): void
    {
        $this->addItem();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id' => '',
            'product_search' => '',
            'product_label' => '',
            'quantity' => 1,
            'original_unit_price' => 0,
            'unit_price' => 0,
            'discount_type' => 'none',
            'discount_value' => 0,
            'delivery_time' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        if (empty($this->items)) {
            $this->addItem();
        }
    }

    public function updatedItems($value, $key): void
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'product_id' && $value) {
            $product = Product::find($value);
            if ($product) {
                $this->items[$index]['product_label'] = $product->name;
                $this->items[$index]['product_search'] = $product->sku ?: $product->name;
                $this->items[$index]['original_unit_price'] = $product->price;
                $this->items[$index]['unit_price'] = $product->price;
            }
        }
    }

    public function getSubtotal(): float
    {
        return round(collect($this->items)->sum(
            fn (array $item): float => $this->lineSubtotal($item)
        ), 2);
    }

    public function getItemsDiscountTotal(): float
    {
        return round(collect($this->items)->sum(
            fn (array $item): float => $this->lineDiscountAmount($item)
        ), 2);
    }

    public function getQuoteDiscountAmount(): float
    {
        return $this->discountAmount(
            $this->getSubtotal(),
            $this->discount_type,
            (float) $this->discount_value,
        );
    }

    public function getIva(): float
    {
        return round($this->getTaxableSubtotal() * 0.16, 2);
    }

    public function getTotal(): float
    {
        return round($this->getTaxableSubtotal() + $this->getIva(), 2);
    }

    public function getTaxableSubtotal(): float
    {
        return round(max(0, $this->getSubtotal() - $this->getQuoteDiscountAmount()), 2);
    }

    public function productOptionsFor(int $index): Collection
    {
        $item = $this->items[$index] ?? [];
        $search = trim((string) ($item['product_search'] ?? ''));
        $selectedProductId = (int) ($item['product_id'] ?? 0);

        $products = Product::query()
            ->where('active', true)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->with('category')
            ->orderBy('name')
            ->limit(20)
            ->get();

        if ($selectedProductId !== 0 && $products->doesntContain('id', $selectedProductId)) {
            $selectedProduct = Product::with('category')->find($selectedProductId);

            if ($selectedProduct) {
                $products->prepend($selectedProduct);
            }
        }

        return $products;
    }

    public function save()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'discount_type' => 'required|in:none,percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.original_unit_price' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_type' => 'required|in:none,percent,fixed',
            'items.*.discount_value' => 'required|numeric|min:0',
            'items.*.delivery_time' => 'nullable|max:100',
            'notes' => 'nullable|max:500',
            'conditions' => 'nullable|max:255',
        ]);

        $subtotal = $this->getSubtotal();
        $quoteDiscountAmount = $this->getQuoteDiscountAmount();
        $taxAmount = $this->getIva();

        $quote = Quote::create([
            'customer_id' => $this->customer_id,
            'subtotal' => $subtotal,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'discount_amount' => $quoteDiscountAmount,
            'tax_amount' => $taxAmount,
            'notes' => $this->notes ?: null,
            'delivery_time' => $this->delivery_time ?: null,
            'conditions' => $this->conditions,
            'total' => $this->getTotal(),
        ]);

        foreach ($this->items as $item) {
            $product = Product::find($item['product_id']);
            $originalUnitPrice = (float) ($item['original_unit_price'] ?: $product?->price ?: 0);
            $lineDiscountAmount = $this->lineDiscountAmount($item);

            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'original_unit_price' => $originalUnitPrice,
                'unit_price' => $item['unit_price'],
                'discount_type' => $item['discount_type'],
                'discount_value' => $item['discount_value'],
                'discount_amount' => $lineDiscountAmount,
                'subtotal' => $this->lineSubtotal($item),
                'delivery_time' => $item['delivery_time'] ?: null,
            ]);
        }

        return redirect()->route('admin.quotes');
    }

    public function render()
    {
        return view('livewire.admin.quote-create', [
            'customers' => Customer::orderBy('name')->get(),
            'subtotal' => $this->getSubtotal(),
            'itemsDiscountTotal' => $this->getItemsDiscountTotal(),
            'quoteDiscountAmount' => $this->getQuoteDiscountAmount(),
            'taxableSubtotal' => $this->getTaxableSubtotal(),
            'iva' => $this->getIva(),
            'total' => $this->getTotal(),
        ])->layout('layouts.admin', ['title' => 'Nueva cotización']);
    }

    protected function lineGrossTotal(array $item): float
    {
        return round(((float) ($item['quantity'] ?? 0)) * ((float) ($item['unit_price'] ?? 0)), 2);
    }

    protected function lineDiscountAmount(array $item): float
    {
        return $this->discountAmount(
            $this->lineGrossTotal($item),
            (string) ($item['discount_type'] ?? 'none'),
            (float) ($item['discount_value'] ?? 0),
        );
    }

    protected function lineSubtotal(array $item): float
    {
        return round(max(0, $this->lineGrossTotal($item) - $this->lineDiscountAmount($item)), 2);
    }

    protected function discountAmount(float $baseAmount, string $type, float $value): float
    {
        if ($baseAmount <= 0 || $value <= 0 || $type === 'none') {
            return 0.00;
        }

        $discount = $type === 'percent'
            ? $baseAmount * min($value, 100) / 100
            : $value;

        return round(min($baseAmount, $discount), 2);
    }
}
