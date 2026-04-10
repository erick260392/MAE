<div class="space-y-6">

    {{-- Tarjetas de estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Productos</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Clientes</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalCustomers }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Cotizaciones pendientes</p>
            <p class="text-3xl font-bold text-orange-500 mt-1">{{ $pendingQuotes }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total cotizaciones</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalQuotes }}</p>
        </div>
    </div>

    {{-- Cotizaciones recientes --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Cotizaciones recientes</h2>
            <a href="{{ route('admin.quotes') }}" class="text-sm text-orange-500 hover:underline">Ver todas</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentQuotes as $quote)
            <div class="px-6 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $quote->folio }}</p>
                    <p class="text-xs text-gray-500">{{ $quote->customer->name }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">${{ number_format($quote->total, 2) }}</span>
                    <span @class([
                        'text-xs px-2 py-1 rounded-full font-medium',
                        'bg-yellow-100 text-yellow-700' => $quote->status === 'pendiente',
                        'bg-green-100 text-green-700' => $quote->status === 'confirmada',
                        'bg-red-100 text-red-700' => $quote->status === 'cancelada',
                    ])>{{ ucfirst($quote->status) }}</span>
                </div>
            </div>
            @empty
            <p class="px-6 py-4 text-sm text-gray-400">No hay cotizaciones aún.</p>
            @endforelse
        </div>
    </div>

</div>
