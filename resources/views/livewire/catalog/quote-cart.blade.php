<div
    x-data="{
        storageKey: 'mae.quote-cart',
        hasRestored: false,
        items: $wire.entangle('items').live,
        showCart: $wire.entangle('showCart').live,
        currentStep: $wire.entangle('currentStep').live,
        submitted: $wire.entangle('submitted').live,
        name: $wire.entangle('name').live,
        phone: $wire.entangle('phone').live,
        company: $wire.entangle('company').live,
        email: $wire.entangle('email').live,
        city: $wire.entangle('city').live,
        notes: $wire.entangle('notes').live,
        init() {
            this.restore();

            [
                'items',
                'showCart',
                'currentStep',
                'submitted',
                'name',
                'phone',
                'company',
                'email',
                'city',
                'notes',
            ].forEach((property) => {
                this.$watch(property, () => this.persist());
            });
        },
        restore() {
            const rawState = localStorage.getItem(this.storageKey);

            if (! rawState) {
                this.hasRestored = true;
                return;
            }

            try {
                const state = JSON.parse(rawState);
                $wire.restoreStateFromBrowser(state);
            } catch (error) {
                localStorage.removeItem(this.storageKey);
            }

            this.hasRestored = true;
        },
        persist() {
            if (! this.hasRestored) {
                return;
            }

            if (this.submitted) {
                localStorage.removeItem(this.storageKey);
                return;
            }

            if (Object.keys(this.items ?? {}).length === 0 && ! this.name && ! this.phone && ! this.company && ! this.email && ! this.city && ! this.notes) {
                localStorage.removeItem(this.storageKey);
                return;
            }

            localStorage.setItem(this.storageKey, JSON.stringify({
                items: this.items,
                showCart: this.showCart,
                currentStep: this.currentStep,
                name: this.name,
                phone: this.phone,
                company: this.company,
                email: this.email,
                city: this.city,
                notes: this.notes,
            }));
        },
    }"
