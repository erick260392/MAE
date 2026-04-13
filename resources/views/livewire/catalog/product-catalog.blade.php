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
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                        @foreach($products as $product)
                        <div wire:click="openDetail({{ $product->id }})"
                            class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col cursor-pointer hover:shadow-md hover:border-orange-200 transition-all group">
                            {{-- Imagen --}}
                            <div class="bg-gray-50 h-48 flex items-center justify-center overflow-hidden p-3">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>

                            <div class="p-4 flex flex-col flex-1">
                                <span class="text-xs text-orange-500 font-medium mb-1">{{ $product->category->name }}</span>
                                <h3 class="font-semibold text-gray-800 text-sm leading-snug mb-1">{{ $product->name }}</h3>
                                @if($product->description)
                                    <p class="text-xs text-gray-400 mb-3 line-clamp-2">{{ $product->description }}</p>
                                @endif

                                <div class="mt-auto">
                                    <button wire:click.stop="addToCart({{ $product->id }})"
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium px-3 py-2 rounded-lg transition-colors flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Cotizar
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal detalle producto --}}
    @if($selectedProduct)
    <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4" wire:click="closeDetail">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden" wire:click.stop>
            {{-- Imagen --}}
            <div class="bg-gray-50 h-64 flex items-center justify-center p-6">
                @if($selectedProduct->image)
                    <img src="{{ Storage::url($selectedProduct->image) }}" alt="{{ $selectedProduct->name }}"
                        class="w-full h-full object-contain">
                @else
                    <svg class="w-20 h-20 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @endif
            </div>

            <div class="p-6">
                <span class="text-xs text-orange-500 font-medium">{{ $selectedProduct->category->name }}</span>
                <h2 class="text-xl font-bold text-gray-900 mt-1 mb-3">{{ $selectedProduct->name }}</h2>

                @if($selectedProduct->description)
                <div class="mb-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Descripción</p>
                    <p class="text-sm text-gray-600">{{ $selectedProduct->description }}</p>
                </div>
                @endif

                @if($selectedProduct->application)
                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Aplicación</p>
                    <p class="text-sm text-gray-600">{{ $selectedProduct->application }}</p>
                </div>
                @endif

                <div class="flex gap-3 mt-4">
                    <button wire:click="closeDetail"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                        Cerrar
                    </button>
                    <button wire:click="addToCart({{ $selectedProduct->id }}); closeDetail()"
                        class="flex-1 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Agregar a cotización
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
