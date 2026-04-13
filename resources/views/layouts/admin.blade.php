<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAE Admin - {{ $title ?? 'Panel' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden">

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-20 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed lg:static lg:translate-x-0 z-30 w-64 h-full bg-[#1e3a5f] text-white flex flex-col flex-shrink-0 transition-transform duration-300">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#2d5a8e]">
            @if(file_exists(public_path('images/logo.png')))
                <img src="/images/logo.png" alt="MAE" class="h-10 w-10 object-contain rounded-lg">
            @endif
            <div>
                <p class="font-bold text-orange-500 leading-tight">MAE</p>
                <p class="text-xs text-blue-300 leading-tight">Hidráulicas y Neumáticas</p>
            </div>
            {{-- Cerrar sidebar en mobile --}}
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-blue-300 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white' : 'text-blue-200 hover:bg-[#2d5a8e]' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.quotes') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm {{ request()->routeIs('admin.quotes*') ? 'bg-orange-500 text-white' : 'text-blue-200 hover:bg-[#2d5a8e]' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Cotizaciones
            </a>
            <a href="{{ route('admin.customers') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm {{ request()->routeIs('admin.customers*') ? 'bg-orange-500 text-white' : 'text-blue-200 hover:bg-[#2d5a8e]' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Clientes
            </a>
            <a href="{{ route('admin.products') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm {{ request()->routeIs('admin.products*') ? 'bg-orange-500 text-white' : 'text-blue-200 hover:bg-[#2d5a8e]' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Productos
            </a>
            <a href="{{ route('admin.categories') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm {{ request()->routeIs('admin.categories*') ? 'bg-orange-500 text-white' : 'text-blue-200 hover:bg-[#2d5a8e]' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Categorías
            </a>
            <a href="{{ route('admin.inventory') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm {{ request()->routeIs('admin.inventory*') ? 'bg-orange-500 text-white' : 'text-blue-200 hover:bg-[#2d5a8e]' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                Inventario
            </a>
        </nav>

        <div class="px-4 py-4 border-t border-[#2d5a8e]">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-3 w-full rounded-lg text-sm text-blue-200 hover:bg-[#2d5a8e]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenido --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
        <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between gap-3">
            {{-- Botón hamburguesa mobile --}}
            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-base font-semibold text-gray-800 truncate">{{ $title ?? 'Panel de Administración' }}</h1>
            <div class="flex items-center gap-2">
                {{-- Acceso rápido a nueva cotización en mobile --}}
                <a href="{{ route('admin.quotes.create') }}"
                    class="lg:hidden bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Cotización
                </a>
                <span class="hidden lg:block text-sm text-gray-500">{{ auth()->user()->name }}</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>

</div>

@livewireScripts
</body>
</html>
