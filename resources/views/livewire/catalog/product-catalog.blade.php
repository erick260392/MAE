<div>
    {{-- Hero --}}
    <section class="relative text-white py-20 px-4 overflow-hidden">
        {{-- Imagen de fondo industrial --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.pexels.com/photos/4483610/pexels-photo-4483610.jpeg?auto=compress&cs=tinysrgb&w=1600"
                alt="Mangueras y conexiones hidráulicas" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-[#1e3a5f]/70"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto text-center">
            @if(file_exists(public_path('images/logo.png')))
                <img src="/images/logo.png" alt="MAE" class="h-28 w-28 object-contain mx-auto mb-6 drop-shadow-2xl">
            @endif
            <h1 class="text-4xl sm:text-5xl font-bold mb-3">
                Mangueras y Conexiones <span class="text-orange-400">MAE</span>
            </h1>
            <p class="text-blue-200 text-lg mb-8">Especialistas en mangueras y conexiones hidráulicas y neumáticas</p>
            <div class="flex items-center justify-center gap-3 mb-8">
                <a href="https://www.google.com/maps/dir/?api=1&destination=Benito+Ju%C3%A1rez+33-29+Habitacional+54038+Tlalnepantla+Mexico" target="_blank"
                    class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Cómo llegar
                </a>
                <a href="tel:+525542305373"
                    class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    Llamar ahora
                </a>
            </div>
            <div class="max-w-md mx-auto relative">
                <svg class="absolute left-4 top-3 w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Buscar productos..."
                    class="w-full bg-white/10 backdrop-blur border border-white/20 text-white rounded-xl pl-10 pr-5 py-3 text-sm focus:outline-none focus:border-orange-400 placeholder-blue-200">
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Sidebar categorías --}}
            <aside class="lg:w-56 flex-shrink-0">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Categorías
                </p>
                <ul class="space-y-1">
                    <li>
                        <button wire:click="setCategory(null)"
                            class="w-full text-left px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2 {{ is_null($activeCategory) ? 'bg-orange-500 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="flex-1">Todos los productos</span>
                        </button>
                    </li>
                    @foreach($categories as $category)
                    <li>
                        <button wire:click="setCategory({{ $category->id }})"
                            class="w-full text-left px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2 {{ $activeCategory === $category->id ? 'bg-orange-500 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span class="flex-1">{{ $category->name }}</span>
                            <span class="text-xs {{ $activeCategory === $category->id ? 'text-orange-100' : 'text-gray-400' }}">{{ $category->products_count }}</span>
                        </button>
                    </li>
                    @endforeach
                </ul>
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
                            show(id) { this.product = this.products.find(p => p.id === id); this.open = true; },
                            hide() { this.open = false; }
                        }"
                    >
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                            @foreach($products as $product)
                            <div class="bg-slate-50 rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md hover:border-slate-300 transition-all group">
                                <div class="p-4 flex items-center justify-between gap-3">
                                    <span class="text-xs text-slate-500">{{ $product->unit === 'metro' ? 'Metro' : 'Pieza' }}</span>
                                    <button type="button" @click="show({{ $product->id }})"
                                        class="text-xs text-orange-600 hover:text-orange-700 font-medium">Ver detalle</button>
                                </div>
                                <div class="bg-slate-100 h-40 flex items-center justify-center overflow-hidden p-3 rounded-t-[28px] cursor-pointer" @click="show({{ $product->id }})">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300 rounded-[24px] border border-slate-200/60 bg-white/80">
                                    @else
                                        <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-1">
                                    <span class="text-xs text-orange-500 font-medium mb-1">{{ $product->category->name }}</span>
                                    <h3 class="font-semibold text-slate-800 text-sm leading-snug mb-2">{{ $product->name }}</h3>

                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            <span class="text-xs text-slate-600">{{ $product->stock }} en stock</span>
                                        </div>
                                        @if($product->stock <= 5)
                                            <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">¡Últimas unidades!</span>
                                        @endif
                                    </div>

                                    @if($product->description)
                                        <p class="text-xs text-slate-500 mb-3 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                                    @endif

                                    <div class="mt-auto space-y-3">
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span>Envío rápido</span>
                                            <span>Garantía 1 año</span>
                                        </div>
                                        <button @click.stop="$wire.addToCart({{ $product->id }}, 1)"
                                            class="w-full bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium px-3 py-2.5 rounded-lg transition-colors flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Cotizar
                                        </button>
                                    </div>
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
