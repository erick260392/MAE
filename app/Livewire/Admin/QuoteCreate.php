<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use Livewire\Component;

class QuoteCreate extends Component
{
    public ?int $customer_id = null;
    public string $notes = '';
    public string $delivery_time = '';
    public string $conditions = 'Crédito 30 días';
    public array $items = [];

    public function mount(): void
    {
        $this->addItem();
    }

    public function addItem(): void
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'delivery_time' => ''];
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
                $this->items[$index]['unit_price'] = $product->price;
            }
        }
    }

    public function getSubtotal(): float
    {
        return collect($this->items)->sum(fn($item) =>
            ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0)
        );
    }

    public function getIva(): float
    {
        return $this->getSubtotal() * 0.16;
    }

    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getIva();
    }

    public function save()
    {
        $this->validate([
            'customer_id'               => 'required|exists:customers,id',
            'items'                     => 'required|array|min:1',
            'items.*.product_id'        => 'required|exists:products,id',
            'items.*.quantity'          => 'required|integer|min:1',
            'items.*.unit_price'        => 'required|numeric|min:0',
            'items.*.delivery_time'     => 'nullable|max:100',
            'notes'                     => 'nullable|max:500',
            'conditions'                => 'nullable|max:255',
        ]);

        $quote = Quote::create([
            'customer_id'   => $this->customer_id,
            'notes'         => $this->notes ?: null,
            'delivery_time' => $this->delivery_time ?: null,
            'conditions'    => $this->conditions,
            'total'         => $this->getTotal(),
        ]);

        foreach ($this->items as $item) {
            QuoteItem::create([
                'quote_id'      => $quote->id,
                'product_id'    => $item['product_id'],
                'quantity'      => $item['quantity'],
                'unit_price'    => $item['unit_price'],
                'subtotal'      => $item['quantity'] * $item['unit_price'],
                'delivery_time' => $item['delivery_time'] ?: null,
            ]);
        }

        return redirect()->route('admin.quotes');
    }

    public function render()
    {
        return view('livewire.admin.quote-create', [
            'customers' => Customer::orderBy('name')->get(),
            'products'  => Product::where('active', true)->with('category')->orderBy('name')->get(),
            'subtotal'  => $this->getSubtotal(),
            'iva'       => $this->getIva(),
            'total'     => $this->getTotal(),
        ])->layout('layouts.admin', ['title' => 'Nueva cotización']);
    }
}
