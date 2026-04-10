<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar categoría..."
            class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:border-orange-400">
        <button wire:click="openCreate"
            class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            + Nueva categoría
        </button>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Nombre</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Descripción</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Productos</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $category->description ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $category->products_count }}</td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <button wire:click="openEdit({{ $category->id }})"
                            class="text-gray-400 hover:text-orange-500 transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $category->id }})"
                            wire:confirm="¿Eliminar esta categoría?"
                            class="text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay categorías.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $editingId ? 'Editar categoría' : 'Nueva categoría' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nombre <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Descripción</label>
                    <input wire:model="description" type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-200 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
                        {{ $editingId ? 'Guardar cambios' : 'Crear categoría' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
