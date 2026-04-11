<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre, empresa o teléfono..."
            class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm w-72 focus:outline-none focus:border-orange-400">
        <button wire:click="openCreate"
            class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            + Nuevo cliente
        </button>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Nombre</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Empresa</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Teléfono</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Ciudad</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Cotizaciones</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $customer->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $customer->company ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $customer->phone }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $customer->city ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full font-medium">
                            {{ $customer->quotes_count }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <button wire:click="openEdit({{ $customer->id }})"
                            class="text-gray-400 hover:text-orange-500 transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $customer->id }})"
                            wire:confirm="¿Eliminar este cliente? También se eliminarán sus cotizaciones."
                            class="text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">No hay clientes.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $editingId ? 'Editar cliente' : 'Nuevo cliente' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input wire:model="name" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Empresa</label>
                        <input wire:model="company" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">RFC</label>
                        <input wire:model="rfc" type="text" placeholder="Ej: XAXX010101000"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Teléfono <span class="text-red-500">*</span></label>
                        <input wire:model="phone" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Correo electrónico</label>
                        <input wire:model="email" type="email"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Ciudad</label>
                        <input wire:model="city" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Código Postal</label>
                        <input wire:model="zip_code" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Dirección</label>
                        <input wire:model="address" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">Notas</label>
                        <textarea wire:model="notes" rows="2"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:text-gray-800">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
                        {{ $editingId ? 'Guardar cambios' : 'Crear cliente' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
