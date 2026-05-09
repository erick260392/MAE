<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAE Admin - {{ $title ?? 'Panel' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="text-white" x-data="{ sidebarOpen: false, userMenuOpen: false }">

<div class="relative flex h-screen overflow-hidden">
    <div class="pointer-events-none fixed inset-0 opacity-80">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-mae-gold/70 to-transparent"></div>
        <div class="absolute left-80 top-20 h-72 w-72 rounded-full bg-mae-gold/8 blur-3xl"></div>
        <div class="absolute right-10 top-1/3 h-96 w-96 rounded-full bg-blue-500/8 blur-3xl"></div>
    </div>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak
        @click="sidebarOpen = false"
        x-transition.opacity
        class="fixed inset-0 z-20 bg-black/60 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed lg:static lg:translate-x-0 z-30 flex h-full w-72 shrink-0 flex-col border-r border-white/10 bg-[#050d19]/92 text-white shadow-[24px_0_80px_rgba(0,0,0,0.28)] backdrop-blur-2xl transition-transform duration-300">

        <div class="relative border-b border-white/10 px-6 py-6">
            <div class="flex items-center gap-4">
                <div class="rounded-2xl border border-mae-gold/40 bg-white/6 p-2 shadow-[0_0_30px_rgba(244,179,26,0.12)]">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="/images/logo.png" alt="MAE" class="h-10 w-10 object-contain">
                    @endif
                </div>
                <div>
                    <p class="font-display text-3xl font-bold uppercase tracking-[0.2em] text-white">MAE</p>
                    <p class="font-display text-xs font-semibold uppercase tracking-[0.26em] text-mae-gold">Panel industrial</p>
                </div>
            </div>
            <div class="mt-5 rounded-xl border border-white/10 bg-white/6 px-4 py-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-[#8fa7c5]">Sesión activa</p>
                    </div>
                    <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-green-400 shadow-[0_0_18px_rgba(74,222,128,0.7)]"></span>
                </div>
            </div>
            {{-- Cerrar sidebar en mobile --}}
            <button @click="sidebarOpen = false" class="absolute right-4 top-4 text-[#9db1cc] hover:text-white lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="mae-scrollbar flex-1 space-y-2 overflow-y-auto px-4 py-6">
            <p class="px-3 pb-2 font-display text-xs font-bold uppercase tracking-[0.22em] text-[#6f88aa]">Principal</p>
            <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false"
               @class([
                   'mae-admin-link',
                   'mae-admin-link-active' => request()->routeIs('admin.dashboard'),
                   'mae-admin-link-idle' => !request()->routeIs('admin.dashboard'),
               ])>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <p class="px-3 pb-2 pt-4 font-display text-xs font-bold uppercase tracking-[0.22em] text-[#6f88aa]">Operación</p>
            <a href="{{ route('admin.quotes') }}" @click="sidebarOpen = false"
               @class([
                   'mae-admin-link',
                   'mae-admin-link-active' => request()->routeIs('admin.quotes*'),
                   'mae-admin-link-idle' => !request()->routeIs('admin.quotes*'),
               ])>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="flex-1">Cotizaciones</span>
                @php $unseen = \App\Models\Quote::whereNull('seen_at')->count() @endphp
                @if($unseen > 0)
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#09131f] text-xs font-bold text-mae-gold">{{ $unseen }}</span>
                @endif
            </a>
            <a href="{{ route('admin.customers') }}" @click="sidebarOpen = false"
               @class([
                   'mae-admin-link',
                   'mae-admin-link-active' => request()->routeIs('admin.customers*'),
                   'mae-admin-link-idle' => !request()->routeIs('admin.customers*'),
               ])>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Clientes
            </a>
            <a href="{{ route('admin.products') }}" @click="sidebarOpen = false"
               @class([
                   'mae-admin-link',
                   'mae-admin-link-active' => request()->routeIs('admin.products*'),
                   'mae-admin-link-idle' => !request()->routeIs('admin.products*'),
               ])>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Productos
            </a>
            <a href="{{ route('admin.categories') }}" @click="sidebarOpen = false"
               @class([
                   'mae-admin-link',
                   'mae-admin-link-active' => request()->routeIs('admin.categories*'),
                   'mae-admin-link-idle' => !request()->routeIs('admin.categories*'),
               ])>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Categorías
            </a>
            <a href="{{ route('admin.inventory') }}" @click="sidebarOpen = false"
               @class([
                   'mae-admin-link',
                   'mae-admin-link-active' => request()->routeIs('admin.inventory*'),
                   'mae-admin-link-idle' => !request()->routeIs('admin.inventory*'),
               ])>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                Inventario
            </a>
        </nav>

        <div class="border-t border-white/10 px-4 py-4">
            <a href="{{ route('admin.quotes.create') }}" class="mae-btn-primary mb-3 w-full">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva cotización
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="mae-admin-link mae-admin-link-idle w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenido --}}
    <div class="relative z-10 flex min-w-0 flex-1 flex-col overflow-hidden">
        <livewire:admin.quote-notifications />
        <header class="border-b border-white/10 bg-[#081525]/78 px-4 py-4 shadow-[0_14px_40px_rgba(0,0,0,0.24)] backdrop-blur-2xl">
            <div class="flex items-center justify-between gap-3">
            {{-- Botón hamburguesa mobile --}}
            <button @click="sidebarOpen = true" class="mae-icon-button lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="min-w-0">
                <p class="mae-kicker">Operación</p>
                <h1 class="truncate font-display text-2xl font-bold uppercase tracking-[0.18em] text-white">{{ $title ?? 'Panel de Administración' }}</h1>
            </div>
            <div class="flex items-center gap-2" @click.outside="userMenuOpen = false">
                <a href="{{ route('admin.quotes.create') }}" class="mae-btn-secondary hidden px-3 py-2 text-xs md:inline-flex">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Cotización
                </a>
                {{-- Acceso rápido a nueva cotización en mobile --}}
                <a href="{{ route('admin.quotes.create') }}"
                    class="mae-btn-primary px-3 py-2 text-xs lg:hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Cotización
                </a>
                <div class="relative hidden lg:block">
                    <button @click="userMenuOpen = ! userMenuOpen" class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/6 px-3 py-2 text-sm font-semibold text-[#d6e1ee] transition hover:border-mae-gold/50 hover:text-white">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-mae-gold text-xs font-black text-[#09131f]">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span>{{ auth()->user()->name }}</span>
                        <svg class="h-4 w-4 text-[#8fa7c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="userMenuOpen" x-cloak x-transition.origin.top.right
                        class="absolute right-0 mt-2 w-56 rounded-xl border border-white/10 bg-[#071527] p-2 shadow-[0_24px_70px_rgba(0,0,0,0.42)]">
                        <a href="{{ route('admin.dashboard') }}" class="mae-menu-item">Ir al dashboard</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="mae-menu-item w-full text-left text-red-200 hover:text-red-100">Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </header>

        <main class="mae-scrollbar flex-1 overflow-y-auto bg-transparent p-4 lg:p-6">
            <div class="mx-auto w-full max-w-7xl">
                {{ $slot }}
            </div>
        </main>
    </div>

</div>

@livewireScripts
</body>
</html>
