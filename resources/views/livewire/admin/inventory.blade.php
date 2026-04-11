<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar producto..."
            class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm w-56 focus:outline-none focus:border-orange-400">
        <select wire:model.live="filterCategory"
            class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Producto</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Categoría</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Unidad</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Stock actual</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $product->unit }}</td>
                    <td class="px-6 py-3">
                        <span @class([
                            'font-semibold px-2 py-1 rounded-full text-xs',
                            'bg-red-100 text-red-600'    => $product->stock <= 5,
                            'bg-yellow-100 text-yellow-600' => $product->stock > 5 && $product->stock <= 20,
                            'bg-green-100 text-green-700'   => $product->stock > 20,
                        ])>
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <button wire:click="openHistory({{ $product->id }})"
                            class="text-gray-400 hover:text-orange-500 transition-colors" title="Ver historial">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                        <button wire:click="openMovement({{ $product->id }})"
                            class="text-gray-400 hover:text-orange-500 transition-colors" title="Registrar movimiento">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">No hay productos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal: Registrar movimiento --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Registrar movimiento</h2>
            <p class="text-sm text-gray-400 mb-4">{{ $productName }} — Stock actual: <span class="font-semibold text-gray-700">{{ $currentStock }}</span></p>

            <form wire:submit="save" class="space-y-4">

                {{-- Tipo --}}
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="$set('type', 'entrada')"
                        class="py-2.5 rounded-lg text-sm font-medium border-2 transition-colors {{ $type === 'entrada' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                        ↑ Entrada
                    </button>
                    <button type="button" wire:click="$set('type', 'salida')"
                        class="py-2.5 rounded-lg text-sm font-medium border-2 transition-colors {{ $type === 'salida' ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                        ↓ Salida
                    </button>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Cantidad <span class="text-red-500">*</span></label>
                    <input wire:model="quantity" type="number" min="1"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Motivo <span class="text-red-500">*</span></label>
                    <select wire:model="reason"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                        <option value="">Seleccionar...</option>
                        @if($type === 'entrada')
                            <option value="Compra a proveedor">Compra a proveedor</option>
                            <option value="Devolución de cliente">Devolución de cliente</option>
                            <option value="Ajuste de inventario">Ajuste de inventario</option>
                        @else
                            <option value="Venta">Venta</option>
                            <option value="Merma o daño">Merma o daño</option>
                            <option value="Ajuste de inventario">Ajuste de inventario</option>
                        @endif
                    </select>
                    @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Notas</label>
                    <textarea wire:model="notes" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400"></textarea>
                </div>

                @if($quantity)
                <div class="bg-gray-50 rounded-lg px-4 py-3 text-sm text-gray-600">
                    Stock resultante:
                    <span class="font-bold {{ $type === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $type === 'entrada' ? $currentStock + (int)$quantity : $currentStock - (int)$quantity }}
                    </span>
                </div>
                @endif

                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:text-gray-800">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors {{ $type === 'entrada' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                        Registrar {{ $type }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Modal: Historial --}}
    @if($showHistory)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[80vh] flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Historial de movimientos</h2>
                    <p class="text-sm text-gray-400">{{ $productName }}</p>
                </div>
                <button wire:click="$set('showHistory', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1">
                @if($history->isEmpty())
                    <p class="text-center text-gray-400 py-8 text-sm">Sin movimientos registrados.</p>
                @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium">Fecha</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium">Tipo</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium">Cantidad</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium">Motivo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($history as $movement)
                        <tr>
                            <td class="px-4 py-2 text-gray-500">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <span @class([
                                    'text-xs px-2 py-0.5 rounded-full font-medium',
                                    'bg-green-100 text-green-700' => $movement->type === 'entrada',
                                    'bg-red-100 text-red-700'     => $movement->type === 'salida',
                                ])>{{ ucfirst($movement->type) }}</span>
                            </td>
                            <td class="px-4 py-2 font-medium {{ $movement->type === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->type === 'entrada' ? '+' : '-' }}{{ $movement->quantity }}
                            </td>
                            <td class="px-4 py-2 text-gray-600">{{ $movement->reason }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

            <div class="pt-4 flex justify-between items-center border-t border-gray-100 mt-4">
                <span class="text-sm text-gray-500">Stock actual: <span class="font-semibold text-gray-800">{{ $currentStock }}</span></span>
                <button wire:click="openMovement({{ $productId }})"
                    class="text-sm bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition-colors">
                    + Registrar movimiento
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
