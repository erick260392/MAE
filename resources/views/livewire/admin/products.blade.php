<div class="space-y-4">

    {{-- Header --}}
    <div class="mae-toolbar">
        <div class="flex items-center gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar producto..."
                class="mae-input w-56">
            <select wire:model.live="filterCategory"
                class="mae-input w-56">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="openCreate" class="mae-btn-primary">
            + Nuevo producto
        </button>
    </div>

    {{-- Tabla --}}
    <div class="mae-table">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Producto</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Categoría</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Precio</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Stock</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Activo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="h-10 w-10 rounded-lg object-cover bg-white/10">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/6 text-[#84a0c3]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <span class="font-medium text-white">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-[#b7c6da]">{{ $product->category->name }}</td>
                    <td class="px-6 py-3 text-white">${{ number_format($product->price, 2) }} <span class="text-xs text-[#89a2bf]">/{{ $product->unit }}</span></td>
                    <td class="px-6 py-3">
                        <span @class([
                            'font-medium',
                            'text-red-300' => $product->stock <= 5,
                            'text-white' => $product->stock > 5,
                        ])>{{ $product->stock }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <button wire:click="toggleActive({{ $product->id }})" class="transition-colors">
                            @if($product->active)
                                <span class="mae-badge border-green-400/30 bg-green-400/10 text-green-300">Activo</span>
                            @else
                                <span class="mae-badge border-white/12 bg-white/8 text-[#9ab0cc]">Inactivo</span>
                            @endif
                        </button>
                    </td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <button wire:click="openEdit({{ $product->id }})"
                            class="text-[#95aac4] transition-colors hover:text-mae-gold">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $product->id }})"
                            wire:confirm="¿Eliminar este producto?"
                            class="text-[#95aac4] transition-colors hover:text-red-400">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-[#92a8c5]">No hay productos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
        <div class="mae-panel w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
            <h2 class="mb-4 font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">
                {{ $editingId ? 'Editar producto' : 'Nuevo producto' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Nombre <span class="text-red-400">*</span></label>
                        <input wire:model="name" type="text"
                            class="mae-input">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Descripción</label>
                        <textarea wire:model="description" rows="2"
                            class="mae-input"></textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Aplicación</label>
                        <textarea wire:model="application" rows="2" placeholder="Ej: Sistemas hidráulicos de alta presión, maquinaria industrial..."
                            class="mae-input"></textarea>
                        @error('application') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Categoría <span class="text-red-400">*</span></label>
                        <select wire:model="category_id"
                            class="mae-input">
                            <option value="">Seleccionar...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Unidad <span class="text-red-400">*</span></label>
                        <select wire:model="unit"
                            class="mae-input">
                            <option value="pieza">Pieza</option>
                            <option value="metro">Metro</option>
                            <option value="rollo">Rollo</option>
                            <option value="juego">Juego</option>
                            <option value="litro">Litro</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Precio (MXN) <span class="text-red-400">*</span></label>
                        <input wire:model="price" type="number" step="0.01" min="0"
                            class="mae-input">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Stock <span class="text-red-400">*</span></label>
                        <input wire:model="stock" type="number" min="0"
                            class="mae-input">
                        @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Imagen</label>
                        @if($currentImage && !$image)
                            <img src="{{ Storage::url($currentImage) }}" class="w-16 h-16 rounded-lg object-cover mb-2">
                        @endif
                        @if($image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-16 h-16 rounded-lg object-cover mb-2">
                        @endif
                        <input wire:model="image" type="file" accept="image/*"
                            class="w-full text-sm text-[#b6c5d8] file:mr-3 file:rounded-lg file:border-0 file:bg-mae-gold file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-[#071220] hover:file:bg-mae-gold-soft">
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 flex items-center gap-2">
                        <input wire:model="active" type="checkbox" id="active" class="rounded border-white/20 bg-white/8 text-mae-gold">
                        <label for="active" class="text-sm text-[#d7e2ef]">Producto activo (visible en catálogo)</label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="mae-btn-secondary">
                        Cancelar
                    </button>
                    <button type="submit" class="mae-btn-primary">
                        {{ $editingId ? 'Guardar cambios' : 'Crear producto' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
