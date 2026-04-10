<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3">
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
        <button wire:click="openCreate"
            class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            + Nuevo producto
        </button>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Producto</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Categoría</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Precio</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Stock</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Activo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="w-9 h-9 rounded-lg object-cover bg-gray-100">
                            @else
                                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <span class="font-medium text-gray-800">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-6 py-3 text-gray-800">${{ number_format($product->price, 2) }} <span class="text-gray-400 text-xs">/{{ $product->unit }}</span></td>
                    <td class="px-6 py-3">
                        <span @class([
                            'font-medium',
                            'text-red-500' => $product->stock <= 5,
                            'text-gray-800' => $product->stock > 5,
                        ])>{{ $product->stock }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <button wire:click="toggleActive({{ $product->id }})" class="transition-colors">
                            @if($product->active)
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">Activo</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full font-medium">Inactivo</span>
                            @endif
                        </button>
                    </td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <button wire:click="openEdit({{ $product->id }})"
                            class="text-gray-400 hover:text-orange-500 transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $product->id }})"
                            wire:confirm="¿Eliminar este producto?"
                            class="text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">No hay productos.</td>
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
                {{ $editingId ? 'Editar producto' : 'Nuevo producto' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input wire:model="name" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">Descripción</label>
                        <textarea wire:model="description" rows="2"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400"></textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Categoría <span class="text-red-500">*</span></label>
                        <select wire:model="category_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                            <option value="">Seleccionar...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Unidad <span class="text-red-500">*</span></label>
                        <select wire:model="unit"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                            <option value="pieza">Pieza</option>
                            <option value="metro">Metro</option>
                            <option value="rollo">Rollo</option>
                            <option value="juego">Juego</option>
                            <option value="litro">Litro</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Precio (MXN) <span class="text-red-500">*</span></label>
                        <input wire:model="price" type="number" step="0.01" min="0"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Stock <span class="text-red-500">*</span></label>
                        <input wire:model="stock" type="number" min="0"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                        @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">Imagen</label>
                        @if($currentImage && !$image)
                            <img src="{{ Storage::url($currentImage) }}" class="w-16 h-16 rounded-lg object-cover mb-2">
                        @endif
                        @if($image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-16 h-16 rounded-lg object-cover mb-2">
                        @endif
                        <input wire:model="image" type="file" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100">
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 flex items-center gap-2">
                        <input wire:model="active" type="checkbox" id="active" class="rounded border-gray-300 text-orange-500">
                        <label for="active" class="text-sm text-gray-600">Producto activo (visible en catálogo)</label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-200 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
                        {{ $editingId ? 'Guardar cambios' : 'Crear producto' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
