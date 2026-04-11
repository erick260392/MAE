<div class="max-w-5xl space-y-6">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">

        {{-- Cliente y condiciones --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Cliente <span class="text-red-500">*</span></label>
                <select wire:model="customer_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    <option value="">Seleccionar cliente...</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}{{ $customer->company ? ' — ' . $customer->company : '' }}</option>
                    @endforeach
                </select>
                @error('customer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Condiciones comerciales</label>
                <input wire:model="conditions" type="text"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400"
                    placeholder="Ej: Crédito 30 días, Contado, etc.">
            </div>
            <div class="col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Notas</label>
                <input wire:model="notes" type="text"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400"
                    placeholder="Observaciones opcionales...">
            </div>
        </div>

        {{-- Items --}}
        <div>
            <label class="block text-sm text-gray-600 mb-2">Productos</label>
            <div class="space-y-2">
                {{-- Encabezados --}}
                <div class="hidden sm:grid grid-cols-12 gap-2 text-xs text-gray-400 font-medium px-1">
                    <div class="col-span-4">Producto</div>
                    <div class="col-span-1 text-center">Cant.</div>
                    <div class="col-span-2 text-center">Precio unit.</div>
                    <div class="col-span-2 text-center">T. Entrega</div>
                    <div class="col-span-2 text-right">Subtotal</div>
                    <div class="col-span-1"></div>
                </div>

                @foreach($items as $i => $item)
                <div class="grid grid-cols-12 gap-2 items-center">
                    <div class="col-span-4">
                        <select wire:model.live="items.{{ $i }}.product_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                            <option value="">Seleccionar...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error("items.{$i}.product_id") <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-1">
                        <input wire:model.live="items.{{ $i }}.quantity" type="number" min="1"
                            class="w-full border border-gray-200 rounded-lg px-2 py-2 text-sm text-center focus:outline-none focus:border-orange-400">
                    </div>
                    <div class="col-span-2 relative">
                        <span class="absolute left-3 top-2 text-gray-400 text-sm">$</span>
                        <input wire:model.live="items.{{ $i }}.unit_price" type="number" step="0.01" min="0"
                            class="w-full border border-gray-200 rounded-lg pl-6 pr-2 py-2 text-sm focus:outline-none focus:border-orange-400">
                    </div>
                    <div class="col-span-2">
                        <input wire:model="items.{{ $i }}.delivery_time" type="text" placeholder="Ej: 2 días"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    </div>
                    <div class="col-span-2 text-right text-sm font-medium text-gray-700">
                        ${{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 2) }}
                    </div>
                    <div class="col-span-1 text-right">
                        <button wire:click="removeItem({{ $i }})" type="button"
                            class="text-gray-300 hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <button wire:click="addItem" type="button"
                class="mt-3 text-sm text-orange-500 hover:text-orange-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar producto
            </button>
        </div>

        {{-- Totales --}}
        <div class="flex justify-end pt-2 border-t border-gray-100">
            <div class="text-right space-y-1 min-w-48">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>IVA (16%)</span>
                    <span>${{ number_format($iva, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200 pt-1">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }} <span class="text-xs font-normal text-gray-400">MXN</span></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.quotes') }}"
            class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:text-gray-800">
            Cancelar
        </a>
        <button wire:click="save"
            class="px-6 py-2 text-sm bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
            Guardar cotización
        </button>
    </div>

</div>
