<div x-data="{ open: $wire.entangle('showCart') }">

    {{-- Botón del carrito en navbar --}}
    <button @click="open = true" class="relative flex items-center gap-2 text-sm text-gray-300 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span class="hidden sm:inline">Cotización</span>
        @if($count > 0)
            <span class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $count }}</span>
        @endif
    </button>

    {{-- Panel lateral --}}
    <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex justify-end" style="display:none">

        {{-- Overlay: cierra solo al hacer click directo en él --}}
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

        {{-- Panel: detiene propagación para que el overlay no interfiera --}}
        <div class="relative bg-white w-full max-w-md flex flex-col shadow-2xl" @click.stop>

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Mi cotización</h2>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4">

                {{-- Confirmación enviada --}}
                @if($submitted)
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">¡Cotización enviada!</h3>
                    <p class="text-sm text-gray-500 mb-6">Nos pondremos en contacto contigo a la brevedad.</p>
                    <a href="https://wa.me/525542305373" target="_blank"
                        class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Contactar por WhatsApp
                    </a>
                    <button wire:click="$set('submitted', false)" class="block mx-auto mt-3 text-sm text-gray-400 hover:text-gray-600">
                        Nueva cotización
                    </button>
                </div>

                {{-- Formulario de datos --}}
                @elseif($showForm)
                <div>
                    <button wire:click="$set('showForm', false)" class="flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Volver
                    </button>
                    <h3 class="font-semibold text-gray-800 mb-4">Tus datos de contacto</h3>
                    <form wire:submit.prevent="submitQuote" class="space-y-3">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nombre <span class="text-red-500">*</span></label>
                            <input wire:model="name" type="text"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Teléfono / WhatsApp <span class="text-red-500">*</span></label>
                            <input wire:model="phone" type="text"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Empresa (opcional)</label>
                            <input wire:model="company" type="text"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Notas (opcional)</label>
                            <textarea wire:model="notes" rows="2"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400"
                                placeholder="Medidas especiales, urgencia, etc."></textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg text-sm transition-colors mt-2">
                            Enviar cotización
                        </button>
                    </form>
                </div>

                {{-- Lista de items --}}
                @else
                @if(empty($items))
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm">Tu cotización está vacía.</p>
                    </div>
                @else
                <div class="space-y-3">
                    @foreach($items as $productId => $item)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-50">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-400">{{ $item['unit'] }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button wire:click="decrement({{ $productId }})" type="button" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-base font-bold transition-colors">−</button>
                            <span class="w-10 text-center text-sm font-medium text-gray-800">{{ $item['quantity'] }}</span>
                            <button wire:click="increment({{ $productId }})" type="button" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-base font-bold transition-colors">+</button>
                        </div>
                        <button wire:click="remove({{ $productId }})" type="button" class="text-gray-300 hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @endforeach
                </div>
                @endif
                @endif
            </div>

            {{-- Footer --}}
            @if(!$showForm && !$submitted && !empty($items))
            <div class="px-6 py-4 border-t border-gray-100">
                <button wire:click="$set('showForm', true)" type="button"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg text-sm transition-colors mb-2">
                    Solicitar cotización
                </button>
                <a href="https://wa.me/525542305373" target="_blank"
                    class="w-full flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg text-sm transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Contactar por WhatsApp
                </a>
            </div>
            @endif

        </div>
    </div>
</div>
