<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">

    <div class="space-y-6">
        <div class="mae-toolbar">
            <div class="min-w-0">
                <p class="mae-kicker">Nueva venta</p>
                <h2 class="font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">Crear cotización</h2>
            </div>
            <a href="{{ route('admin.quotes') }}" class="mae-btn-secondary">
                Volver
            </a>
        </div>

        <div class="mae-panel p-5 lg:p-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Cliente <span class="text-red-300">*</span></label>
                    <select wire:model="customer_id" class="mae-input">
                        <option value="">Seleccionar cliente...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}{{ $customer->company ? ' - '.$customer->company : '' }}</option>
                        @endforeach
                    </select>
                    @error('customer_id') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Condiciones comerciales</label>
                    <input wire:model="conditions" type="text" class="mae-input" placeholder="Ej: Crédito 30 días">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Notas</label>
                    <input wire:model="notes" type="text" class="mae-input" placeholder="Observaciones opcionales...">
                </div>
            </div>
        </div>

        <div class="mae-panel overflow-hidden">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4">
                <div>
                    <p class="mae-kicker">Partidas</p>
                    <h3 class="mt-1 font-display text-xl font-bold uppercase tracking-[0.14em] text-white">Productos</h3>
                </div>
                <button wire:click="addItem" type="button" class="mae-btn-primary px-3 py-2 text-xs">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Agregar
                </button>
            </div>

            <div class="space-y-3 p-4">
                @foreach($items as $i => $item)
                    <div wire:key="quote-item-{{ $i }}" class="rounded-xl border border-white/10 bg-white/5 p-4">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <span class="rounded-full border border-mae-gold/30 bg-mae-gold/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-mae-gold">Partida {{ $i + 1 }}</span>
                            <button wire:click="removeItem({{ $i }})" type="button" class="mae-danger-icon h-8 w-8">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(0,1.4fr)_100px_130px_120px_110px_130px]">
                            <div class="space-y-2">
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-[#8ea6c5]">Producto</label>
                                <input wire:model.live.debounce.300ms="items.{{ $i }}.product_search" type="text" class="mae-input" placeholder="Buscar por nombre o SKU">
                                <select wire:model.live="items.{{ $i }}.product_id" class="mae-input">
                                    <option value="">Seleccionar producto...</option>
                                    @foreach($this->productOptionsFor($i) as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->sku ? $product->sku.' - ' : '' }}{{ $product->name }} · ${{ number_format($product->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($item['product_label'] ?? false)
                                    <p class="text-xs text-[#8ea6c5]">Seleccionado: <span class="text-white">{{ $item['product_label'] }}</span></p>
                                @endif
                                @error("items.{$i}.product_id") <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-[#8ea6c5]">Cantidad</label>
                                <input wire:model.live="items.{{ $i }}.quantity" type="number" min="1" class="mae-input text-center">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-[#8ea6c5]">Precio unit.</label>
                                <input wire:model.live="items.{{ $i }}.unit_price" type="number" step="0.01" min="0" class="mae-input">
                                @if((float) ($item['original_unit_price'] ?? 0) > 0)
                                    <p class="mt-1 text-xs text-[#8ea6c5]">Base: ${{ number_format((float) $item['original_unit_price'], 2) }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-[#8ea6c5]">Desc.</label>
                                <select wire:model.live="items.{{ $i }}.discount_type" class="mae-input">
                                    <option value="none">Sin desc.</option>
                                    <option value="percent">%</option>
                                    <option value="fixed">$</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-[#8ea6c5]">Valor desc.</label>
                                <input wire:model.live="items.{{ $i }}.discount_value" type="number" step="0.01" min="0" class="mae-input">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-[#8ea6c5]">Entrega</label>
                                <input wire:model="items.{{ $i }}.delivery_time" type="text" placeholder="2 días" class="mae-input">
                            </div>
                        </div>

                        @php
                            $itemGross = ((float) ($item['quantity'] ?? 0)) * ((float) ($item['unit_price'] ?? 0));
                            $itemDiscountType = (string) ($item['discount_type'] ?? 'none');
                            $itemDiscountValue = (float) ($item['discount_value'] ?? 0);
                            $itemDiscount = match ($itemDiscountType) {
                                'percent' => $itemGross * min($itemDiscountValue, 100) / 100,
                                'fixed' => $itemDiscountValue,
                                default => 0,
                            };
                            $itemNet = max(0, $itemGross - min($itemGross, $itemDiscount));
                        @endphp

                        <div class="mt-3 flex justify-end">
                            <span class="rounded-lg border border-white/10 bg-white/6 px-3 py-2 text-sm font-semibold text-white">
                                Neto partida:
                                <span class="text-mae-gold">${{ number_format($itemNet, 2) }}</span>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <aside class="space-y-4 xl:sticky xl:top-6 xl:self-start">
        <div class="mae-panel p-5">
            <p class="mae-kicker">Resumen</p>
            <h3 class="mt-1 font-display text-2xl font-bold uppercase tracking-[0.14em] text-white">Total</h3>

            <div class="mt-5 space-y-3">
                <div class="flex justify-between text-sm text-[#b7c6da]">
                    <span>Subtotal partidas</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                @if($itemsDiscountTotal > 0)
                    <div class="flex justify-between text-sm text-yellow-200">
                        <span>Descuentos en productos</span>
                        <span>-${{ number_format($itemsDiscountTotal, 2) }}</span>
                    </div>
                @endif
                <div class="rounded-xl border border-white/10 bg-white/5 p-3">
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.14em] text-[#8ea6c5]">Descuento general</label>
                    <div class="grid grid-cols-[1fr_1fr] gap-2">
                        <select wire:model.live="discount_type" class="mae-input">
                            <option value="none">Sin descuento</option>
                            <option value="percent">Porcentaje</option>
                            <option value="fixed">Monto fijo</option>
                        </select>
                        <input wire:model.live="discount_value" type="number" step="0.01" min="0" class="mae-input" placeholder="0.00">
                    </div>
                    @if($quoteDiscountAmount > 0)
                        <p class="mt-2 text-sm text-yellow-200">Descuento aplicado: -${{ number_format($quoteDiscountAmount, 2) }}</p>
                    @endif
                </div>
                <div class="flex justify-between text-sm text-[#b7c6da]">
                    <span>Base gravable</span>
                    <span>${{ number_format($taxableSubtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-[#b7c6da]">
                    <span>IVA (16%)</span>
                    <span>${{ number_format($iva, 2) }}</span>
                </div>
                <div class="border-t border-white/10 pt-4">
                    <div class="flex items-end justify-between gap-4">
                        <span class="text-sm font-semibold text-white">Total</span>
                        <span class="font-display text-4xl font-bold text-mae-gold">${{ number_format($total, 2) }}</span>
                    </div>
                    <p class="mt-1 text-right text-xs text-[#8ea6c5]">MXN</p>
                </div>
            </div>
        </div>

        <div class="mae-panel-soft p-4">
            <div class="flex gap-2">
                <a href="{{ route('admin.quotes') }}" class="mae-btn-secondary flex-1">
                    Cancelar
                </a>
                <button wire:click="save" class="mae-btn-primary flex-1">
                    Guardar
                </button>
            </div>
        </div>
    </aside>

</div>
