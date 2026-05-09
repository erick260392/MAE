<div class="space-y-5">

    {{-- Header --}}
    <div class="mae-toolbar">
        <div class="min-w-0">
            <p class="mae-kicker">Seguimiento comercial</p>
            <h2 class="font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">Cotizaciones</h2>
        </div>
        <div class="flex w-full flex-col gap-3 lg:w-auto lg:flex-row lg:items-center">
        <div class="flex flex-1 items-center gap-2">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar folio o cliente..."
                class="mae-input flex-1">
            <select wire:model.live="filterStatus"
                class="mae-input w-44">
                <option value="">Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="confirmada">Confirmada</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>
        <a href="{{ route('admin.quotes.create') }}" class="mae-btn-primary text-center">
                Nueva cotización
        </a>
        </div>
    </div>

    {{-- Mobile: Cards --}}
    <div class="lg:hidden space-y-3">
        @forelse($quotes as $quote)
        <div class="mae-panel-soft p-4">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <p class="font-display text-xl font-bold uppercase tracking-[0.14em] text-white">{{ $quote->folio }}</p>
                    <p class="text-sm text-[#d6e2ef]">{{ $quote->customer->name }}</p>
                    @if($quote->customer->company)
                        <p class="text-xs text-[#90a5c0]">{{ $quote->customer->company }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="font-bold text-white">${{ number_format($quote->total, 2) }}</p>
                    <p class="text-xs text-[#90a5c0]">{{ $quote->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between border-t border-white/8 pt-3">
                <select wire:change="updateStatus({{ $quote->id }}, $event.target.value)"
                    class="text-xs px-2 py-1.5 rounded-full border-0 font-medium focus:outline-none cursor-pointer
                    {{ $quote->status === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $quote->status === 'confirmada' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $quote->status === 'cancelada' ? 'bg-red-100 text-red-700' : '' }}">
                    <option value="pendiente" {{ $quote->status === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ $quote->status === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelada" {{ $quote->status === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.quotes.show', $quote) }}"
                        class="text-mae-gold text-sm font-medium">Ver detalle</a>
                    <button wire:click="delete({{ $quote->id }})" wire:confirm="¿Eliminar esta cotización?"
                        class="text-red-400 text-sm">Eliminar</button>
                </div>
            </div>
        </div>
        @empty
        <p class="py-8 text-center text-[#92a8c5]">No hay cotizaciones.</p>
        @endforelse
    </div>

    {{-- Desktop: Tabla --}}
    <div class="hidden lg:block mae-table">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Folio</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Cliente</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Total</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Estado</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Fecha</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotes as $quote)
                <tr>
                    <td class="px-6 py-3 font-medium text-white">{{ $quote->folio }}</td>
                    <td class="px-6 py-3">
                        <p class="text-white">{{ $quote->customer->name }}</p>
                        @if($quote->customer->company)
                            <p class="text-xs text-[#90a5c0]">{{ $quote->customer->company }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-3 font-medium text-white">${{ number_format($quote->total, 2) }}</td>
                    <td class="px-6 py-3">
                        <select wire:change="updateStatus({{ $quote->id }}, $event.target.value)"
                            class="text-xs px-2 py-1 rounded-full border-0 font-medium focus:outline-none cursor-pointer
                            {{ $quote->status === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $quote->status === 'confirmada' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $quote->status === 'cancelada' ? 'bg-red-100 text-red-700' : '' }}">
                            <option value="pendiente" {{ $quote->status === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmada" {{ $quote->status === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="cancelada" {{ $quote->status === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </td>
                    <td class="px-6 py-3 text-[#b7c6da]">{{ $quote->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-3 text-right">
                        <a href="{{ route('admin.quotes.show', $quote) }}" class="mae-action-icon">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <button wire:click="delete({{ $quote->id }})" wire:confirm="¿Eliminar esta cotización?" class="mae-danger-icon">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-[#92a8c5]">No hay cotizaciones.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
