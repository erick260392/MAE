<div class="space-y-6">

    {{-- Tarjetas de estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="mae-stat">
            <p class="mae-stat-label">Productos</p>
            <p class="mae-stat-value">{{ $totalProducts }}</p>
        </div>
        <div class="mae-stat">
            <p class="mae-stat-label">Clientes</p>
            <p class="mae-stat-value">{{ $totalCustomers }}</p>
        </div>
        <div class="mae-stat">
            <p class="mae-stat-label">Cotizaciones pendientes</p>
            <p class="mae-stat-value text-mae-gold">{{ $pendingQuotes }}</p>
        </div>
        <div class="mae-stat">
            <p class="mae-stat-label">Total cotizaciones</p>
            <p class="mae-stat-value">{{ $totalQuotes }}</p>
        </div>
    </div>

    {{-- Cotizaciones recientes --}}
    <div class="mae-panel overflow-hidden">
        <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
            <h2 class="font-display text-2xl font-bold uppercase tracking-[0.16em] text-white">Cotizaciones recientes</h2>
            <a href="{{ route('admin.quotes') }}" class="text-sm font-semibold text-mae-gold hover:text-mae-gold-soft">Ver todas</a>
        </div>
        <div class="divide-y divide-white/8">
            @forelse($recentQuotes as $quote)
            <div class="flex items-center justify-between px-6 py-4">
                <div>
                    <p class="font-display text-lg font-bold uppercase tracking-[0.14em] text-white">{{ $quote->folio }}</p>
                    <p class="text-sm text-[#b7c6da]">{{ $quote->customer->name }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-white">${{ number_format($quote->total, 2) }}</span>
                    <span @class([
                        'mae-badge',
                        'border-yellow-400/30 bg-yellow-400/12 text-yellow-300' => $quote->status === 'pendiente',
                        'border-green-400/30 bg-green-400/12 text-green-300' => $quote->status === 'confirmada',
                        'border-red-400/30 bg-red-400/12 text-red-300' => $quote->status === 'cancelada',
                    ])>{{ ucfirst($quote->status) }}</span>
                </div>
            </div>
            @empty
            <p class="px-6 py-4 text-sm text-[#92a8c5]">No hay cotizaciones aún.</p>
            @endforelse
        </div>
    </div>

</div>
