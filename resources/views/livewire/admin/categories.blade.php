<div class="space-y-5">

    {{-- Header --}}
    <div class="mae-toolbar">
        <div class="min-w-0">
            <p class="mae-kicker">Catálogo</p>
            <h2 class="font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">Categorías</h2>
        </div>
        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar categoría..."
            class="mae-input sm:w-64">
        <button wire:click="openCreate" class="mae-btn-primary">
                Nueva categoría
        </button>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="mae-table">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Nombre</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Descripción</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Productos</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-3 font-medium text-white">{{ $category->name }}</td>
                    <td class="px-6 py-3 text-[#b7c6da]">{{ $category->description ?? '—' }}</td>
                    <td class="px-6 py-3 text-[#b7c6da]">{{ $category->products_count }}</td>
                    <td class="px-6 py-3 text-right">
                        <button wire:click="openEdit({{ $category->id }})"
                            class="mae-action-icon">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $category->id }})"
                            wire:confirm="¿Eliminar esta categoría?"
                            class="mae-danger-icon">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-[#92a8c5]">No hay categorías.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
        <div class="mae-panel w-full max-w-md p-6">
            <h2 class="mb-4 font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">
                {{ $editingId ? 'Editar categoría' : 'Nueva categoría' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Nombre <span class="text-red-400">*</span></label>
                    <input wire:model="name" type="text"
                        class="mae-input">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Descripción</label>
                    <input wire:model="description" type="text"
                        class="mae-input">
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="mae-btn-secondary">
                        Cancelar
                    </button>
                    <button type="submit" class="mae-btn-primary">
                        {{ $editingId ? 'Guardar cambios' : 'Crear categoría' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
