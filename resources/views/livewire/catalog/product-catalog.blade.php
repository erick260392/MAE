<div>
    {{-- Hero --}}
    <section class="relative text-white py-16 px-4 overflow-hidden">
        {{-- Imagen de fondo industrial --}}
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
                    Cómo llegar
                </a>
                <a href="tel:+525542305373"
                    class="inline-flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-lg transition">
                    Llamar ahora
                </a>
            </div>

            <div class="relative max-w-2xl">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input id="catalog-search" wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Buscar productos por nombre, SKU o categoría..."
                    class="w-full rounded-lg border border-gray-400 bg-white/95 px-12 py-3 text-base text-gray-900 placeholder:text-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/30">
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Sidebar categorías --}}
            <aside class="lg:w-48 flex-shrink-0">
                <div class="sticky top-20 h-[calc(100vh-120px)] flex flex-col">
                    <h2 class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        Categorías
                    </h2>
                    <ul class="space-y-2 overflow-y-auto pr-2">
                        <li>
                            <button wire:click="setCategory(null)"
                                class="w-full text-left px-3 py-2 text-sm rounded-lg flex items-center gap-2 transition {{ is_null($activeCategory) ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
                                Todos los productos
                            </button>
                        </li>
                        @foreach($categories as $category)
                        <li>
                            <button wire:click="setCategory({{ $category->id }})"
                                class="w-full text-left px-3 py-2 text-sm rounded-lg flex items-center gap-2 transition {{ $activeCategory === $category->id ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                {{ $category->name }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            {{-- Grid de productos --}}
            <div class="flex-1">
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

                                <div class="bg-gray-50 h-48 flex items-center justify-center overflow-hidden p-4 cursor-pointer" @click="show({{ $product->id }})">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
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
                                        <div class="flex items-center justify-between text-xs text-gray-600">
                                            <span>✓ Envío rápido</span>
                                            <span>◎ Garantía 1 año</span>
                                        </div>
                                    </div>

                                    <button @click.stop="$wire.addToCart({{ $product->id }}, 1)"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-3 py-2.5 rounded-lg transition-colors">
                                        + Cotizar
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Modal detalle --}}
                        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4" @click="hide()" style="display:none">
                            <div class="bg-slate-50 rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden border border-slate-200 max-h-[90vh] overflow-y-auto" @click.stop>
                                <div class="bg-slate-100 h-48 flex items-center justify-center p-4">
                                    <template x-if="product.image">
                                        <img :src="product.image" :alt="product.name" class="w-full h-full object-contain">
                                    </template>
                                    <template x-if="!product.image">
                                        <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </template>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <span class="text-xs text-orange-500 font-medium" x-text="product.category"></span>
                                            <h2 class="text-xl font-bold text-slate-900 mt-1" x-text="product.name"></h2>
                                        </div>
                                        <div class="flex gap-2">
                                            <button @click="$wire.addToCart(product.id, 1); hide()"
                                                class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Cotizar
                                            </button>
                                            <button @click="hide()"
                                                class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                                                Cerrar
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                        <div class="lg:col-span-2 space-y-4">
                                            <div class="bg-white/90 border border-slate-200 rounded-2xl p-4 shadow-sm">
                                                <div class="flex items-center justify-between gap-3 mb-4">
                                                    <div>
                                                        <p class="text-xs uppercase text-slate-500 tracking-[0.2em]">Cuerdas y conexión</p>
                                                        <h3 class="text-lg font-semibold text-slate-900">Detalle técnico</h3>
                                                    </div>
                                                    <span class="text-xs font-semibold text-orange-700 bg-orange-100/90 px-2 py-1 rounded-full">Visual</span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <template x-if="product.image">
                                                        <div class="relative rounded-[24px] overflow-hidden h-40 bg-slate-50 border border-slate-200">
                                                            <img :src="product.image" :alt="product.name + ' cuerda'" class="w-full h-full object-cover">
                                                            <div class="absolute bottom-0 left-0 right-0 bg-slate-900/80 text-white text-xs uppercase tracking-[0.12em] px-3 py-2">Cuerdas</div>
                                                        </div>
                                                        <div class="relative rounded-[24px] overflow-hidden h-40 bg-slate-50 border border-slate-200">
                                                            <img :src="product.image" :alt="product.name + ' entrada'" class="w-full h-full object-cover">
                                                            <div class="absolute bottom-0 left-0 right-0 bg-slate-900/80 text-white text-xs uppercase tracking-[0.12em] px-3 py-2">Entrada</div>
                                                        </div>
                                                    </template>
                                                    <template x-if="!product.image">
                                                        <div class="rounded-[24px] h-40 bg-slate-200 border border-slate-300 flex items-center justify-center text-slate-500 text-sm">Imagen de cuerdas</div>
                                                        <div class="rounded-[24px] h-40 bg-slate-200 border border-slate-300 flex items-center justify-center text-slate-500 text-sm">Imagen de entrada</div>
                                                    </template>
                                                </div>
                                                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Descripción</p>
                                                        <p class="text-slate-700 leading-relaxed" x-text="product.detailCuerdas"></p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Aplicación</p>
                                                        <p class="text-slate-700 leading-relaxed" x-text="product.detailConexion"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            <div class="bg-white/90 border border-slate-200 rounded-2xl p-4 shadow-sm">
                                                <div class="flex items-center justify-between gap-3 mb-4">
                                                    <div>
                                                        <p class="text-xs uppercase text-slate-500 tracking-[0.2em]">Manguera</p>
                                                        <h3 class="text-base font-semibold text-slate-900">Características</h3>
                                                    </div>
                                                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">Central</div>
                                                </div>
                                                <div class="space-y-3">
                                                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
                                                        <p class="text-xs text-slate-500 uppercase">Material</p>
                                                        <p class="mt-1 font-semibold text-slate-900" x-text="product.hoseMaterial"></p>
                                                    </div>
                                                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
                                                        <p class="text-xs text-slate-500 uppercase">Grueso</p>
                                                        <p class="mt-1 font-semibold text-slate-900" x-text="product.hoseThickness"></p>
                                                    </div>
                                                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
                                                        <p class="text-xs text-slate-500 uppercase">Tipo</p>
                                                        <p class="mt-1 font-semibold text-slate-900" x-text="product.hoseType"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

</div>
