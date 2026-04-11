<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar folio o cliente..."
                class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:border-orange-400">
            <select wire:model.live="filterStatus"
                class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                <option value="">Todos los estados</option>
                <option value="pendiente">Pendiente</option>
                <option value="confirmada">Confirmada</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>
        <a href="{{ route('admin.quotes.create') }}"
            class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            + Nueva cotización
        </a>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Folio</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Cliente</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Total</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Estado</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Fecha</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($quotes as $quote)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $quote->folio }}</td>
                    <td class="px-6 py-3">
                        <p class="text-gray-800">{{ $quote->customer->name }}</p>
                        @if($quote->customer->company)
                            <p class="text-xs text-gray-400">{{ $quote->customer->company }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-3 font-medium text-gray-800">${{ number_format($quote->total, 2) }}</td>
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
                    <td class="px-6 py-3 text-gray-500">{{ $quote->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <a href="{{ route('admin.quotes.show', $quote) }}"
                            class="text-gray-400 hover:text-orange-500 transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <button wire:click="delete({{ $quote->id }})"
                            wire:confirm="¿Eliminar esta cotización?"
                            class="text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">No hay cotizaciones.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
