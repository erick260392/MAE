<div class="space-y-6">

    {{-- KPIs principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Total de productos --}}
        <div class="mae-stat">
            <div class="flex items-start justify-between">
                <div>
                    <p class="mae-stat-label">Productos</p>
                    <p class="mae-stat-value">{{ $stats['totalProducts'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/20">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-[#90a5c0]">{{ $activeCategories }} categorías activas</p>
        </div>

        {{-- Total clientes --}}
        <div class="mae-stat">
            <div class="flex items-start justify-between">
                <div>
                    <p class="mae-stat-label">Clientes</p>
                    <p class="mae-stat-value">{{ $stats['totalCustomers'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500/20">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-[#90a5c0]">Activos en sistema</p>
        </div>

        {{-- Cotizaciones pendientes --}}
        <div class="mae-stat border-yellow-500/20 bg-gradient-to-br from-yellow-500/5 to-transparent">
            <div class="flex items-start justify-between">
                <div>
                    <p class="mae-stat-label text-yellow-200">Pendientes</p>
                    <p class="mae-stat-value text-yellow-300">{{ $stats['pendingQuotes'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-500/20">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-yellow-300/70">Por confirmar</p>
        </div>

        {{-- Total cotizaciones --}}
        <div class="mae-stat">
            <div class="flex items-start justify-between">
                <div>
                    <p class="mae-stat-label">Cotizaciones</p>
                    <p class="mae-stat-value">{{ $stats['totalQuotes'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-500/20">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-[#90a5c0]">Conversión: {{ $conversionRate }}%</p>
        </div>

        {{-- Ingresos confirmados --}}
        <div class="mae-stat">
            <div class="flex items-start justify-between">
                <div>
                    <p class="mae-stat-label">Ingresos</p>
                    <p class="mae-stat-value text-mae-gold text-lg">${{ number_format($stats['totalRevenue'], 0) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-mae-gold/20">
                    <svg class="w-6 h-6 text-mae-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-[#90a5c0]">Valor promedio: ${{ number_format($averageQuoteValue, 0) }}</p>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Gráfico: Cotizaciones por estado --}}
        <div class="mae-panel p-6">
            <h3 class="mb-4 font-display text-lg font-bold uppercase tracking-[0.14em] text-white">Estado de cotizaciones</h3>
            <div class="relative h-64">
                <canvas id="quoteStatusChart" class="mx-auto"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-2 text-xs">
                <div class="rounded-lg bg-white/5 p-2 text-center">
                    <p class="text-[#90a5c0]">Pendiente</p>
                    <p class="font-bold text-yellow-300">{{ $quotesByStatus['pendiente'] }}</p>
                </div>
                <div class="rounded-lg bg-white/5 p-2 text-center">
                    <p class="text-[#90a5c0]">Confirmada</p>
                    <p class="font-bold text-green-300">{{ $quotesByStatus['confirmada'] }}</p>
                </div>
                <div class="rounded-lg bg-white/5 p-2 text-center">
                    <p class="text-[#90a5c0]">Cancelada</p>
                    <p class="font-bold text-red-300">{{ $quotesByStatus['cancelada'] }}</p>
                </div>
            </div>
        </div>

        {{-- Gráfico: Tendencia mensual --}}
        <div class="mae-panel p-6">
            <h3 class="mb-4 font-display text-lg font-bold uppercase tracking-[0.14em] text-white">Cotizaciones últimos 6 meses</h3>
            <div class="relative h-64">
                <canvas id="monthlyQuotesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Alertas y acceso rápido --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Stock bajo --}}
        <div class="mae-panel p-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-lg font-bold uppercase tracking-[0.14em] text-white">⚠️ Stock bajo</h3>
                <a href="{{ route('admin.inventory') }}" class="text-xs text-mae-gold hover:text-mae-gold-soft">Ver inventario</a>
            </div>
            <div class="space-y-2">
                @forelse($lowStockProducts as $product)
                <div class="flex items-center justify-between rounded-lg bg-white/5 p-3">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-white text-sm">{{ $product->name }}</p>
                        <p class="text-xs text-[#90a5c0]">{{ $product->unit }}</p>
                    </div>
                    <span class="ml-2 shrink-0 rounded-full bg-red-500/20 px-2.5 py-1 text-xs font-semibold text-red-300">{{ $product->stock }}</span>
                </div>
                @empty
                <p class="text-sm text-[#90a5c0]">Todo el stock está bien</p>
                @endforelse
            </div>
        </div>

        {{-- Clientes top --}}
        <div class="mae-panel p-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-lg font-bold uppercase tracking-[0.14em] text-white">👥 Clientes top</h3>
                <a href="{{ route('admin.customers') }}" class="text-xs text-mae-gold hover:text-mae-gold-soft">Ver todos</a>
            </div>
            <div class="space-y-2">
                @forelse($topCustomers as $customer)
                <div class="flex items-center justify-between rounded-lg bg-white/5 p-3">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-white text-sm">{{ $customer->name }}</p>
                        @if($customer->company)
                            <p class="truncate text-xs text-[#90a5c0]">{{ $customer->company }}</p>
                        @endif
                    </div>
                    <span class="ml-2 shrink-0 rounded-full bg-purple-500/20 px-2.5 py-1 text-xs font-semibold text-purple-300">{{ $customer->quotes_count }}</span>
                </div>
                @empty
                <p class="text-sm text-[#90a5c0]">Sin clientes aún</p>
                @endforelse
            </div>
        </div>

        {{-- Productos más solicitados --}}
        <div class="mae-panel p-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-lg font-bold uppercase tracking-[0.14em] text-white">⭐ Más solicitados</h3>
                <a href="{{ route('admin.products') }}" class="text-xs text-mae-gold hover:text-mae-gold-soft">Ver todos</a>
            </div>
            <div class="space-y-2">
                @forelse($topProducts as $product)
                <div class="flex items-center justify-between rounded-lg bg-white/5 p-3">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-white text-sm">{{ $product->name }}</p>
                        <p class="text-xs text-mae-gold">${{ number_format($product->price, 2) }}</p>
                    </div>
                    <span class="ml-2 shrink-0 rounded-full bg-mae-gold/20 px-2.5 py-1 text-xs font-semibold text-mae-gold">{{ $product->quote_items_count }}</span>
                </div>
                @empty
                <p class="text-sm text-[#90a5c0]">Sin cotizaciones</p>
                @endforelse
            </div>
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
            <div class="flex items-center justify-between px-6 py-4 hover:bg-white/3 transition-colors">
                <div class="min-w-0">
                    <div class="flex items-center gap-3">
                        <p class="font-display text-lg font-bold uppercase tracking-[0.14em] text-white">{{ $quote->folio }}</p>
                        <span class="shrink-0 text-xs text-[#90a5c0]">{{ $quote->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-sm text-[#b7c6da]">{{ $quote->customer->name }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="shrink-0 text-sm font-medium text-white">${{ number_format($quote->total, 2) }}</span>
                    <span @class([
                        'mae-badge shrink-0',
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    // Colores consistentes con el tema MAE
    const chartColors = {
        pending: 'rgba(251, 191, 36, 0.8)',     // amber/yellow
        confirmed: 'rgba(34, 197, 94, 0.8)',    // green
        cancelled: 'rgba(239, 68, 68, 0.8)',    // red
        background1: 'rgba(51, 65, 85, 0.1)',
        background2: 'rgba(30, 130, 206, 0.1)',
        text: 'rgba(255, 255, 255, 0.7)',
    };

    // Gráfico: Cotizaciones por estado (Doughnut)
    const statusCtx = document.getElementById('quoteStatusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pendiente', 'Confirmada', 'Cancelada'],
                datasets: [{
                    data: [
                        {{ $quotesByStatus['pendiente'] }},
                        {{ $quotesByStatus['confirmada'] }},
                        {{ $quotesByStatus['cancelada'] }}
                    ],
                    backgroundColor: [
                        chartColors.pending,
                        chartColors.confirmed,
                        chartColors.cancelled,
                    ],
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                    }
                }
            }
        });
    }

    // Gráfico: Tendencia mensual (Line)
    const monthlyCtx = document.getElementById('monthlyQuotesChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyQuotes['labels']) !!},
                datasets: [{
                    label: 'Cotizaciones',
                    data: {!! json_encode($monthlyQuotes['values']) !!},
                    borderColor: '#FBB924',
                    backgroundColor: chartColors.background1,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#FBB924',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                        },
                        ticks: {
                            color: chartColors.text,
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: chartColors.text,
                        }
                    }
                }
            }
        });
    }
</script>
@endpush

