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
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                            {{-- Imagen --}}
                            <div class="bg-gray-50 h-44 flex items-center justify-center overflow-hidden">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
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

                                <div class="mt-auto flex items-center justify-between">
                                    <div>
                                        <p class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                                        <p class="text-xs text-gray-400">por {{ $product->unit }}</p>
                                    </div>
                                    <button wire:click="addToCart({{ $product->id }})"
                                        class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium px-3 py-2 rounded-lg transition-colors flex items-center gap-1">
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
</div>
