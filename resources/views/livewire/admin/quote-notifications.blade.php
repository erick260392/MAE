<div>
    {{-- Banner superior --}}
    @if($showBanner)
    <div class="bg-orange-500 text-white px-4 py-2.5 flex items-center justify-between gap-4 text-sm">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span>
                Nueva cotización recibida:
                <strong>{{ $latestFolio }}</strong>
                @if($latestCustomer) — {{ $latestCustomer }} @endif
            </span>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('admin.quotes') }}" class="underline font-semibold hover:text-orange-100">Ver cotizaciones</a>
            <button wire:click="dismissBanner" class="hover:text-orange-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Toast esquina inferior derecha --}}
    @if($showToast)
    <div
        x-data="{ visible: true }"
        x-init="setTimeout(() => { visible = false; $wire.dismissToast() }, 6000)"
        x-show="visible"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-6 right-6 z-50 bg-white rounded-xl shadow-2xl border border-orange-100 p-4 w-80"
    >
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800">¡Nueva cotización!</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $latestFolio }}@if($latestCustomer) — {{ $latestCustomer }}@endif
                </p>
                <a href="{{ route('admin.quotes') }}" class="text-xs text-orange-500 font-medium hover:text-orange-600 mt-1 inline-block">
                    Ver ahora →
                </a>
            </div>
            <button wire:click="dismissToast" class="text-gray-300 hover:text-gray-500 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        {{-- Barra de progreso --}}
        <div class="mt-3 h-1 bg-orange-100 rounded-full overflow-hidden">
            <div class="h-full bg-orange-400 rounded-full" style="animation: shrink 6s linear forwards"></div>
        </div>
    </div>
    @endif
</div>

<style>
    @keyframes shrink {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>