>

    {{-- Botón del carrito en navbar --}}
    <button @click="$wire.showCart = true" class="relative flex items-center gap-2 text-sm text-gray-300 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span class="hidden sm:inline">Cotización</span>
        @if($count > 0)
            <span class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $count }}</span>
        @endif
    </button>

    {{-- Panel lateral --}}
    <div x-show="$wire.showCart" x-transition.opacity class="fixed inset-0 z-50 flex justify-end" style="display:none">

        {{-- Overlay: cierra solo al hacer click directo en él --}}
        <div class="absolute inset-0 bg-black/50" @click="$wire.showCart = false"></div>

        {{-- Panel: detiene propagación para que el overlay no interfiera --}}
        <div class="relative bg-white w-full max-w-md flex flex-col shadow-2xl" @click.stop>

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Mi cotización</h2>
                <button @click="$wire.showCart = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if(! $submitted)
                <div class="px-6 pt-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div @class([
                            'rounded-lg border px-3 py-2 text-xs font-semibold',
                            'border-blue-200 bg-blue-50 text-blue-700' => $currentStep === 1,
                            'border-gray-300 bg-gray-50 text-gray-700' => $currentStep !== 1,
                        ])>
                            1. Resumen
                        </div>
                        <div @class([
                            'rounded-lg border px-3 py-2 text-xs font-semibold',
                            'border-orange-200 bg-orange-50 text-orange-700' => $currentStep === 2,
                            'border-gray-300 bg-gray-50 text-gray-700' => $currentStep !== 2,
                        ])>
                            2. Contacto
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex-1 overflow-y-auto px-6 py-4">

                {{-- Confirmación enviada --}}
                @if($submitted)
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¡Cotización enviada!</h3>
                    <p class="text-sm text-gray-700">Tu solicitud ya quedó registrada.</p>
                    <p class="text-sm text-gray-900 font-semibold mt-2 mb-6">Folio: {{ $submittedQuoteFolio }}</p>
                    <a href="https://wa.me/525542305373" target="_blank"
                        class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Contactar por WhatsApp
                    </a>
                    <button wire:click="startNewQuote" class="block mx-auto mt-3 text-sm font-medium text-gray-600 hover:text-gray-900">
                        Nueva cotización
                    </button>
                </div>

                {{-- Formulario de datos --}}
                @elseif($currentStep === 2)
                <div>
                    <button wire:click="backToSummaryStep" class="flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-gray-900 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Volver al resumen
                    </button>
                    <h3 class="font-semibold text-gray-900 mb-1">Tus datos de contacto</h3>
                    <p class="text-sm text-gray-700 mb-4">Déjanos lo necesario para prepararte una cotización más precisa.</p>
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
                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm text-gray-600 mb-1">Empresa</label>
                                <input wire:model="company" type="text"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm text-gray-600 mb-1">Ciudad</label>
                                <input wire:model="city" type="text"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400">
                                @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Correo electrónico</label>
                            <input wire:model="email" type="email"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Comentarios generales</label>
                            <textarea wire:model="notes" rows="3"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400"
                                placeholder="Medidas especiales, urgencia, etc."></textarea>
                            @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">Productos</span>
                                <span class="font-semibold text-gray-900">{{ count($items) }}</span>
                            </div>
                        </div>
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitQuote"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg text-sm transition-colors mt-2 data-loading:opacity-60 data-loading:pointer-events-none">
                            <span wire:loading.remove wire:target="submitQuote">Enviar cotización</span>
                            <span wire:loading.inline-flex wire:target="submitQuote" class="hidden items-center gap-2">
                                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Enviando...
                            </span>
                        </button>
                    </form>
                </div>

                {{-- Lista de items --}}
                @else
                @if(empty($items))
                    <div class="text-center py-12 text-gray-600">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm font-medium">Tu cotización está vacía.</p>
                    </div>
                @else
                <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 mb-4">
                    <p class="text-sm font-medium text-blue-900">Resumen de tu solicitud</p>
                    <p class="text-xs text-blue-700 mt-1">Ajusta cantidades y agrega detalles por producto para que podamos cotizarte mejor.</p>
                </div>
                <div class="space-y-3">
                    @foreach($items as $productId => $item)
                    <div class="rounded-xl border border-gray-200 p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-600 mt-1">Unidad: {{ $item['unit'] }}</p>
                            </div>
                            <button wire:click="remove({{ $productId }})" type="button" class="text-gray-300 hover:text-red-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="mt-3 flex items-center gap-2">
                            <button wire:click="decrement({{ $productId }})" type="button" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-base font-bold transition-colors">−</button>
                            <input
                                type="number"
                                min="{{ $item['unit'] === 'metro' ? '0.1' : '1' }}"
                                step="{{ $item['unit'] === 'metro' ? '0.1' : '1' }}"
                                value="{{ $item['quantity'] }}"
                                wire:change="updateQuantity({{ $productId }}, $event.target.value)"
                                class="w-20 rounded-lg border border-gray-200 px-3 py-2 text-center text-sm font-medium text-gray-800 focus:outline-none focus:border-orange-400"
                            >
                            <button wire:click="increment({{ $productId }})" type="button" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-base font-bold transition-colors">+</button>
                            <span class="ml-auto rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                {{ $item['quantity'] }} {{ $item['unit'] }}
                            </span>
                        </div>
                        <div class="mt-3">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Detalles del producto</label>
                            <textarea
                                rows="2"
                                wire:change="updateItemNotes({{ $productId }}, $event.target.value)"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-orange-400"
                                placeholder="Medida, tipo de conexión, presión, urgencia o alguna especificación"
                            >{{ $item['notes'] }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                @endif
            </div>

            {{-- Footer --}}
            @if($currentStep === 1 && !$submitted && !empty($items))
            <div class="px-6 py-4 border-t border-gray-100">
                @error('items') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                <div class="flex items-center justify-between mb-3 text-sm">
                    <span class="text-gray-700 font-medium">Productos seleccionados</span>
                    <span class="font-semibold text-gray-900">{{ count($items) }}</span>
                </div>
                <button wire:click="goToContactStep" type="button"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg text-sm transition-colors mb-2">
                    Continuar con mis datos
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
