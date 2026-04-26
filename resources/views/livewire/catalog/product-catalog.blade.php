<div>
    {{-- Hero --}}
    <section class="relative text-white py-16 px-4 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.pexels.com/photos/4483610/pexels-photo-4483610.jpeg?auto=compress&cs=tinysrgb&w=1600"
                alt="Mangueras y conexiones hidráulicas" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-[#1e3a5f]/90 to-[#1e3a5f]/70"></div>
        </div>
        <div class="relative z-10 max-w-5xl mx-auto">
            <div class="mb-8">
                <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-2">
                    Mangueras y Conexiones <span class="text-orange-500">MAE</span>
                </h1>
                <p class="text-base text-gray-200">Especialistas en mangueras y conexiones hidráulicas y neumáticas.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mb-8">
                <a href="https://www.google.com/maps/dir/?api=1&destination=Benito+Ju%C3%A1rez+33-29+Habitacional+54038+Tlalnepantla+Mexico" target="_blank"
                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Cómo llegar
                </a>
                <a href="tel:+525542305373"
                    class="inline-flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    Llamar ahora
                </a>
            </div>
            <div class="relative max-w-2xl">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Buscar productos por nombre, SKU o categoría..."
                    class="w-full rounded-lg border border-gray-400 bg-white/95 px-12 py-3 text-base text-gray-900 placeholder:text-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/30">
            </div>
        </div>
    </section>

    {{-- Nav categorías --}}
    @php
    $catIcons = [
        'Mangueras Hidráulicas'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>',
        'Mangueras Neumáticas'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>',
        'Mangueras Industriales' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>',
        'Conexiones Hidráulicas' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8M5 8l-3 4 3 4M19 8l3 4-3 4"/>',
        'Conexiones Neumáticas'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>',
        'Cilindros y Actuadores' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16M8 4v16M16 4v16"/>',
        'Accesorios y Sellos'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'Coples Rápidos'         => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
        'Cam Lock'               => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
        'FRL y Manómetros'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
        'Accesorios'             => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>',
    ];
    @endphp

    {{-- Nav categorías --}}
    <nav class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-1 overflow-x-auto py-2" style="scrollbar-width:none;-ms-overflow-style:none">
                <button wire:click="setCategory(null)"
                    class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200
                        {{ is_null($activeCategory) ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12M6 6h12m1 1v8a1 1 0 001 1h3m-7-5l-4 4m0-11l4 4m0-11V5"/></svg>
                    Todos
                </button>
                @foreach($categories as $category)
                <button wire:click="setCategory({{ $category->id }})"
                    class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200
                        {{ $activeCategory === $category->id ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $catIcons[$category->name] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>' !!}</svg>
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($products->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <p class="text-lg">No se encontraron productos.</p>
            </div>
        @else
            <div
                x-data="{
                    open: false,
                    product: {},
                    products: {{ \Illuminate\Support\Js::from($productsData) }},
                    show(id) { 
                        this.product = this.products.find(p => p.id === id); 
                        this.open = true; 
                    },
                    hide() { this.open = false; }
                }"
                wire:key="products-container-{{ $activeCategory ?? 'all' }}"
            >
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6" wire:key="products-{{ $activeCategory ?? 'all' }}">
                    @foreach($products as $product)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-all group" wire:key="product-{{ $product->id }}">
                        <div class="p-4 flex items-center justify-between gap-3 border-b border-gray-100">
                            <span class="text-xs text-gray-500 uppercase font-medium">{{ $product->category->name }}</span>
                            <button type="button" @click="show({{ $product->id }})"
                                class="text-xs text-blue-600 hover:text-blue-700 font-semibold">Ver detalle</button>
                        </div>

                        <div class="bg-white h-48 flex items-center justify-center overflow-hidden p-4 cursor-pointer border-b border-gray-100" @click="show({{ $product->id }})">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-md">
                            @else
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </div>

                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-bold text-gray-900 text-sm leading-snug mb-3">{{ $product->name }}</h3>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-xs text-gray-700">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>{{ $product->stock }} en stock</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                        Envío rápido
                                    </span>
                                </div>
                            </div>

                            <button @click.stop="$wire.addToCart({{ $product->id }})"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-3 py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Cotizar
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Modal detalle --}}
                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4" @click="hide()" style="display:none">
                    <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl overflow-hidden border border-gray-200" @click.stop>
                        {{-- Header con imagen y badge --}}
                        <div class="relative">
                            <div class="bg-white h-48 flex items-center justify-center p-6 relative border-b border-gray-100">
                                <template x-if="product.image">
                                    <img :src="product.image" :alt="product.name" class="max-h-full max-w-full object-contain drop-shadow-md">
                                </template>
                                <template x-if="!product.image">
                                    <svg class="w-24 h-24 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </template>
                                <div class="absolute top-4 right-4 bg-orange-500 text-white px-4 py-1 rounded-full text-sm font-bold">En stock</div>
                            </div>
                        </div>

                        {{-- Contenido --}}
                        <div class="p-5">
                            {{-- Título y categoría --}}
                            <div class="mb-4">
                                <span class="text-xs text-blue-600 font-bold uppercase tracking-wider" x-text="product.category"></span>
                                <h2 class="text-xl font-bold text-gray-900 mt-1" x-text="product.name"></h2>
                            </div>

                            {{-- Grid de características --}}
                            <div class="grid grid-cols-2 gap-3 mb-4 pb-4 border-b border-gray-200">
                                <div class="text-center">
                                    <div class="flex justify-center mb-1">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 uppercase mb-0.5">Stock</p>
                                    <p class="text-sm font-bold text-gray-900" x-text="product.stock + ' un.'"></p>
                                </div>
                                <div class="text-center">
                                    <div class="flex justify-center mb-1">
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 uppercase mb-0.5">Envío</p>
                                    <p class="text-sm font-bold text-gray-900">Rápido</p>
                                </div>
                            </div>

                            {{-- Descripción --}}
                            <template x-if="product.description">
                                <div class="mb-4">
                                    <h3 class="text-xs font-bold text-gray-900 uppercase mb-2 flex items-center gap-2">
                                        <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H6a6 6 0 100 12H4a1 1 0 100 2 2 2 0 01-2-2V5zm15.577-1H12a1 1 0 100 2h4.577a2 2 0 110 4H12a1 1 0 100 2h4.577a4 4 0 100-8z"/></svg>
                                        Descripción
                                    </h3>
                                    <p class="text-sm text-gray-700 leading-relaxed" x-text="product.description"></p>
                                </div>
                            </template>

                            {{-- Características especiales --}}
                            <div class="bg-blue-50 rounded-lg p-3 mb-4">
                                <h3 class="text-xs font-bold text-gray-900 uppercase mb-2 flex items-center gap-2">
                                    <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6V7z"/><path fill-rule="evenodd" d="M7 2a1 1 0 012 0v.01a7 7 0 017.99 7.99H17a1 1 0 110 2h-.01a7 7 0 01-7.99 7.99v.01a1 1 0 11-2 0v-.01A7 7 0 012.01 12H2a1 1 0 110-2h.01A7 7 0 017 2.01V2zm9 2a1 1 0 100-2 1 1 0 000 2zM6 19a1 1 0 100-2 1 1 0 000 2z"/></svg>
                                    Ventajas
                                </h3>
                                <ul class="space-y-1 text-xs text-gray-700">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Calidad premium
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Envío rápido
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Soporte técnico
                                    </li>
                                </ul>
                            </div>

                            {{-- Aplicaciones --}}
                            <template x-if="product.application">
                            <div class="bg-orange-50 rounded-lg p-3 mb-4">
                                <h3 class="text-xs font-bold text-gray-900 uppercase mb-2 flex items-center gap-2">
                                    <svg class="w-3 h-3 text-orange-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                                    Aplicación
                                </h3>
                                <p class="text-sm text-gray-700 leading-relaxed" x-text="product.application"></p>
                            </div>
                            </template>

                            {{-- Botones de acción --}}
                            <div class="flex gap-2 mt-4">
                                <button @click="hide()"
                                    class="flex-1 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold px-4 py-2 rounded-lg transition-colors text-sm">
                                    Cerrar
                                </button>
                                <button @click.stop="$wire.addToCart(product.id); hide()"
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold px-4 py-2 rounded-lg transition-all flex items-center justify-center gap-2 shadow-lg text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Cotizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
