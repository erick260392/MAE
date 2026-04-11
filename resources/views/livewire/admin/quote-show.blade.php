<div class="max-w-3xl space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $quote->folio }}</h2>
                <p class="text-sm text-gray-400 mt-1">{{ $quote->created_at->format('d \d\e F \d\e Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                @foreach(['pendiente' => ['bg-yellow-100 text-yellow-700', 'Pendiente'], 'confirmada' => ['bg-green-100 text-green-700', 'Confirmada'], 'cancelada' => ['bg-red-100 text-red-700', 'Cancelada']] as $status => [$classes, $label])
                    <button wire:click="updateStatus('{{ $status }}')"
                        class="text-xs px-3 py-1.5 rounded-full font-medium transition-opacity {{ $classes }} {{ $quote->status === $status ? 'opacity-100 ring-2 ring-offset-1 ring-current' : 'opacity-40 hover:opacity-70' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Cliente --}}
        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400">Cliente</p>
                <p class="font-medium text-gray-800">{{ $quote->customer->name }}</p>
                @if($quote->customer->company)
                    <p class="text-gray-500">{{ $quote->customer->company }}</p>
                @endif
            </div>
            <div>
                <p class="text-gray-400">Contacto</p>
                <p class="text-gray-700">{{ $quote->customer->phone }}</p>
                @if($quote->customer->email)
                    <p class="text-gray-700">{{ $quote->customer->email }}</p>
                @endif
            </div>
            @if($quote->notes)
            <div class="col-span-2">
                <p class="text-gray-400">Notas</p>
                <p class="text-gray-700">{{ $quote->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Producto</th>
                    <th class="text-right px-6 py-3 text-gray-500 font-medium">Precio unit.</th>
                    <th class="text-right px-6 py-3 text-gray-500 font-medium">Cantidad</th>
                    <th class="text-right px-6 py-3 text-gray-500 font-medium">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($quote->items as $item)
                <tr>
                    <td class="px-6 py-3 text-gray-800">{{ $item->product->name }}</td>
                    <td class="px-6 py-3 text-right text-gray-600">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-6 py-3 text-right text-gray-600">{{ $item->quantity }} {{ $item->product->unit }}</td>
                    <td class="px-6 py-3 text-right font-medium text-gray-800">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t border-gray-100 bg-gray-50">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-right font-semibold text-gray-700">Total</td>
                    <td class="px-6 py-3 text-right font-bold text-gray-900 text-base">${{ number_format($quote->total, 2) }} MXN</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="flex justify-between items-center">
        <a href="{{ route('admin.quotes') }}"
            class="text-sm text-gray-500 hover:text-gray-700">← Volver a cotizaciones</a>
        <a href="{{ route('admin.quotes.pdf', $quote) }}" target="_blank"
            wire:navigate.off
            onclick="window.open(this.href, '_blank'); return false;"
            class="flex items-center gap-2 bg-gray-900 hover:bg-gray-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Descargar PDF
        </a>
    </div>

</div>
