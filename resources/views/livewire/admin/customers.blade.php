<div class="space-y-5">

    {{-- Header --}}
    <div class="mae-toolbar">
        <div class="min-w-0">
            <p class="mae-kicker">Relaciones comerciales</p>
            <h2 class="font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">Clientes</h2>
        </div>
        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
            <div class="relative sm:w-80">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#7890af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nombre, empresa o teléfono"
                    class="mae-input pl-10">
            </div>
        <button wire:click="openCreate" class="mae-btn-primary">
                Nuevo cliente
        </button>
        </div>
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
                    <td class="px-6 py-3 text-right">
                        <button wire:click="openEdit({{ $customer->id }})"
                            class="mae-action-icon">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $customer->id }})"
                            wire:confirm="¿Eliminar este cliente? También se eliminarán sus cotizaciones."
                            class="mae-danger-icon">
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
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm">
        <div class="mae-panel mae-scrollbar max-h-[90vh] w-full max-w-2xl overflow-y-auto p-6">
            <p class="mae-kicker">Cliente</p>
            <h2 class="mb-5 mt-1 font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">
                {{ $editingId ? 'Editar cliente' : 'Nuevo cliente' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Nombre <span class="text-red-300">*</span></label>
                        <input wire:model="name" type="text"
                            class="mae-input">
                        @error('name') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Empresa</label>
                        <input wire:model="company" type="text"
                            class="mae-input">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">RFC</label>
                        <input wire:model="rfc" type="text" placeholder="Ej: XAXX010101000"
                            class="mae-input">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Teléfono <span class="text-red-300">*</span></label>
                        <input wire:model="phone" type="text"
                            class="mae-input">
                        @error('phone') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Correo electrónico</label>
                        <input wire:model="email" type="email"
                            class="mae-input">
                        @error('email') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Ciudad</label>
                        <input wire:model="city" type="text"
                            class="mae-input">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Código Postal</label>
                        <input wire:model="zip_code" type="text"
                            class="mae-input">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Dirección</label>
                        <input wire:model="address" type="text"
                            class="mae-input">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Notas</label>
                        <textarea wire:model="notes" rows="2"
                            class="mae-input"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="mae-btn-secondary">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="mae-btn-primary">
                        {{ $editingId ? 'Guardar cambios' : 'Crear cliente' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
