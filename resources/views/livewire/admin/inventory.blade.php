<div class="space-y-6">

    <div class="mae-toolbar">
        <div class="min-w-0">
            <p class="mae-kicker">Control de stock</p>
            <h2 class="font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">Inventario</h2>
        </div>
        <div class="flex flex-wrap items-center gap-2 text-xs text-[#9fb3cf]">
            <span class="rounded-full border border-white/10 bg-white/6 px-3 py-1.5">Stock bajo: 1-5</span>
            <span class="rounded-full border border-white/10 bg-white/6 px-3 py-1.5">Ordenado por urgencia</span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <button wire:click="$set('filterStock', '')"
            @class([
                'mae-stat text-left transition hover:-translate-y-0.5 hover:border-mae-gold/50',
                'border-mae-gold/70 bg-mae-gold/10' => $filterStock === '',
            ])>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="mae-stat-label">Productos</p>
                    <p class="mae-stat-value">{{ number_format($totalProducts) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-500/16 text-blue-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="mt-3 text-sm text-[#90a5c0]">Catálogo completo</p>
        </button>

        <button wire:click="$set('filterStock', 'low')"
            @class([
                'mae-stat text-left transition hover:-translate-y-0.5 hover:border-yellow-400/50',
                'border-yellow-400/70 bg-yellow-400/10' => $filterStock === 'low',
            ])>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="mae-stat-label text-yellow-200">Stock bajo</p>
                    <p class="mae-stat-value text-yellow-300">{{ number_format($lowStock) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-400/16 text-yellow-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                </div>
            </div>
            <p class="mt-3 text-sm text-yellow-200/70">Requiere seguimiento</p>
        </button>

        <button wire:click="$set('filterStock', 'zero')"
            @class([
                'mae-stat text-left transition hover:-translate-y-0.5 hover:border-red-400/50',
                'border-red-400/70 bg-red-400/10' => $filterStock === 'zero',
            ])>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="mae-stat-label text-red-200">Sin stock</p>
                    <p class="mae-stat-value text-red-300">{{ number_format($zeroStock) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-400/16 text-red-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                </div>
            </div>
            <p class="mt-3 text-sm text-red-200/70">Prioridad alta</p>
        </button>
    </div>

    <div class="mae-panel-soft p-4">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(0,1fr)_280px_auto] lg:items-center">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#7890af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por producto o SKU"
                    class="mae-input pl-10">
            </div>

            <select wire:model.live="filterCategory" class="mae-input">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            @if($filterStock)
                <button wire:click="$set('filterStock', '')" class="mae-btn-secondary whitespace-nowrap">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
                    Quitar estado
                </button>
            @else
                <span class="hidden text-right text-sm text-[#8ea6c5] lg:block">{{ $products->total() }} registros</span>
            @endif
        </div>
    </div>

    <div class="mae-table hidden overflow-x-auto lg:block">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Unidad</th>
                    <th class="text-center">Stock</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr wire:key="inventory-row-{{ $product->id }}">
                        <td>
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-lg border border-white/10 bg-white object-cover">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg border border-white/10 bg-white/6 text-[#84a0c3]">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="max-w-[420px] truncate font-semibold text-white">{{ $product->name }}</p>
                                    <p class="mt-1 font-mono text-xs text-[#7f98b8]">{{ $product->sku ?: 'Sin SKU' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-[#b7c6da]">{{ $product->category->name }}</td>
                        <td>
                            <span class="mae-badge border-white/10 bg-white/6 text-[#b7c6da]">{{ $product->unit }}</span>
                        </td>
                        <td class="text-center">
                            <span @class([
                                'inline-flex min-w-14 items-center justify-center rounded-full px-3 py-1 text-sm font-bold',
                                'bg-red-400/14 text-red-300 ring-1 ring-red-400/30' => $product->stock === 0,
                                'bg-yellow-400/14 text-yellow-300 ring-1 ring-yellow-400/30' => $product->stock > 0 && $product->stock <= 5,
                                'bg-green-400/12 text-green-300 ring-1 ring-green-400/24' => $product->stock > 5,
                            ])>{{ $product->stock }}</span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openHistory({{ $product->id }})"
                                    class="rounded-lg border border-white/10 bg-white/5 p-2 text-[#9eb2cc] transition hover:border-mae-gold/50 hover:text-mae-gold" title="Ver historial">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                                </button>
                                <button wire:click="openMovement({{ $product->id }})"
                                    class="rounded-lg bg-mae-gold px-3 py-2 text-xs font-bold uppercase tracking-[0.12em] text-[#09131f] transition hover:bg-mae-gold-soft" title="Registrar movimiento">
                                    Movimiento
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg border border-white/10 bg-white/6 text-[#7890af]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0H4"/></svg>
                            </div>
                            <p class="mt-3 font-semibold text-white">No hay productos</p>
                            <p class="mt-1 text-sm text-[#8ea6c5]">Ajusta los filtros para ampliar la búsqueda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-1 gap-3 lg:hidden">
        @forelse($products as $product)
            <div wire:key="inventory-card-{{ $product->id }}" class="mae-panel-soft p-4">
                <div class="flex gap-3">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-lg border border-white/10 bg-white object-cover">
                    @else
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg border border-white/10 bg-white/6 text-[#84a0c3]">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="line-clamp-2 font-semibold text-white">{{ $product->name }}</p>
                                <p class="mt-1 font-mono text-xs text-[#7f98b8]">{{ $product->sku ?: 'Sin SKU' }}</p>
                            </div>
                            <span @class([
                                'shrink-0 rounded-full px-3 py-1 text-sm font-bold',
                                'bg-red-400/14 text-red-300 ring-1 ring-red-400/30' => $product->stock === 0,
                                'bg-yellow-400/14 text-yellow-300 ring-1 ring-yellow-400/30' => $product->stock > 0 && $product->stock <= 5,
                                'bg-green-400/12 text-green-300 ring-1 ring-green-400/24' => $product->stock > 5,
                            ])>{{ $product->stock }}</span>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-[#9fb3cf]">
                            <span class="rounded-full bg-white/6 px-2.5 py-1">{{ $product->category->name }}</span>
                            <span class="rounded-full bg-white/6 px-2.5 py-1">{{ $product->unit }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <button wire:click="openHistory({{ $product->id }})" class="mae-btn-secondary px-3 py-2 text-xs">
                        Historial
                    </button>
                    <button wire:click="openMovement({{ $product->id }})" class="mae-btn-primary px-3 py-2 text-xs">
                        Movimiento
                    </button>
                </div>
            </div>
        @empty
            <div class="mae-panel-soft px-5 py-10 text-center text-[#92a8c5]">No hay productos.</div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="mae-panel-soft flex flex-col gap-3 px-4 py-3 text-sm text-[#9fb3cf] sm:flex-row sm:items-center sm:justify-between">
            <span>Mostrando {{ $products->firstItem() }}-{{ $products->lastItem() }} de {{ $products->total() }}</span>
            <div class="flex flex-wrap gap-1">
                @if($products->onFirstPage())
                    <span class="rounded-lg border border-white/8 px-3 py-1.5 text-[#607896]">Anterior</span>
                @else
                    <button wire:click="previousPage" class="rounded-lg border border-white/12 px-3 py-1.5 text-white transition hover:border-mae-gold/60 hover:text-mae-gold">Anterior</button>
                @endif

                @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                    <button wire:click="gotoPage({{ $page }})"
                        @class([
                            'rounded-lg border px-3 py-1.5 transition',
                            'border-mae-gold bg-mae-gold text-[#09131f]' => $page === $products->currentPage(),
                            'border-white/12 text-white hover:border-mae-gold/60 hover:text-mae-gold' => $page !== $products->currentPage(),
                        ])>
                        {{ $page }}
                    </button>
                @endforeach

                @if($products->hasMorePages())
                    <button wire:click="nextPage" class="rounded-lg border border-white/12 px-3 py-1.5 text-white transition hover:border-mae-gold/60 hover:text-mae-gold">Siguiente</button>
                @else
                    <span class="rounded-lg border border-white/8 px-3 py-1.5 text-[#607896]">Siguiente</span>
                @endif
            </div>
        </div>
    @endif

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm">
            <div class="mae-panel w-full max-w-lg p-6">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="mae-kicker">Movimiento</p>
                        <h2 class="mt-1 font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">Registrar stock</h2>
                        <p class="mt-1 line-clamp-1 text-sm text-[#9fb3cf]">{{ $productName }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/6 px-4 py-3 text-right">
                        <p class="text-xs uppercase tracking-[0.16em] text-[#8ea6c5]">Actual</p>
                        <p class="font-display text-3xl font-bold text-white">{{ $currentStock }}</p>
                    </div>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="$set('type', 'entrada')"
                            @class([
                                'rounded-lg border px-4 py-3 text-sm font-bold transition',
                                'border-green-400 bg-green-400/16 text-green-200' => $type === 'entrada',
                                'border-white/12 bg-white/5 text-[#9fb3cf] hover:border-green-400/50 hover:text-green-200' => $type !== 'entrada',
                            ])>
                            Entrada
                        </button>
                        <button type="button" wire:click="$set('type', 'salida')"
                            @class([
                                'rounded-lg border px-4 py-3 text-sm font-bold transition',
                                'border-red-400 bg-red-400/16 text-red-200' => $type === 'salida',
                                'border-white/12 bg-white/5 text-[#9fb3cf] hover:border-red-400/50 hover:text-red-200' => $type !== 'salida',
                            ])>
                            Salida
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Cantidad <span class="text-red-300">*</span></label>
                            <input wire:model.live="quantity" type="number" min="1" class="mae-input">
                            @error('quantity') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Motivo <span class="text-red-300">*</span></label>
                            <select wire:model="reason" class="mae-input">
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
                            @error('reason') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Notas</label>
                        <textarea wire:model="notes" rows="3" class="mae-input resize-none"></textarea>
                    </div>

                    @if($quantity)
                        <div @class([
                            'rounded-lg border px-4 py-3 text-sm',
                            'border-green-400/24 bg-green-400/10 text-green-200' => $type === 'entrada',
                            'border-red-400/24 bg-red-400/10 text-red-200' => $type === 'salida',
                        ])>
                            Stock resultante:
                            <span class="ml-1 font-display text-xl font-bold">
                                {{ $type === 'entrada' ? $currentStock + (int) $quantity : max(0, $currentStock - (int) $quantity) }}
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" wire:click="$set('showModal', false)" class="mae-btn-secondary">Cancelar</button>
                        <button type="submit"
                            @class([
                                'inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-bold text-white transition',
                                'bg-green-500 hover:bg-green-400' => $type === 'entrada',
                                'bg-red-500 hover:bg-red-400' => $type === 'salida',
                            ])>
                            Registrar {{ $type }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showHistory)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm">
            <div class="mae-panel flex max-h-[84vh] w-full max-w-2xl flex-col p-6">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="mae-kicker">Historial</p>
                        <h2 class="mt-1 font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">Movimientos</h2>
                        <p class="mt-1 line-clamp-1 text-sm text-[#9fb3cf]">{{ $productName }}</p>
                    </div>
                    <button wire:click="$set('showHistory', false)" class="rounded-lg border border-white/10 bg-white/5 p-2 text-[#9fb3cf] transition hover:border-mae-gold/50 hover:text-mae-gold">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto">
                    @if($history->isEmpty())
                        <div class="rounded-lg border border-white/10 bg-white/5 px-5 py-10 text-center text-sm text-[#92a8c5]">Sin movimientos registrados.</div>
                    @else
                        <div class="overflow-hidden rounded-lg border border-white/10">
                            <table class="w-full text-sm">
                                <thead class="sticky top-0 bg-[#102443] text-[#8ea6c5]">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-display text-xs font-semibold uppercase tracking-[0.16em]">Fecha</th>
                                        <th class="px-4 py-3 text-left font-display text-xs font-semibold uppercase tracking-[0.16em]">Tipo</th>
                                        <th class="px-4 py-3 text-center font-display text-xs font-semibold uppercase tracking-[0.16em]">Cant.</th>
                                        <th class="px-4 py-3 text-left font-display text-xs font-semibold uppercase tracking-[0.16em]">Motivo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/8">
                                    @foreach($history as $movement)
                                        <tr wire:key="stock-movement-{{ $movement->id }}" class="transition hover:bg-white/4">
                                            <td class="px-4 py-3 text-xs text-[#92a8c5]">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3">
                                                <span @class([
                                                    'mae-badge',
                                                    'border-green-400/30 bg-green-400/12 text-green-300' => $movement->type === 'entrada',
                                                    'border-red-400/30 bg-red-400/12 text-red-300' => $movement->type === 'salida',
                                                ])>{{ ucfirst($movement->type) }}</span>
                                            </td>
                                            <td @class([
                                                'px-4 py-3 text-center font-bold',
                                                'text-green-300' => $movement->type === 'entrada',
                                                'text-red-300' => $movement->type === 'salida',
                                            ])>
                                                {{ $movement->type === 'entrada' ? '+' : '-' }}{{ $movement->quantity }}
                                            </td>
                                            <td class="px-4 py-3 text-xs text-[#c7d5e7]">{{ $movement->reason }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="mt-5 flex flex-col gap-3 border-t border-white/10 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-sm text-[#9fb3cf]">Stock actual: <span class="font-display text-xl font-bold text-white">{{ $currentStock }}</span></span>
                    <button wire:click="openMovement({{ $productId }})" class="mae-btn-primary">
                        Registrar movimiento
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
