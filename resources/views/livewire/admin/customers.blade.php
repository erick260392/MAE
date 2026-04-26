<div class="space-y-4">

    {{-- Header --}}
    <div class="mae-toolbar">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre, empresa o teléfono..."
            class="mae-input w-72">
        <button wire:click="openCreate" class="mae-btn-primary">
            + Nuevo cliente
        </button>
    </div>

    {{-- Tabla --}}
    <div class="mae-table">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Nombre</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Empresa</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Teléfono</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Ciudad</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Cotizaciones</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="px-6 py-3 font-medium text-white">{{ $customer->name }}</td>
                    <td class="px-6 py-3 text-[#b7c6da]">{{ $customer->company ?? '—' }}</td>
                    <td class="px-6 py-3 text-[#b7c6da]">{{ $customer->phone }}</td>
                    <td class="px-6 py-3 text-[#b7c6da]">{{ $customer->city ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="mae-badge border-white/12 bg-white/8 text-[#d9e3ef]">
                            {{ $customer->quotes_count }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <button wire:click="openEdit({{ $customer->id }})"
                            class="text-[#95aac4] transition-colors hover:text-mae-gold">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $customer->id }})"
                            wire:confirm="¿Eliminar este cliente? También se eliminarán sus cotizaciones."
                            class="text-[#95aac4] transition-colors hover:text-red-400">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-[#92a8c5]">No hay clientes.</td>
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
