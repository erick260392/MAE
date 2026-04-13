<div class="max-w-5xl space-y-4">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-6 space-y-4">

        {{-- Cliente y condiciones --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-sm text-gray-600 mb-1">Cliente <span class="text-red-500">*</span></label>
                <select wire:model="customer_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-orange-400">
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
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-orange-400"
                    placeholder="Ej: Crédito 30 días">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Notas</label>
                <input wire:model="notes" type="text"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-orange-400"
                    placeholder="Observaciones opcionales...">
            </div>
        </div>

        {{-- Items --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Productos</label>
            <div class="space-y-3">
                @foreach($items as $i => $item)
                <div class="bg-gray-50 rounded-xl p-3 space-y-2 relative">
                    {{-- Botón eliminar --}}
                    <button wire:click="removeItem({{ $i }})" type="button"
                        class="absolute top-2 right-2 text-gray-300 hover:text-red-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Producto</label>
                        <select wire:model.live="items.{{ $i }}.product_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-orange-400 bg-white">
                            <option value="">Seleccionar producto...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error("items.{$i}.product_id") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Cantidad</label>
                            <input wire:model.live="items.{{ $i }}.quantity" type="number" min="1"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-center focus:outline-none focus:border-orange-400 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Precio unit.</label>
                            <div class="relative">
                                <span class="absolute left-2 top-2.5 text-gray-400 text-xs">$</span>
                                <input wire:model.live="items.{{ $i }}.unit_price" type="number" step="0.01" min="0"
                                    class="w-full border border-gray-200 rounded-lg pl-5 pr-2 py-2.5 text-sm focus:outline-none focus:border-orange-400 bg-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">T. Entrega</label>
                            <input wire:model="items.{{ $i }}.delivery_time" type="text" placeholder="2 días"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-orange-400 bg-white">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <span class="text-sm font-semibold text-gray-800">
                            Subtotal: ${{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 2) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <button wire:click="addItem" type="button"
                class="mt-3 w-full sm:w-auto text-sm text-orange-500 hover:text-orange-600 font-medium flex items-center justify-center gap-1 border border-orange-200 rounded-lg px-4 py-2.5 hover:bg-orange-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar producto
            </button>
        </div>

        {{-- Totales --}}
        <div class="border-t border-gray-100 pt-4">
            <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>IVA (16%)</span>
                    <span>${{ number_format($iva, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200 pt-2">
                    <span>Total</span>
                    <span class="text-orange-500">${{ number_format($total, 2) }} <span class="text-xs font-normal text-gray-400">MXN</span></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex gap-3">
        <a href="{{ route('admin.quotes') }}"
            class="flex-1 sm:flex-none text-center px-4 py-3 text-sm text-gray-600 border border-gray-200 rounded-lg hover:text-gray-800">
            Cancelar
        </a>
        <button wire:click="save"
            class="flex-1 sm:flex-none px-6 py-3 text-sm bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
            Guardar cotización
        </button>
    </div>

</div>
